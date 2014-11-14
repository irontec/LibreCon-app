package com.irontec.librecon;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.irontec.librecon.api.APILibrecon;
import com.irontec.librecon.deserializers.MeDeserializer;
import com.irontec.librecon.domains.AssistantMeetingDomain;
import com.irontec.librecon.domains.MeDomain;
import com.irontec.librecon.domains.MeetingsDomain;
import com.irontec.librecon.ui.FloatLabelLayout;
import com.nvanbenschoten.motion.ParallaxImageView;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;

import librecon.Me;

public class CodeActivity extends Activity {

    private final static String TAG = CodeActivity.class.getSimpleName();

    // Threads
    private CodeValidationTask codeValidationTask;

    // UI
    private InputMethodManager mInputMethodManager;
    private ParallaxImageView mBackground;
    private EditText mCode;
    private FloatLabelLayout mFloatLabelLayout;
    private LinearLayout mProgressBar;
    private Button mEnterButton;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_code);

        //Mint.initAndStartSession(CodeActivity.this, "6d484d93");

        finishWithUserIfPossible(this);

        mInputMethodManager = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);

        mBackground = (ParallaxImageView) findViewById(R.id.background);
        mBackground.setForwardTiltOffset(.35f);
        mBackground.setParallaxIntensity(1.1f);
        mBackground.registerSensorManager();

        mProgressBar = (LinearLayout) findViewById(R.id.progressBar);
        //mFloatLabelLayout = (FloatLabelLayout) findViewById(R.id.floatLabelLayout);
        mEnterButton = (Button) findViewById(R.id.enterButton);
        mEnterButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mCode.getText() != null && !mCode.getText().toString().isEmpty()) {
                    codeValidationTask = new CodeValidationTask(CodeActivity.this, mCode.getText().toString());
                    codeValidationTask.execute();
                }
            }
        });

        //mCode = (EditText) findViewById(R.id.edit_code);
        /*mCode.addTextChangedListener(new TextWatcher() {
            public void afterTextChanged(Editable s) {
                if (mCode != null && mCode.getText() != null && mCode.getText().toString().isEmpty()) {
                    mInputMethodManager.hideSoftInputFromWindow(mCode.getWindowToken(), 0);
                }
            }

            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        mCode.setOnEditorActionListener(new EditText.OnEditorActionListener() {
            @Override
            public boolean onEditorAction(TextView v, int actionId, KeyEvent event) {
                if (actionId == EditorInfo.IME_ACTION_SEND) {
                    InputMethodManager imm = (InputMethodManager) getSystemService(Context.INPUT_METHOD_SERVICE);
                    imm.hideSoftInputFromWindow(v.getWindowToken(), 0);
                    codeValidationTask = new CodeValidationTask(CodeActivity.this, v.getText().toString());
                    codeValidationTask.execute();
                    return true; // consume.
                }
                return false; // pass on to other listeners.
            }
        });*/

    }

    private class CodeValidationTask extends AsyncTask<Void, Void, Boolean> {

        private Context context;
        private String code;

        private CodeValidationTask(Context context, String code) {
            this.context = context;
            this.code = code;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            // TODO loading
            mProgressBar.setVisibility(View.VISIBLE);
            mFloatLabelLayout.setVisibility(View.GONE);
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            JSONObject response = authCode();
            if (response != null) {
                parseMyInfoAndSave(response);
                return true;
            }
            return false;
        }

        private JSONObject authCode() {
            APILibrecon api = APILibrecon.getInstance();
            try {
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.get(APILibrecon.AUTH_CODE + code);
                if (response != null && !response.has("errorCode")) {
                    return response;
                }
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            } catch (IOException ex) {
                ex.printStackTrace();
            }
            return null;
        }

        private boolean isNetworkAvailable() {
            ConnectivityManager connectivityManager
                    = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
            NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
            return activeNetworkInfo != null && activeNetworkInfo.isConnected();
        }

        private void parseMyInfoAndSave(JSONObject response) {
            GsonBuilder gsonBuilder = new GsonBuilder();
            gsonBuilder.registerTypeAdapter(Me.class, new MeDeserializer());
            Gson gson = gsonBuilder.create();
            JSONObject jsonObject = new JSONObject();
            try {
                jsonObject = response.getJSONObject("data").getJSONObject("assistant");
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            }
            Me me = gson.fromJson(jsonObject.toString(), Me.class);
            MeDomain.clear(context);
            MeetingsDomain.clearMeetings(context) ;
            AssistantMeetingDomain.clearAssistantMeetings(context);
            MeDomain.insertOrUpdate(context, me);
        }

        @Override
        protected void onPostExecute(Boolean aBool) {
            super.onPostExecute(aBool);
            // TODO hide loading
            if (aBool) {
                finishWithUserIfPossible(context);
            } else {
                mProgressBar.setVisibility(View.GONE);
                mFloatLabelLayout.setVisibility(View.VISIBLE);
            }
        }
    }

    private void finishWithUserIfPossible(Context context) {
        Me me = MeDomain.get(this);
        if (me != null && !me.getHash().isEmpty()) {
            Intent intent = new Intent(this, MainActivity.class);
            intent.putExtra("me", me);
            context.startActivity(intent);
            this.finish();
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        mBackground.registerSensorManager();
    }

    @Override
    protected void onPause() {
        super.onPause();
        mBackground.unregisterSensorManager();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.code, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        return super.onOptionsItemSelected(item);
    }
}

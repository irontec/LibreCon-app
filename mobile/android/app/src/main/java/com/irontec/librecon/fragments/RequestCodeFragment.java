package com.irontec.librecon.fragments;

import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.inputmethod.EditorInfo;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.irontec.librecon.DaoApplication;
import com.irontec.librecon.MainActivity;
import com.irontec.librecon.R;
import com.irontec.librecon.api.APILibrecon;
import com.irontec.librecon.deserializers.MeDeserializer;
import com.irontec.librecon.domains.AssistantMeetingDomain;
import com.irontec.librecon.domains.MeDomain;
import com.irontec.librecon.domains.MeetingsDomain;
import com.irontec.librecon.ui.FloatLabelLayout;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;

import librecon.Me;

/**
 * Created by Asier Fernandez on 15/09/14.
 */
public class RequestCodeFragment extends BaseFragment {

    private final static String TAG = ScheduleFragment.class.getSimpleName();

    // UI
    private InputMethodManager mInputMethodManager;
    private LinearLayout mProgressBar;
    private FloatLabelLayout mFloatLabelLayout;
    private Button mEnterButton;
    private EditText mCode;

    // Threads
    private CodeValidationTask mCodeValidationTask;

    public RequestCodeFragment() {
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Tracker tracker = ((DaoApplication) getActivity().getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.fragments.RequestCodeFragment");
        tracker.send(new HitBuilders.AppViewBuilder().build());
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View rootView = inflater.inflate(R.layout.fragment_request_code, container, false);

        mInputMethodManager = (InputMethodManager) getActivity().getSystemService(Context.INPUT_METHOD_SERVICE);

        mProgressBar = (LinearLayout) rootView.findViewById(R.id.progressBar);
        mFloatLabelLayout = (FloatLabelLayout) rootView.findViewById(R.id.floatLabelLayout);

        mCode = (EditText) rootView.findViewById(R.id.edit_code);

        mEnterButton = (Button) rootView.findViewById(R.id.enterButton);
        mEnterButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mCode.getText() != null && !mCode.getText().toString().isEmpty()) {
                    mCodeValidationTask = new CodeValidationTask(getActivity(), mCode.getText().toString());
                    mCodeValidationTask.execute();
                }
            }
        });

        mCode.addTextChangedListener(new TextWatcher() {
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
                    InputMethodManager imm = (InputMethodManager) getActivity().getSystemService(Context.INPUT_METHOD_SERVICE);
                    imm.hideSoftInputFromWindow(v.getWindowToken(), 0);
                    mCodeValidationTask = new CodeValidationTask(getActivity(), v.getText().toString());
                    mCodeValidationTask.execute();
                    return true;
                }
                return false;
            }
        });

        return rootView;
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
                JSONObject jsonObj = new JSONObject();
                jsonObj.put("code", code);
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.post(APILibrecon.AUTH_CODE, jsonObj.toString());
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
            MeetingsDomain.clearMeetings(context);
            AssistantMeetingDomain.clearAssistantMeetings(context);
            MeDomain.insertOrUpdate(context, me);
        }

        @Override
        protected void onPostExecute(Boolean aBool) {
            super.onPostExecute(aBool);
            if (aBool) {
                ((MainActivity) getActivity()).refreshFragmentView();
            } else {
                mProgressBar.setVisibility(View.GONE);
                mFloatLabelLayout.setVisibility(View.VISIBLE);
            }
        }
    }

}

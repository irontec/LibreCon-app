package com.irontec.librecon;

import android.app.Activity;
import android.app.AlertDialog;
import android.app.Dialog;
import android.app.DialogFragment;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.Button;
import android.widget.CheckedTextView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.irontec.librecon.api.APILibrecon;
import com.irontec.librecon.domains.MeDomain;
import com.squareup.picasso.Picasso;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.Arrays;
import java.util.List;

import fr.castorflex.android.smoothprogressbar.SmoothProgressBar;
import librecon.Assistant;
import librecon.Me;

public class AssistantProfileActivity extends Activity {

    private final static String TAG = AssistantProfileActivity.class.getSimpleName();
    private Assistant mAssistant;

    //Threads
    private RequestMeetingTask mRequestMeetingTask;

    // UI
    private SmoothProgressBar mProgressBar;
    private Button mSendRequestButton;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        Tracker tracker = ((DaoApplication) getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.AssistantProfileActivity");
        tracker.send(new HitBuilders.AppViewBuilder().build());

        getActionBar().setDisplayHomeAsUpEnabled(true);
        setContentView(R.layout.activity_assistant_profile);

        Intent intent = getIntent();
        if (intent != null) {
            mAssistant = intent.getExtras().getParcelable("assistant");
        }

        mProgressBar = (SmoothProgressBar) findViewById(R.id.progressbar);
        mSendRequestButton = (Button) findViewById(R.id.send_request);
        ImageView assistantPicture = (ImageView) findViewById(R.id.assistant_picture);
        TextView assistantName = (TextView) findViewById(R.id.assistant_name);
        TextView assistantCompany = (TextView) findViewById(R.id.assistant_company);
        TextView assistantPosition = (TextView) findViewById(R.id.assistant_position);
        LinearLayout assistantLayout = (LinearLayout) findViewById(R.id.interestLayout);

        if (mAssistant != null) {
            if (mAssistant.getPicUrl() != null && !mAssistant.getPicUrl().isEmpty()) {
                Picasso.with(this).load(mAssistant.getPicUrlCircle()).into(assistantPicture);
            } else {
                Picasso.with(this).load(R.drawable.user_placeholder).into(assistantPicture);
            }
            if (!mAssistant.getLastName().isEmpty()) {
                assistantName.setText(mAssistant.getName() + " " + mAssistant.getLastName());
            } else {
                assistantName.setText(mAssistant.getName());
            }
            assistantCompany.setText(mAssistant.getCompany());
            assistantPosition.setText(mAssistant.getPosition());

            generateInterestTv(assistantLayout, mAssistant);

            mSendRequestButton.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    mRequestMeetingTask = new RequestMeetingTask(AssistantProfileActivity.this, mAssistant);
                    mRequestMeetingTask.execute();
                }
            });
        }
    }

    private void generateInterestTv(LinearLayout layout, Assistant assistant) {
        if (assistant.getInterests() != null && !assistant.getInterests().isEmpty()) {
            String interests = assistant.getInterests();
            List<String> interestList = Arrays.asList(interests.split(","));
            for (int i = 0; i < interestList.size(); i++) {
                String interest = interestList.get(i);
                View view = getLayoutInflater().inflate(R.layout.layout_checkedtextview, null);
                CheckedTextView linkChTv = (CheckedTextView) view.findViewById(R.id.interest);
                linkChTv.setText(interest.trim());
                layout.addView(linkChTv);
            }
        }
    }

    private class RequestMeetingTask extends AsyncTask<Void, Void, JSONObject> {

        private Context context;
        private Assistant assistant;

        private RequestMeetingTask(Context context, Assistant assistant) {
            this.context = context;
            this.assistant = assistant;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            mProgressBar.setVisibility(View.VISIBLE);
        }

        @Override
        protected JSONObject doInBackground(Void... params) {
            APILibrecon api = APILibrecon.getInstance();
            JSONObject jsonObj = new JSONObject();
            try {
                jsonObj.put("receiver", assistant.getId());
                String hash = getUserHash();
                if (!isNetworkAvailable()) {
                    return null;
                }
                return api.post(hash, APILibrecon.MEETINGS, jsonObj.toString());
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            } catch (IOException ioEx) {
                ioEx.printStackTrace();
            }
            return null;
        }

        private boolean isNetworkAvailable() {
            ConnectivityManager connectivityManager
                    = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
            NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
            return activeNetworkInfo != null && activeNetworkInfo.isConnected();
        }

        @Override
        protected void onPostExecute(JSONObject jsonObject) {
            super.onPostExecute(jsonObject);
            mProgressBar.setVisibility(View.INVISIBLE);
            if (!jsonObject.has("errorCode")) {
                mSendRequestButton.setBackgroundColor(getResources().getColor(android.R.color.holo_green_light));
                mSendRequestButton.setText(getString(R.string.request_sent));
                mSendRequestButton.setEnabled(false);
            } else {
                int errorCode = 0;
                try {
                    errorCode = jsonObject.getInt("errorCode");
                } catch (JSONException jsEx) {
                    jsEx.printStackTrace();
                }
                if (errorCode == 406 || errorCode == 409) {
                    ErrorDialogFragment errorDialogFragment = new ErrorDialogFragment();
                    errorDialogFragment.show(getFragmentManager(), "error_http_406_409");
                }
            }
        }

        private String getUserHash() {
            Me me = MeDomain.get(context);
            if (me != null) {
                return me.getHash();
            } else {
                return "";
            }
        }
    }

    public static class ErrorDialogFragment extends DialogFragment {
        @Override
        public Dialog onCreateDialog(Bundle savedInstanceState) {
            // Use the Builder class for convenient dialog construction
            AlertDialog.Builder builder = new AlertDialog.Builder(getActivity());
            View view = ((LayoutInflater) getActivity().getSystemService(LAYOUT_INFLATER_SERVICE))
                    .inflate(R.layout.layout_request_meeting_dialog, null);
            builder.setView(view)
                    .setPositiveButton(R.string.accept, new DialogInterface.OnClickListener() {
                        public void onClick(DialogInterface dialog, int id) {
                            dialog.dismiss();
                        }
                    });
            return builder.create();
        }
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.profile, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        if (id == android.R.id.home) {
            finish();
        }
        return super.onOptionsItemSelected(item);
    }

}

package com.irontec.librecon;

import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.preference.PreferenceManager;
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
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.irontec.librecon.api.APILibrecon;
import com.irontec.librecon.deserializers.AssistantDeserializer;
import com.irontec.librecon.deserializers.MeetingDeserializer;
import com.irontec.librecon.domains.AssistantDomain;
import com.irontec.librecon.domains.AssistantMeetingDomain;
import com.irontec.librecon.domains.MeDomain;
import com.irontec.librecon.domains.MeetingsDomain;
import com.irontec.librecon.fragments.NavigationDrawerFragment;
import com.irontec.librecon.utils.DateUtils;
import com.squareup.picasso.Picasso;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;

import fr.castorflex.android.smoothprogressbar.SmoothProgressBar;
import librecon.Assistant;
import librecon.AssistantMeeting;
import librecon.Me;
import librecon.Meeting;

public class MeetingDetailActivity extends BaseActivity {

    private final static String TAG = MeetingDetailActivity.class.getSimpleName();

    private Meeting mMeeting;
    private Assistant mAssistant;

    // Results
    private final int UPDATE_MEETING = 101010;

    private final String SEND_MOMENT_NEVER = "never";
    private final String SEND_MOMENT_ONE_HOUR = "hour";
    private final String SEND_MOMENT_HALF_HOUR = "half";
    private final String SEND_MOMENT_NOW = "now";
    private Integer SHARE_EMAIL = 0;
    private Integer SHARE_PHONE = 0;
    private Boolean MEETING_UPDATED = false;

    // Meeting status
    private static final int STATUS_PENDING = 1;
    private static final int STATUS_ACCEPTED = 2;
    private static final int STATUS_CANCELLED = 3;
    private int CURRENT_MEETING_STATUS = 1;

    // Meeting moments
    private static final String MOMENT_ONE_HOUR = "atOneHour";
    private static final String MOMENT_HALF_HOUR = "atHalfHour";
    private static final String MOMENT_RIGHT_NOW = "atRightNow";

    // UI
    private CheckedTextView mNow;
    private CheckedTextView mHalfHour;
    private CheckedTextView mOneHour;
    private CheckedTextView mPhone;
    private CheckedTextView mEmail;
    private LinearLayout mButtonsLayout;
    private SmoothProgressBar mProgressBar;
    private Button mAcceptBtn;
    private Button mRejectBtn;
    private ImageView mAssistantPicture;
    private TextView mAssistantName;
    private TextView mAssistantCompany;
    private TextView mAssistantPosition;
    private TextView mSharedInfoText;
    private TextView mMeetingCreatedAt;
    private TextView mMeetingRespondedAt;
    private TextView mMeetingPhone;
    private TextView mMeetingEmail;

    // Threads
    private MeetingResponseTask mMeetingResponseTask;
    private SyncMeeting mSyncMeeting;

    private Boolean mNavigateStack = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        Tracker tracker = ((DaoApplication) getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.MeetingDetailActivity");
        tracker.send(new HitBuilders.AppViewBuilder().build());

        getActionBar().setDisplayHomeAsUpEnabled(true);
        setContentView(R.layout.activity_meeting_detail);

        Intent intent = getIntent();
        String meetingId = null;
        if (intent != null) {
            // From notification
            meetingId = intent.getExtras().getString("meetingId");
            // From meeting list
            mMeeting = intent.getExtras().getParcelable("meeting");
            mAssistant = intent.getExtras().getParcelable("assistant");
            mNavigateStack = intent.getExtras().getBoolean("navigateStack");
        }

        mButtonsLayout = (LinearLayout) findViewById(R.id.buttonsLayout);
        mAssistantPicture = (ImageView) findViewById(R.id.assistant_picture);
        mAssistantName = (TextView) findViewById(R.id.assistant_name);
        mAssistantCompany = (TextView) findViewById(R.id.assistant_company);
        mAssistantPosition = (TextView) findViewById(R.id.assistant_position);
        mAcceptBtn = (Button) findViewById(R.id.acceptBtn);
        mRejectBtn = (Button) findViewById(R.id.rejectBtn);
        mProgressBar = (SmoothProgressBar) findViewById(R.id.progressbar);
        mSharedInfoText = (TextView) findViewById(R.id.meeting_shared_info);
        mMeetingCreatedAt = (TextView) findViewById(R.id.meetings_createdat);
        mMeetingRespondedAt = (TextView) findViewById(R.id.meetings_respondedat);
        mMeetingPhone = (TextView) findViewById(R.id.meetings_phone);
        mMeetingEmail = (TextView) findViewById(R.id.meetings_email);

        mNow = (CheckedTextView) findViewById(R.id.now);
        mHalfHour = (CheckedTextView) findViewById(R.id.halfHour);
        mOneHour = (CheckedTextView) findViewById(R.id.oneHour);

        mPhone = (CheckedTextView) findViewById(R.id.phone);
        mEmail = (CheckedTextView) findViewById(R.id.email);

        if (mMeeting == null && mAssistant == null) {
            // Look in database, maybe the user dismissed the notification and ran into the meetings view.
            mMeeting = MeetingsDomain.getMeetingForId(this, Long.valueOf(meetingId));
            if (mMeeting != null) {
                // Get the relation and find the assistant
                AssistantMeeting assistantMeeting = AssistantMeetingDomain.getAssistantMeetingWithMeetingId(this, mMeeting.getId());
                mAssistant = AssistantDomain.getAssistantForId(this, assistantMeeting.getAssistantId());
                setMeetingDetailUI();
            } else {
                // Get the meeting from API
                mSyncMeeting = new SyncMeeting(this, meetingId);
                mSyncMeeting.execute();
            }
        } else {
            setMeetingDetailUI();
        }
    }

    private void setMeetingDetailUI() {
        CURRENT_MEETING_STATUS = mMeeting.getStatus();

        if (mAssistant.getPicUrlCircle() != null && !mAssistant.getPicUrlCircle().isEmpty()) {
            Picasso.with(this).load(mAssistant.getPicUrlCircle()).into(mAssistantPicture);
        } else {
            Picasso.with(this).load(R.drawable.user_placeholder).into(mAssistantPicture);
        }

        mAssistantName.setText(mAssistant.getName() + " " + mAssistant.getLastName());
        mAssistantCompany.setText(mAssistant.getCompany());
        mAssistantPosition.setText(mAssistant.getPosition());

        mMeetingCreatedAt.setText(DateUtils.getPrettyDate(mMeeting.getCreatedAt()));
        mMeetingRespondedAt.setText(DateUtils.getPrettyDate(mMeeting.getResponseDate()));

        if (CURRENT_MEETING_STATUS == STATUS_ACCEPTED || CURRENT_MEETING_STATUS == STATUS_PENDING) {
            mSharedInfoText.setText(getString(R.string.meeting_shared_info));
            mSharedInfoText.setVisibility(View.VISIBLE);
            mPhone.setVisibility(View.VISIBLE);
            mEmail.setVisibility(View.VISIBLE);
        } else if (CURRENT_MEETING_STATUS == STATUS_CANCELLED){
            mSharedInfoText.setVisibility(View.GONE);
            mPhone.setVisibility(View.GONE);
            mEmail.setVisibility(View.GONE);
        }

        mNow.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mNow.isChecked()) {
                    mNow.setChecked(false);
                } else {
                    mNow.setChecked(true);
                    mHalfHour.setChecked(false);
                    mOneHour.setChecked(false);
                }
            }
        });

        mHalfHour.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mHalfHour.isChecked()) {
                    mHalfHour.setChecked(false);
                } else {
                    mHalfHour.setChecked(true);
                    mNow.setChecked(false);
                    mOneHour.setChecked(false);
                }
            }
        });

        mOneHour.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mOneHour.isChecked()) {
                    mOneHour.setChecked(false);
                } else {
                    mOneHour.setChecked(true);
                    mNow.setChecked(false);
                    mHalfHour.setChecked(false);
                }
            }
        });

        mPhone.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mPhone.isChecked()) {
                    mPhone.setChecked(false);
                } else {
                    mPhone.setChecked(true);
                }
            }
        });

        mEmail.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mEmail.isChecked()) {
                    mEmail.setChecked(false);
                } else {
                    mEmail.setChecked(true);
                }
            }
        });

        setControlsValuesFromMeeting();
        disableControlsIfCreatedByMe();

        mAcceptBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String moment = "";
                if (mHalfHour.isChecked())
                    moment = SEND_MOMENT_HALF_HOUR;
                if (mOneHour.isChecked())
                    moment = SEND_MOMENT_ONE_HOUR;
                if (mNow.isChecked())
                    moment = SEND_MOMENT_NOW;
                if (mPhone.isChecked())
                    SHARE_PHONE = 1;
                if (mEmail.isChecked())
                    SHARE_EMAIL = 1;

                mMeetingResponseTask = new MeetingResponseTask(
                        MeetingDetailActivity.this, mMeeting, moment, SHARE_PHONE, SHARE_EMAIL, false);
                mMeetingResponseTask.execute();
            }
        });
        mRejectBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                mMeetingResponseTask = new MeetingResponseTask(
                        MeetingDetailActivity.this, mMeeting, SEND_MOMENT_NEVER, SHARE_PHONE, SHARE_EMAIL, true);
                mMeetingResponseTask.execute();
            }
        });
    }

    private void setControlsValuesFromMeeting() {
        if (!mMeeting.getEmailShare()) {
            mEmail.setChecked(false);
        } else if (mMeeting.getEmailShare() && !mAssistant.getEmail().isEmpty() && mMeeting.getSendedByMe()){
            mMeetingEmail.setText(getString(R.string.info_email) + ": " + mAssistant.getEmail());
            mMeetingEmail.setVisibility(View.VISIBLE);
            mMeetingEmail.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Intent intent = new Intent(Intent.ACTION_SEND);
                    intent.setType("plain/text");
                    intent.putExtra(Intent.EXTRA_EMAIL, new String[] { mAssistant.getEmail() });
                    startActivity(Intent.createChooser(intent, ""));
                }
            });
            mEmail.setVisibility(View.GONE);
        }
        if (!mMeeting.getCellphoneShare()) {
            mPhone.setChecked(false);
        } else if (mMeeting.getCellphoneShare() && !mAssistant.getCellPhone().isEmpty() && mMeeting.getSendedByMe()){
            mMeetingPhone.setText(getString(R.string.info_phone) + ": " +mAssistant.getCellPhone());
            mMeetingPhone.setVisibility(View.VISIBLE);
            mMeetingPhone.setOnClickListener(new View.OnClickListener() {
                @Override
                public void onClick(View v) {
                    Intent intent = new Intent(Intent.ACTION_DIAL);
                    intent.setData(Uri.parse("tel:" + mAssistant.getCellPhone()));
                    startActivity(intent);
                }
            });
            mPhone.setVisibility(View.GONE);
        }
        if (mMeeting.getMoment().equalsIgnoreCase(MOMENT_RIGHT_NOW)) {
            mNow.setChecked(true);
        }
        if (mMeeting.getMoment().equalsIgnoreCase(MOMENT_HALF_HOUR)) {
            mHalfHour.setChecked(true);
        }
        if (mMeeting.getMoment().equalsIgnoreCase(MOMENT_ONE_HOUR)) {
            mOneHour.setChecked(true);
        }
        if (!mOneHour.isChecked() && !mHalfHour.isChecked() && !mNow.isChecked()) {
            mOneHour.setChecked(true); // Default value
        }
    }

    private void disableControlsIfCreatedByMe() {
        if (mMeeting.getSendedByMe() || mMeeting.getStatus() != 1) {
            mHalfHour.setEnabled(false);
            mOneHour.setEnabled(false);
            mNow.setEnabled(false);
            mPhone.setEnabled(false);
            mEmail.setEnabled(false);
            mButtonsLayout.setVisibility(View.GONE);
        }
    }

    public class MeetingResponseTask extends AsyncTask<Void, Void, JSONObject> {

        private Context context;
        private Meeting meeting;
        private String moment;
        private Integer sharePhone;
        private Integer shareEmail;
        private Boolean mustEnd;

        public MeetingResponseTask(
                Context mContext, Meeting meeting, String moment, Integer sharePhone,
                Integer shareEmail, Boolean mustEnd) {
            this.context = mContext;
            this.meeting = meeting;
            this.moment = moment;
            this.sharePhone = sharePhone;
            this.shareEmail = shareEmail;
            this.mustEnd = mustEnd;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            mProgressBar.setVisibility(View.VISIBLE);
        }

        @Override
        protected JSONObject doInBackground(Void... params) {
            APILibrecon api = APILibrecon.getInstance();
            String hash = getUserHash();
            JSONObject jsonObj = new JSONObject();
            try {
                jsonObj.put("meetingId", meeting.getId());
                jsonObj.put("moment", moment);
                jsonObj.put("emailShare", shareEmail);
                jsonObj.put("cellphoneShare", sharePhone);
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.put(hash, APILibrecon.MEETINGS, jsonObj.toString());
                if (response != null && !response.has("errorCode")) {
                    return response;
                } else if (response != null) {
                    handleErrorResponse(response);
                }
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
        protected void onPostExecute(JSONObject aVoid) {
            super.onPostExecute(aVoid);
            MEETING_UPDATED = true;
            mProgressBar.setVisibility(View.INVISIBLE);
            if (mustEnd) {
                finishWithResult();
            } else {
                mAcceptBtn.setBackgroundColor(getResources().getColor(android.R.color.holo_green_light));
                mAcceptBtn.setText(getString(R.string.accepted));
                mAcceptBtn.setEnabled(false);
            }
        }

        private void handleErrorResponse(JSONObject response) throws JSONException {
            if (response.getInt("errorCode") == 401) {
                logout();
            }
            mButtonsLayout.setVisibility(View.VISIBLE);
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

    public class SyncMeeting extends AsyncTask<Void, Void, Boolean> {

        private Context context;
        private String meetingId;

        public SyncMeeting(Context context, String meetingId) {
            this.context = context;
            this.meetingId = meetingId;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            mProgressBar.setVisibility(View.VISIBLE);
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            JSONObject response = downloadDataFromAPI();
            if (response != null) {
                parseMeetingsWithAssistants(response);
                return true;
            }
            return false;
        }

        private JSONObject downloadDataFromAPI() {
            APILibrecon api = APILibrecon.getInstance();
            try {
                String hash = getUserHash();
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.get(hash, APILibrecon.MEETINGS + "/" + meetingId);
                if (response != null && !response.has("errorCode")) {
                    return response;
                } else if (response != null) {
                    handleErrorResponse(response);
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

        private void handleErrorResponse(JSONObject response) throws JSONException {
            if (response.getInt("errorCode") == 401) {
                logout();
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

        private void parseMeetingsWithAssistants(JSONObject jsonObject) {
            GsonBuilder gsonBuilder = new GsonBuilder();
            gsonBuilder.registerTypeAdapter(Assistant.class, new AssistantDeserializer());
            gsonBuilder.registerTypeAdapter(Meeting.class, new MeetingDeserializer());
            Gson gson = gsonBuilder.create();
            try {
                JSONObject jsonObject1 = jsonObject.getJSONObject("data").getJSONObject("meeting");
                mAssistant = gson.fromJson(jsonObject1.getJSONObject("assistant").toString(), Assistant.class);
                mMeeting = gson.fromJson(jsonObject1.toString(), Meeting.class);
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            }
        }

        @Override
        protected void onPostExecute(Boolean result) {
            super.onPostExecute(result);
            if (result) {
                setMeetingDetailUI();
            }
            mProgressBar.setVisibility(View.INVISIBLE);
        }
    }

    public void finishWithResult() {
        if (MEETING_UPDATED)
            setResult(RESULT_OK);
        else
            setResult(RESULT_CANCELED);
        finish();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.meeting_detail, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        if (id == android.R.id.home) {
            if (mNavigateStack) {
                SharedPreferences prefs = PreferenceManager.getDefaultSharedPreferences(this);
                SharedPreferences.Editor editor = prefs.edit();
                editor.putInt(NavigationDrawerFragment.STATE_SELECTED_POSITION, 3);
                editor.commit();
                Intent upIntent = new Intent(this, MainActivity.class);
                startActivity(upIntent);
                finish();
            } else {
                finishWithResult();
            }
        }
        return super.onOptionsItemSelected(item);
    }
}

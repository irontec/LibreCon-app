package com.irontec.librecon.fragments;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.widget.SwipeRefreshLayout;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.irontec.librecon.DaoApplication;
import com.irontec.librecon.MainActivity;
import com.irontec.librecon.MeetingDetailActivity;
import com.irontec.librecon.R;
import com.irontec.librecon.adapters.MeetingsAdapter;
import com.irontec.librecon.api.APILibrecon;
import com.irontec.librecon.comparators.MeetingDateComparator;
import com.irontec.librecon.comparators.MeetingStatusComparator;
import com.irontec.librecon.deserializers.AssistantDeserializer;
import com.irontec.librecon.deserializers.MeetingDeserializer;
import com.irontec.librecon.domains.AssistantDomain;
import com.irontec.librecon.domains.AssistantMeetingDomain;
import com.irontec.librecon.domains.LastModifiedDomain;
import com.irontec.librecon.domains.MeDomain;
import com.irontec.librecon.domains.MeetingsDomain;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.List;

import librecon.Assistant;
import librecon.AssistantMeeting;
import librecon.LastModified;
import librecon.Me;
import librecon.Meeting;

/**
 * Created by Asier Fernandez on 15/09/14.
 */
public class MeetingsFragment extends BaseFragment {

    private final static String TAG = AssistantsFragment.class.getSimpleName();

    // Results
    private final int UPDATE_MEETING = 101010;

    // UI
    private ListView mListView;
    private SwipeRefreshLayout meetingsSwipe;

    // DB
    private List<Meeting> mLocalMeetings;
    private MeetingsAdapter mMeetingsAdapter;

    // Threads
    private LoadMeetingsFromLocalDB loadMeetingsFromLocalDB;
    private SyncMeetings syncMeetings;

    public MeetingsFragment() {
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Tracker tracker = ((DaoApplication) getActivity().getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.fragments.MeetingsFragment");
        tracker.send(new HitBuilders.AppViewBuilder().build());
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_meetings, container, false);

        mListView = (ListView) rootView.findViewById(R.id.meetingsList);
        mListView.setEmptyView(rootView.findViewById(R.id.emptyView));

        meetingsSwipe = (SwipeRefreshLayout) rootView.findViewById(R.id.swipe_meetings);
        setupSwipes();

        Me me = MeDomain.get(getActivity());
        mMeetingsAdapter = new MeetingsAdapter(getActivity(), new ArrayList<Meeting>(), me);

        mListView.setAdapter(mMeetingsAdapter);
        mListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Meeting meeting = (Meeting) parent.getItemAtPosition(position);
                AssistantMeeting assistantMeeting = AssistantMeetingDomain.getAssistantMeetingWithMeetingId(getActivity(), meeting.getId());
                Assistant assistant = AssistantDomain.getAssistantForId(getActivity(), assistantMeeting.getAssistantId());
                //Assistant assistant = mMeetingsAdapter.getItemAssistant(position);
                if (meeting != null && assistant != null) {
                    Intent intent = new Intent(getActivity(), MeetingDetailActivity.class);
                    intent.putExtra("meeting", meeting);
                    intent.putExtra("assistant", assistant);
                    startActivityForResult(intent, UPDATE_MEETING);
                }
            }
        });

        loadMeetingsFromLocalDB = new LoadMeetingsFromLocalDB(getActivity());
        loadMeetingsFromLocalDB.execute();
        syncMeetings = new SyncMeetings(getActivity());
        syncMeetings.execute();

        return rootView;
    }

    @Override
    public void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);
        if (requestCode == UPDATE_MEETING && resultCode == Activity.RESULT_OK) {
            syncMeetings = new SyncMeetings(getActivity());
            syncMeetings.execute();
        }
    }

    private void setupSwipes() {
        meetingsSwipe.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                syncMeetings = new SyncMeetings(getActivity());
                syncMeetings.execute();
            }
        });
        int orange = getResources().getColor(R.color.librecon_main_orange);
        int orangeDark = getResources().getColor(R.color.librecon_main_orange_dark);
        meetingsSwipe.setColorSchemeColors(orange, orangeDark, orange, orangeDark);
    }

    @Override
    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
        inflater.inflate(R.menu.assistants, menu);
    }

    /*
    * AsyncTask that querys the API looking for changes and then updates the local SQLite, loads
    * the results into the adapter and notifies to update the GridViews
    */
    public class SyncMeetings extends AsyncTask<Void, Void, Boolean> {

        private Context context;

        public SyncMeetings(Context context) {
            this.context = context;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            meetingsSwipe.setRefreshing(true);
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            JSONObject response = downloadDataFromAPI();
            if (response != null) {
                parseMeetingsWithAssistantsAndSave(response);
                mLocalMeetings = new ArrayList<Meeting>();
                mLocalMeetings = MeetingsDomain.getAllMeetings(context);
                return true;
            }
            return false;
        }

        private JSONObject downloadDataFromAPI() {
            APILibrecon api = APILibrecon.getInstance();
            try {
                String version = getMeetingsVersion();
                String hash = getUserHash();
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.get(hash, APILibrecon.MEETINGS + APILibrecon.VERSION_PARAMETER + version);
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

        private String getMeetingsVersion() {
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified != null) {
                return lastModified.getMeetings();
            } else {
                return "";
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

        private void parseMeetingsWithAssistantsAndSave(JSONObject jsonObject) {
            List<Meeting> tempMeetingValues = new ArrayList<Meeting>();
            List<Assistant> tempAssistantValues = new ArrayList<Assistant>();
            List<AssistantMeeting> tempAssistantMeetingValues = new ArrayList<AssistantMeeting>();
            GsonBuilder gsonBuilder = new GsonBuilder();
            gsonBuilder.registerTypeAdapter(Assistant.class, new AssistantDeserializer());
            gsonBuilder.registerTypeAdapter(Meeting.class, new MeetingDeserializer());
            Gson gson = gsonBuilder.create();
            String lastModifiedValue = "";
            try {
                JSONArray jsonArray = jsonObject.getJSONObject("data").getJSONArray("meetings");
                lastModifiedValue = jsonObject.getJSONObject("data").getString("version");
                for (int i = 0; i < jsonArray.length(); i++) {
                    JSONObject meetingObj = (JSONObject) jsonArray.get(i);
                    Assistant assistant = gson.fromJson(meetingObj.getJSONObject("assistant").toString(), Assistant.class);
                    Meeting meeting = gson.fromJson(meetingObj.toString(), Meeting.class);
                    tempMeetingValues.add(meeting);
                    tempAssistantValues.add(assistant);
                    AssistantMeeting assistantMeeting = new AssistantMeeting();
                    assistantMeeting.setAssistantId(assistant.getId());
                    assistantMeeting.setMeetingId(meeting.getId());
                    tempAssistantMeetingValues.add(assistantMeeting);
                }
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            }
            MeetingsDomain.insertOrUpdateInTransaction(context, tempMeetingValues);
            AssistantDomain.insertOrUpdateInTransaction(context, tempAssistantValues);
            AssistantMeetingDomain.clearAssistantMeetings(context); // Limitación Green-Dao
            AssistantMeetingDomain.insertOrUpdateInTransaction(context, tempAssistantMeetingValues);
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified == null) {
                lastModified = new LastModified();
                lastModified.setId(1l);
            }
            lastModified.setMeetings(lastModifiedValue);
            LastModifiedDomain.insertOrUpdate(context, lastModified);
        }

        @Override
        protected void onPostExecute(Boolean result) {
            super.onPostExecute(result);
            if (result) {
                Log.d(TAG, "Loaded from API - " + mLocalMeetings.size());
                if (mLocalMeetings != null) {
                    Collections.sort(mLocalMeetings, new MeetingDateComparator());
                    Collections.sort(mLocalMeetings, new MeetingStatusComparator());
                    List<Assistant> assistants = new ArrayList<Assistant>();
                    for (Meeting meeting : mLocalMeetings) {
                        AssistantMeeting assistantMeeting = AssistantMeetingDomain.getAssistantMeetingWithMeetingId(getActivity(), meeting.getId());
                        assistants.add(AssistantDomain.getAssistantForId(getActivity(), assistantMeeting.getAssistantId()));
                    }
                    mMeetingsAdapter.addItems(mLocalMeetings, assistants);
                }
            }
            meetingsSwipe.setRefreshing(false);
        }
    }

    private class LoadMeetingsFromLocalDB extends AsyncTask<Void, Void, Void> {

        private Context context;

        private LoadMeetingsFromLocalDB(Context context) {
            this.context = context;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
        }

        @Override
        protected Void doInBackground(Void... params) {
            mLocalMeetings = new ArrayList<Meeting>();
            mLocalMeetings = MeetingsDomain.getAllMeetings(context);
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            Log.d(TAG, "Loaded from local - " + mLocalMeetings.size());
            if (mLocalMeetings != null && !mLocalMeetings.isEmpty()) {
                Collections.sort(mLocalMeetings, new MeetingDateComparator());
                Collections.sort(mLocalMeetings, new MeetingStatusComparator());
                List<Assistant> assistants = new ArrayList<Assistant>();
                for (Meeting meeting : mLocalMeetings) {
                    AssistantMeeting assistantMeeting = AssistantMeetingDomain.getAssistantMeetingWithMeetingId(getActivity(), meeting.getId());
                    assistants.add(AssistantDomain.getAssistantForId(getActivity(), assistantMeeting.getAssistantId()));
                }
                mMeetingsAdapter.addItems(mLocalMeetings, assistants);
            }
        }
    }
}

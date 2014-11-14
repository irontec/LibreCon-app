package com.irontec.librecon.fragments;

import android.app.SearchManager;
import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.widget.SwipeRefreshLayout;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.inputmethod.InputMethodManager;
import android.widget.AdapterView;
import android.widget.GridView;
import android.widget.SearchView;
import android.widget.TabHost;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.irontec.librecon.DaoApplication;
import com.irontec.librecon.MainActivity;
import com.irontec.librecon.R;
import com.irontec.librecon.ScheduleDetailActivity;
import com.irontec.librecon.adapters.ScheduleAdapter;
import com.irontec.librecon.api.APILibrecon;
import com.irontec.librecon.deserializers.ScheduleDeserializer;
import com.irontec.librecon.deserializers.SpeakerDeserializer;
import com.irontec.librecon.domains.LastModifiedDomain;
import com.irontec.librecon.domains.MeDomain;
import com.irontec.librecon.domains.ScheduleDomain;
import com.irontec.librecon.domains.ScheduleSpeakerDomain;
import com.irontec.librecon.domains.SpeakerDomain;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import librecon.LastModified;
import librecon.Me;
import librecon.Schedule;
import librecon.ScheduleSpeaker;
import librecon.Speaker;

/**
 * Created by Asier Fernandez on 15/09/14.
 */
public class ScheduleFragment extends BaseFragment {

    private final static String TAG = ScheduleFragment.class.getSimpleName();

    // DB
    private List<Schedule> mLocalDayOneSchedules;
    private List<Schedule> mLocalDayTwoSchedules;
    private ScheduleAdapter mScheduleDayOneAdapter;
    private ScheduleAdapter mScheduleDayTwoAdapter;

    // Threads
    private LoadSchedulesFromLocalDB loadSchedulesFromLocalDB;
    private SyncSchedules syncSchedules;

    // UI
    private TabHost tabHost;
    private GridView dayOneList;
    private GridView dayTwoList;
    private SwipeRefreshLayout dayOneSwipe;
    private SwipeRefreshLayout dayTwoSwipe;
    private SearchView mSearchView;

    public ScheduleFragment() {
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Tracker tracker = ((DaoApplication) getActivity().getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.fragments.ScheduleFragment");
        tracker.send(new HitBuilders.AppViewBuilder().build());
        setHasOptionsMenu(true);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View rootView = inflater.inflate(R.layout.fragment_schedule, container, false);

        String titleDayOne = getResources().getString(R.string.dia_11);
        String titleDayTwo = getResources().getString(R.string.dia_12);

        // TabHost initialization
        setupTabHost(rootView);
        setupTab(new TextView(getActivity()), 0, titleDayOne, R.id.first_content);
        setupTab(new TextView(getActivity()), 1, titleDayTwo, R.id.second_content);

        tabHost.setCurrentTab(0);
        tabHost.getTabWidget().getChildAt(1).setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mSearchView != null) {
                    mSearchView.setIconified(true);
                }
                if ((Integer) v.getTag() == 1) {
                    tabHost.setCurrentTab(1);
                } else {
                    tabHost.setCurrentTab(0);
                }
            }
        });

        for (int i = 0; i < tabHost.getTabWidget().getChildCount(); i++) {
            TextView tv = (TextView) tabHost.getTabWidget().getChildAt(i).findViewById(android.R.id.title);
            if (tv == null) {
                continue;
            }
            tv.setBackgroundResource(R.drawable.tab_selector_style);
        }

        dayOneList = (GridView) rootView.findViewById(R.id.list_day_one);
        dayTwoList = (GridView) rootView.findViewById(R.id.list_day_two);

        dayOneSwipe = (SwipeRefreshLayout) rootView.findViewById(R.id.swipe_day_one);
        dayTwoSwipe = (SwipeRefreshLayout) rootView.findViewById(R.id.swipe_day_two);

        setupSwipes();

        mScheduleDayOneAdapter = new ScheduleAdapter(
                getActivity(),
                new ArrayList<Schedule>());
        dayOneList.setAdapter(mScheduleDayOneAdapter);
        dayOneList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Schedule item = (Schedule) parent.getItemAtPosition(position);
                getActivity().startActivity(new Intent(getActivity(), ScheduleDetailActivity.class).putExtra("schedule", item));
            }
        });

        mScheduleDayTwoAdapter = new ScheduleAdapter(
                getActivity(),
                new ArrayList<Schedule>());
        dayTwoList.setAdapter(mScheduleDayTwoAdapter);
        dayTwoList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Schedule item = (Schedule) parent.getItemAtPosition(position);
                getActivity().startActivity(new Intent(getActivity(), ScheduleDetailActivity.class).putExtra("schedule", item));
            }
        });

        loadSchedulesFromLocalDB = new LoadSchedulesFromLocalDB(getActivity());
        loadSchedulesFromLocalDB.execute();
        syncSchedules = new SyncSchedules(getActivity());
        syncSchedules.execute();

        return rootView;
    }

    private void setupTabHost(View rootView) {
        tabHost = (TabHost) rootView.findViewById(android.R.id.tabhost);
        tabHost.setup();
    }

    private void setupTab(final View view,int index, final String tag, final int res) {
        View tabview = createTabView(tabHost.getContext(), tag);
        tabview.setTag(index);
        TabHost.TabSpec setContent = tabHost.newTabSpec(tag).setIndicator(tabview).setContent(res);
        tabHost.addTab(setContent);
    }

    private static View createTabView(final Context context, final String text) {
        View view = LayoutInflater.from(context).inflate(R.layout.tab_background, null);
        TextView tv = (TextView) view.findViewById(R.id.tabsText);
        tv.setText(text);
        return view;
    }

    private void setupSwipes() {
        dayOneSwipe.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                //TODO llamada a head para ver cambios
                syncSchedules = new SyncSchedules(getActivity());
                syncSchedules.execute();
            }
        });
        dayTwoSwipe.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                //TODO llamada a head para ver cambios
                syncSchedules = new SyncSchedules(getActivity());
                syncSchedules.execute();

            }
        });
        int orange = getResources().getColor(R.color.librecon_main_orange);
        int orangeDark = getResources().getColor(R.color.librecon_main_orange_dark);
        dayOneSwipe.setColorSchemeColors(orange, orangeDark, orange, orangeDark);
        dayTwoSwipe.setColorSchemeColors(orange, orangeDark, orange, orangeDark);
    }

    @Override
    public void onCreateOptionsMenu(Menu menu, final MenuInflater inflater) {
        inflater.inflate(R.menu.schedule, menu);
        mSearchView = (SearchView) menu.findItem(R.id.search_schedule).getActionView();

        SearchView.OnQueryTextListener queryTextListener = new SearchView.OnQueryTextListener() {
            public boolean onQueryTextChange(String newText) {
                if (tabHost == null || mScheduleDayTwoAdapter == null || mScheduleDayOneAdapter == null) {
                    return true;
                } else {
                    if (tabHost.getCurrentTab() > 0) {
                        mScheduleDayTwoAdapter.getFilter().filter(newText.toString());
                        mScheduleDayOneAdapter.getFilter().filter("");
                        return true;
                    } else {
                        mScheduleDayOneAdapter.getFilter().filter(newText.toString());
                        mScheduleDayTwoAdapter.getFilter().filter("");
                        return true;
                    }
                }
            }

            @Override
            public boolean onQueryTextSubmit(String query) {
                return false;
            }
        };
        mSearchView.setOnQueryTextListener(queryTextListener);
        mSearchView.setOnCloseListener(new SearchView.OnCloseListener() {
            @Override
            public boolean onClose() {
                InputMethodManager imm = (InputMethodManager) getActivity().getSystemService(
                        Context.INPUT_METHOD_SERVICE);
                imm.hideSoftInputFromWindow(mSearchView.getWindowToken(), 0);
                return false;
            }
        });
    }

    /*
     * AsyncTask that querys the API looking for changes and then updates the local SQLite, loads
     * the results into the adapter and notifies to update the GridViews
     */
    public class SyncSchedules extends AsyncTask<Void, Void, Boolean> {

        private Context context;

        public SyncSchedules(Context context) {
            this.context = context;
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            JSONObject response = downloadDataFromAPI();
            if (response != null) {
                parseSchedulesAndSave(response);
                parseSpeakersAndSave(response);
                mLocalDayOneSchedules = new ArrayList<Schedule>();
                mLocalDayTwoSchedules = new ArrayList<Schedule>();
                mLocalDayOneSchedules = ScheduleDomain.getAllDayOneSchedules(context);
                mLocalDayTwoSchedules = ScheduleDomain.getAllDayTwoSchedules(context);
                return true;
            }
            return false;
        }

        private JSONObject downloadDataFromAPI() {
            APILibrecon api = APILibrecon.getInstance();
            try {
                String version = getSchedulesVersion();
                String hash = getUserHash();
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.get(hash, APILibrecon.SCHEDULES + APILibrecon.VERSION_PARAMETER + version);
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
            if (response.getInt("errorCode") == 500) {
                Toast.makeText(getActivity(), R.string.http_500, Toast.LENGTH_LONG).show();
            } /*else if (response.getInt("errorCode") == 401) {
                logout();
            }*/
        }

        private String getSchedulesVersion() {
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified != null) {
                return lastModified.getSchedules();
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

        private void parseSchedulesAndSave(JSONObject jsonObject) {
            List<Schedule> tempValues = new ArrayList<Schedule>();
            GsonBuilder gsonBuilder = new GsonBuilder();
            gsonBuilder.registerTypeAdapter(Schedule.class, new ScheduleDeserializer());
            Gson gson = gsonBuilder.create();
            String lastModifiedValue = "";
            try {
                JSONArray jsonArray = jsonObject.getJSONObject("data").getJSONArray("schedules");
                lastModifiedValue = jsonObject.getJSONObject("data").getString("version");
                for (int i = 0; i < jsonArray.length(); i++) {
                    tempValues.add(gson.fromJson(jsonArray.get(i).toString(), Schedule.class));
                }
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            }
            ScheduleDomain.insertOrUpdateInTransaction(context, tempValues);
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified == null) {
                lastModified = new LastModified();
                lastModified.setId(1l);
            }
            lastModified.setSchedules(lastModifiedValue);
            LastModifiedDomain.insertOrUpdate(context, lastModified);
        }

        private void parseSpeakersAndSave(JSONObject jsonObject) {
            List<Speaker> tempValues = new ArrayList<Speaker>();
            List<ScheduleSpeaker> tempRelValues = new ArrayList<ScheduleSpeaker>();
            GsonBuilder gsonBuilder = new GsonBuilder();
            gsonBuilder.registerTypeAdapter(Speaker.class, new SpeakerDeserializer());
            Gson gson = gsonBuilder.create();
            try {
                JSONArray jsonArray = jsonObject.getJSONObject("data").getJSONArray("schedules");
                for (int i = 0; i < jsonArray.length(); i++) {
                    JSONObject jsonObject1 = (JSONObject) jsonArray.get(i);
                    Long scheduleId = jsonObject1.getLong("id");
                    JSONArray speakers = jsonObject1.getJSONArray("speakers");
                    for (int j = 0; j < speakers.length(); j++) {
                        Speaker speaker = gson.fromJson(speakers.get(j).toString(), Speaker.class);
                        tempValues.add(speaker);
                        ScheduleSpeaker scheduleSpeaker = new ScheduleSpeaker();
                        scheduleSpeaker.setScheduleId(scheduleId);
                        scheduleSpeaker.setSpeakerId(speaker.getId());
                        tempRelValues.add(scheduleSpeaker);
                    }
                }
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            }
            SpeakerDomain.insertOrUpdateInTransaction(context, tempValues);
            ScheduleSpeakerDomain.clearScheduleSpeakers(context); // Limitación de GreenDao
            ScheduleSpeakerDomain.insertOrUpdateInTransaction(context, tempRelValues);
        }

        @Override
        protected void onPostExecute(Boolean result) {
            super.onPostExecute(result);
            if (result) {
                if (mLocalDayOneSchedules != null) {
                    for (Schedule schedule : mLocalDayOneSchedules) {
                        Speaker speaker = SpeakerDomain.getSpeakersOfSchedule(getActivity(), schedule.getId());
                        if (speaker!= null)
                            schedule.setSpeakers(speaker.getName());
                        else
                            schedule.setSpeakers("");
                    }
                    mScheduleDayOneAdapter.addItems(mLocalDayOneSchedules);
                }
                if (mLocalDayTwoSchedules != null) {
                    for (Schedule schedule : mLocalDayTwoSchedules) {
                        Speaker speaker = SpeakerDomain.getSpeakersOfSchedule(getActivity(), schedule.getId());
                        if (speaker!= null)
                            schedule.setSpeakers(speaker.getName());
                        else
                            schedule.setSpeakers("");
                    }
                    mScheduleDayTwoAdapter.addItems(mLocalDayTwoSchedules);
                }
            }
            dayOneSwipe.setRefreshing(false);
            dayTwoSwipe.setRefreshing(false);
        }
    }

    private class LoadSchedulesFromLocalDB extends AsyncTask<Void, Void, Void> {

        private Context context;

        private LoadSchedulesFromLocalDB(Context context) {
            this.context = context;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            // TODO loading dialog
        }

        @Override
        protected Void doInBackground(Void... params) {
            mLocalDayOneSchedules = new ArrayList<Schedule>();
            mLocalDayTwoSchedules = new ArrayList<Schedule>();
            mLocalDayOneSchedules = ScheduleDomain.getAllDayOneSchedules(context);
            mLocalDayTwoSchedules = ScheduleDomain.getAllDayTwoSchedules(context);
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            if (mLocalDayOneSchedules != null) {
                for (Schedule schedule : mLocalDayOneSchedules) {
                    Speaker speaker = SpeakerDomain.getSpeakersOfSchedule(getActivity(), schedule.getId());
                    if (speaker!= null)
                        schedule.setSpeakers(speaker.getName());
                    else
                        schedule.setSpeakers("");
                }
                mScheduleDayOneAdapter.addItems(mLocalDayOneSchedules);
            }
            if (mLocalDayTwoSchedules != null) {
                for (Schedule schedule : mLocalDayTwoSchedules) {
                    Speaker speaker = SpeakerDomain.getSpeakersOfSchedule(getActivity(), schedule.getId());
                    if (speaker!= null)
                        schedule.setSpeakers(speaker.getName());
                    else
                        schedule.setSpeakers("");
                }
                mScheduleDayTwoAdapter.addItems(mLocalDayTwoSchedules);
            }
        }
    }
}

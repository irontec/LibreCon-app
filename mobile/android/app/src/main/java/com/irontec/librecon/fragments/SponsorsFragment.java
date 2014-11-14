package com.irontec.librecon.fragments;

import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
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
import android.widget.Toast;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.irontec.librecon.DaoApplication;
import com.irontec.librecon.MainActivity;
import com.irontec.librecon.R;
import com.irontec.librecon.adapters.SponsorsAdapter;
import com.irontec.librecon.api.APILibrecon;
import com.irontec.librecon.deserializers.SponsorsDeserializer;
import com.irontec.librecon.domains.LastModifiedDomain;
import com.irontec.librecon.domains.MeDomain;
import com.irontec.librecon.domains.SponsorsDomain;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import librecon.LastModified;
import librecon.Me;
import librecon.Sponsor;

/**
 * Created by Asier Fernandez on 15/09/14.
 */
public class SponsorsFragment extends BaseFragment {

    private final static String TAG = SponsorsFragment.class.getSimpleName();

    // UI
    private ListView mListView;
    private SwipeRefreshLayout sponsorsSwipe;

    // DB
    private List<Sponsor> mLocalSponsors;
    private SponsorsAdapter mSponsorsAdapter;

    // Threads
    private LoadSponsorsFromLocalDB loadSponsorsFromLocalDB;
    private SyncSponsors syncSponsors;

    public SponsorsFragment() {
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Tracker tracker = ((DaoApplication) getActivity().getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.fragments.SponsorsFragment");
        tracker.send(new HitBuilders.AppViewBuilder().build());
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_sponsors, container, false);

        mListView = (ListView) rootView.findViewById(R.id.sponsorsList);

        sponsorsSwipe = (SwipeRefreshLayout) rootView.findViewById(R.id.swipe_sponsors);
        setupSwipes();

        Me me = MeDomain.get(getActivity());
        mSponsorsAdapter = new SponsorsAdapter(getActivity(), new ArrayList<Sponsor>());

        mListView.setAdapter(mSponsorsAdapter);
        mListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Sponsor sponsor = (Sponsor) parent.getItemAtPosition(position);
                Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(sponsor.getUrl()));
                startActivity(browserIntent);
            }
        });

        loadSponsorsFromLocalDB = new LoadSponsorsFromLocalDB(getActivity());
        loadSponsorsFromLocalDB.execute();
        syncSponsors = new SyncSponsors(getActivity());
        syncSponsors.execute();

        return rootView;
    }

    private void setupSwipes() {
        sponsorsSwipe.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                syncSponsors = new SyncSponsors(getActivity());
                syncSponsors.execute();
            }
        });
        int orange = getResources().getColor(R.color.librecon_main_orange);
        int orangeDark = getResources().getColor(R.color.librecon_main_orange_dark);
        sponsorsSwipe.setColorSchemeColors(orange, orangeDark, orange, orangeDark);
    }

    @Override
    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
        inflater.inflate(R.menu.sponsors, menu);
    }

    /*
    * AsyncTask that querys the API looking for changes and then updates the local SQLite, loads
    * the results into the adapter and notifies to update the GridViews
    */
    public class SyncSponsors extends AsyncTask<Void, Void, Boolean> {

        private Context context;

        public SyncSponsors(Context context) {
            this.context = context;
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            JSONObject response = downloadDataFromAPI();
            if (response != null) {
                parseSponsorsAndSave(response);
                mLocalSponsors = new ArrayList<Sponsor>();
                mLocalSponsors = SponsorsDomain.getAllSponsors(context);
                return true;
            }
            return false;
        }

        private JSONObject downloadDataFromAPI() {
            APILibrecon api = APILibrecon.getInstance();
            try {
                String version = getSponsorsVersion();
                String hash = getUserHash();
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.get(hash, APILibrecon.SPONSORS + APILibrecon.VERSION_PARAMETER + version);
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

        private String getSponsorsVersion() {
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified != null) {
                return lastModified.getSponsors();
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

        private void parseSponsorsAndSave(JSONObject jsonObject) {
            List<Sponsor> tempSponsorsValues = new ArrayList<Sponsor>();
            GsonBuilder gsonBuilder = new GsonBuilder();
            gsonBuilder.registerTypeAdapter(Sponsor.class, new SponsorsDeserializer());
            Gson gson = gsonBuilder.create();
            String lastModifiedValue = "";
            try {
                JSONArray jsonArray = jsonObject.getJSONObject("data").getJSONArray("sponsors");
                lastModifiedValue = jsonObject.getJSONObject("data").getString("version");
                for (int i = 0; i < jsonArray.length(); i++) {
                    JSONObject sponsorObj = (JSONObject) jsonArray.get(i);
                    Sponsor sponsor = gson.fromJson(sponsorObj.toString(), Sponsor.class);
                    tempSponsorsValues.add(sponsor);
                }
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            }
            SponsorsDomain.insertOrUpdateInTransaction(context, tempSponsorsValues);
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified == null) {
                lastModified = new LastModified();
                lastModified.setId(1l);
            }
            lastModified.setSponsors(lastModifiedValue);
            LastModifiedDomain.insertOrUpdate(context, lastModified);
        }

        @Override
        protected void onPostExecute(Boolean result) {
            super.onPostExecute(result);
            if (result) {
                Log.d(TAG, "Loaded from API - " + mLocalSponsors.size());
                if (mLocalSponsors != null) {
                    mSponsorsAdapter.addItems(mLocalSponsors);

                }
            }
            sponsorsSwipe.setRefreshing(false);
        }
    }

    private class LoadSponsorsFromLocalDB extends AsyncTask<Void, Void, Void> {

        private Context context;

        private LoadSponsorsFromLocalDB(Context context) {
            this.context = context;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            // TODO loading dialog
        }

        @Override
        protected Void doInBackground(Void... params) {
            mLocalSponsors = new ArrayList<Sponsor>();
            mLocalSponsors = SponsorsDomain.getAllSponsors(context);
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            Log.d(TAG, "Loaded from local - " + mLocalSponsors.size());
            if (mLocalSponsors != null && !mLocalSponsors.isEmpty()) {
                mSponsorsAdapter.addItems(mLocalSponsors);
            }
            // TODO remove loading dialog
        }
    }
}

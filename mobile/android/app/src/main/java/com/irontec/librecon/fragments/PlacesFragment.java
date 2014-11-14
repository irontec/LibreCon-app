package com.irontec.librecon.fragments;

import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.widget.SwipeRefreshLayout;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.TabHost;
import android.widget.TextView;
import android.widget.Toast;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.irontec.librecon.DaoApplication;
import com.irontec.librecon.MainActivity;
import com.irontec.librecon.PlaceDetailActivity;
import com.irontec.librecon.R;
import com.irontec.librecon.adapters.ExpositorsAdapter;
import com.irontec.librecon.adapters.TxokosAdapter;
import com.irontec.librecon.api.APILibrecon;
import com.irontec.librecon.deserializers.ExpositorsDeserializer;
import com.irontec.librecon.deserializers.TxokosDeserializer;
import com.irontec.librecon.domains.ExpositorsDomain;
import com.irontec.librecon.domains.LastModifiedDomain;
import com.irontec.librecon.domains.MeDomain;
import com.irontec.librecon.domains.TxokosDomain;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import librecon.Expositor;
import librecon.LastModified;
import librecon.Me;
import librecon.Txoko;

/**
 * Created by Asier Fernandez on 15/09/14.
 */
public class PlacesFragment extends BaseFragment {

    private final static String TAG = ScheduleFragment.class.getSimpleName();

    // DB
    private List<Txoko> mLocalTxokos;
    private List<Expositor> mLocalExpositors;
    private TxokosAdapter mTxokosAdapter;
    private ExpositorsAdapter mExpositorsAdapter;

    // Threads
    private LoadTxokosFromLocalDB loadTxokosFromLocalDB;
    private LoadExpositorsFromLocalDB loadExpositorsFromLocalDB;
    private SyncTxokos syncTxokos;
    private SyncExpositors syncExpositors;

    // UI
    private TabHost tabHost;
    private ListView txokosList;
    private ListView expositorsList;
    private SwipeRefreshLayout txokosSwipe;
    private SwipeRefreshLayout expositorsSwipe;

    public PlacesFragment() {
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        Tracker tracker = ((DaoApplication) getActivity().getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.fragments.PlacesFragment");
        tracker.send(new HitBuilders.AppViewBuilder().build());
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {

        View rootView = inflater.inflate(R.layout.fragment_places, container, false);

        String titleTxoko = getResources().getString(R.string.txoko);
        String titleExpositor = getResources().getString(R.string.expositor);

        // TabHost initialization
        setupTabHost(rootView);
        setupTab(new TextView(getActivity()), titleTxoko, R.id.first_content);
        setupTab(new TextView(getActivity()), titleExpositor, R.id.second_content);

        tabHost.setCurrentTab(0);

        for (int i = 0; i < tabHost.getTabWidget().getChildCount(); i++) {
            TextView tv = (TextView) tabHost.getTabWidget().getChildAt(i).findViewById(android.R.id.title);
            if (tv == null) {
                continue;
            }
            tv.setBackgroundResource(R.drawable.tab_selector_style);
        }

        txokosList = (ListView) rootView.findViewById(R.id.list_txokos);
        expositorsList = (ListView) rootView.findViewById(R.id.list_expositors);

        txokosSwipe = (SwipeRefreshLayout) rootView.findViewById(R.id.swipe_txokos);
        expositorsSwipe = (SwipeRefreshLayout) rootView.findViewById(R.id.swipe_expositors);

        setupSwipes();

        mTxokosAdapter = new TxokosAdapter(
                getActivity(),
                new ArrayList<Txoko>());
        txokosList.setAdapter(mTxokosAdapter);

        mExpositorsAdapter = new ExpositorsAdapter(
                getActivity(),
                new ArrayList<Expositor>());
        expositorsList.setAdapter(mExpositorsAdapter);

        expositorsList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Expositor expositor = (Expositor) mExpositorsAdapter.getItem(position);
                Intent intent = new Intent(getActivity(), PlaceDetailActivity.class);
                intent.putExtra("expositor", expositor);
                getActivity().startActivity(intent);
            }
        });
        txokosList.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Txoko txoko = (Txoko) mTxokosAdapter.getItem(position);
                Intent intent = new Intent(getActivity(), PlaceDetailActivity.class);
                intent.putExtra("txoko", txoko);
                getActivity().startActivity(intent);
            }
        });

        loadTxokosFromLocalDB = new LoadTxokosFromLocalDB(getActivity());
        loadTxokosFromLocalDB.execute();
        loadExpositorsFromLocalDB = new LoadExpositorsFromLocalDB(getActivity());
        loadExpositorsFromLocalDB.execute();
        syncTxokos = new SyncTxokos(getActivity());
        syncTxokos.execute();
        syncExpositors = new SyncExpositors(getActivity());
        syncExpositors.execute();

        return rootView;
    }

    private void setupTabHost(View rootView) {
        tabHost = (TabHost) rootView.findViewById(android.R.id.tabhost);
        tabHost.setup();
    }

    private void setupTab(final View view, final String tag, final int res) {
        View tabview = createTabView(tabHost.getContext(), tag);
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
        txokosSwipe.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                //TODO llamada a head para ver cambios
                syncTxokos = new SyncTxokos(getActivity());
                syncTxokos.execute();
            }
        });
        expositorsSwipe.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                //TODO llamada a head para ver cambios
                syncExpositors = new SyncExpositors(getActivity());
                syncExpositors.execute();

            }
        });
        int orange = getResources().getColor(R.color.librecon_main_orange);
        int orangeDark = getResources().getColor(R.color.librecon_main_orange_dark);
        txokosSwipe.setColorSchemeColors(orange, orangeDark, orange, orangeDark);
        expositorsSwipe.setColorSchemeColors(orange, orangeDark, orange, orangeDark);
    }

    /*
     * AsyncTask that querys the API looking for changes and then updates the local SQLite, loads
     * the results into the adapter and notifies to update the GridViews
     */
    public class SyncTxokos extends AsyncTask<Void, Void, Boolean> {

        private Context context;

        public SyncTxokos(Context context) {
            this.context = context;
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            JSONObject response = downloadDataFromAPI();
            if (response != null) {
                parseTxokosAndSave(response);
                mLocalTxokos = new ArrayList<Txoko>();
                mLocalTxokos = TxokosDomain.getAllTxokos(context);
                return true;
            }
            return false;
        }

        private JSONObject downloadDataFromAPI() {
            APILibrecon api = APILibrecon.getInstance();
            try {
                String version = getTxokosVersion();
                String hash = getUserHash();
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.get(hash, APILibrecon.TXOKOS + APILibrecon.VERSION_PARAMETER + version);
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

        private String getTxokosVersion() {
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified != null) {
                return lastModified.getTxokos();
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

        private void parseTxokosAndSave(JSONObject jsonObject) {
            List<Txoko> tempValues = new ArrayList<Txoko>();
            GsonBuilder gsonBuilder = new GsonBuilder();
            gsonBuilder.registerTypeAdapter(Txoko.class, new TxokosDeserializer());
            Gson gson = gsonBuilder.create();
            String lastModifiedValue = "";
            try {
                JSONArray jsonArray = jsonObject.getJSONObject("data").getJSONArray("txokos");
                lastModifiedValue = jsonObject.getJSONObject("data").getString("version");
                for (int i = 0; i < jsonArray.length(); i++) {
                    tempValues.add(gson.fromJson(jsonArray.get(i).toString(), Txoko.class));
                }
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            }
            TxokosDomain.insertOrUpdateInTransaction(context, tempValues);
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified == null) {
                lastModified = new LastModified();
                lastModified.setId(1l);
            }
            lastModified.setTxokos(lastModifiedValue);
            LastModifiedDomain.insertOrUpdate(context, lastModified);
        }

        @Override
        protected void onPostExecute(Boolean result) {
            super.onPostExecute(result);
            if (result) {
                if (mLocalTxokos != null) {
                    mTxokosAdapter.addItems(mLocalTxokos);
                }
            }
            txokosSwipe.setRefreshing(false);
        }
    }

    private class LoadTxokosFromLocalDB extends AsyncTask<Void, Void, Void> {

        private Context context;

        private LoadTxokosFromLocalDB(Context context) {
            this.context = context;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            // TODO loading dialog
        }

        @Override
        protected Void doInBackground(Void... params) {
            mLocalTxokos = new ArrayList<Txoko>();
            mLocalTxokos = TxokosDomain.getAllTxokos(context);
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            if (mLocalTxokos != null && !mLocalTxokos.isEmpty()) {
                mTxokosAdapter.addItems(mLocalTxokos);
            }
        }
    }

    /*
     * AsyncTask that querys the API looking for changes and then updates the local SQLite, loads
     * the results into the adapter and notifies to update the GridViews
     */
    public class SyncExpositors extends AsyncTask<Void, Void, Boolean> {

        private Context context;

        public SyncExpositors(Context context) {
            this.context = context;
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            JSONObject response = downloadDataFromAPI();
            if (response != null) {
                parseExpositorsAndSave(response);
                mLocalExpositors = new ArrayList<Expositor>();
                mLocalExpositors = ExpositorsDomain.getAllExpositors(context);
                return true;
            }
            return false;
        }

        private JSONObject downloadDataFromAPI() {
            APILibrecon api = APILibrecon.getInstance();
            try {
                String version = getExpositorsVersion();
                String hash = getUserHash();
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.get(hash, APILibrecon.EXPOSITORS + APILibrecon.VERSION_PARAMETER + version);
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

        private String getExpositorsVersion() {
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified != null) {
                return lastModified.getExpositors();
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

        private void parseExpositorsAndSave(JSONObject jsonObject) {
            List<Expositor> tempValues = new ArrayList<Expositor>();
            GsonBuilder gsonBuilder = new GsonBuilder();
            gsonBuilder.registerTypeAdapter(Expositor.class, new ExpositorsDeserializer());
            Gson gson = gsonBuilder.create();
            String lastModifiedValue = "";
            try {
                JSONArray jsonArray = jsonObject.getJSONObject("data").getJSONArray("expositors");
                lastModifiedValue = jsonObject.getJSONObject("data").getString("version");
                for (int i = 0; i < jsonArray.length(); i++) {
                    Expositor expositor = gson.fromJson(jsonArray.get(i).toString(), Expositor.class);
                    tempValues.add(expositor);
                }
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            }
            ExpositorsDomain.insertOrUpdateInTransaction(context, tempValues);
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified == null) {
                lastModified = new LastModified();
                lastModified.setId(1l);
            }
            lastModified.setExpositors(lastModifiedValue);
            LastModifiedDomain.insertOrUpdate(context, lastModified);
        }

        @Override
        protected void onPostExecute(Boolean result) {
            super.onPostExecute(result);
            if (result) {
                if (mLocalExpositors != null) {
                    mExpositorsAdapter.addItems(mLocalExpositors);
                }
            }
            expositorsSwipe.setRefreshing(false);
        }
    }

    private class LoadExpositorsFromLocalDB extends AsyncTask<Void, Void, Void> {

        private Context context;

        private LoadExpositorsFromLocalDB(Context context) {
            this.context = context;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            // TODO loading dialog
        }

        @Override
        protected Void doInBackground(Void... params) {
            mLocalExpositors = new ArrayList<Expositor>();
            mLocalExpositors = ExpositorsDomain.getAllExpositors(context);
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            if (mLocalExpositors != null && !mLocalExpositors.isEmpty()) {
                mExpositorsAdapter.addItems(mLocalExpositors);
            }
        }
    }
}

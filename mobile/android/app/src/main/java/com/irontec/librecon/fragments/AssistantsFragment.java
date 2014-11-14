package com.irontec.librecon.fragments;

import android.app.SearchManager;
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
import android.view.inputmethod.InputMethodManager;
import android.widget.AdapterView;
import android.widget.ListView;
import android.widget.SearchView;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.irontec.librecon.AssistantProfileActivity;
import com.irontec.librecon.DaoApplication;
import com.irontec.librecon.MainActivity;
import com.irontec.librecon.R;
import com.irontec.librecon.adapters.AssistantsAdapter;
import com.irontec.librecon.api.APILibrecon;
import com.irontec.librecon.deserializers.AssistantDeserializer;
import com.irontec.librecon.domains.AssistantDomain;
import com.irontec.librecon.domains.LastModifiedDomain;
import com.irontec.librecon.domains.MeDomain;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

import librecon.Assistant;
import librecon.LastModified;
import librecon.Me;

/**
 * Created by Asier Fernandez on 15/09/14.
 */
public class AssistantsFragment extends BaseFragment {

    private final static String TAG = AssistantsFragment.class.getSimpleName();

    // UI
    private ListView mListView;
    private SwipeRefreshLayout assistantsSwipe;

    // DB
    private List<Assistant> mLocalAssistants;
    private AssistantsAdapter mAssistantsAdapter;

    // Threads
    private LoadAssistantsFromLocalDB loadAssistantsFromLocalDB;
    private SyncAssistants syncAssistants;

    public AssistantsFragment() {
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setHasOptionsMenu(true);
        Tracker tracker = ((DaoApplication) getActivity().getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.fragments.AssistantsFragment");
        tracker.send(new HitBuilders.AppViewBuilder().build());
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_assistants, container, false);

        mListView = (ListView) rootView.findViewById(R.id.assistantList);

        assistantsSwipe = (SwipeRefreshLayout) rootView.findViewById(R.id.swipe_assistants);
        setupSwipes();
        mAssistantsAdapter = new AssistantsAdapter(getActivity(), new ArrayList<Assistant>());

        mListView.setAdapter(mAssistantsAdapter);
        mListView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Assistant assistant = (Assistant) parent.getItemAtPosition(position);
                Intent intent = new Intent(getActivity(), AssistantProfileActivity.class);
                intent.putExtra("assistant", assistant);
                getActivity().startActivity(intent);
            }
        });

        loadAssistantsFromLocalDB = new LoadAssistantsFromLocalDB(getActivity());
        loadAssistantsFromLocalDB.execute();
        syncAssistants = new SyncAssistants(getActivity());
        syncAssistants.execute();

        return rootView;
    }

    private void setupSwipes() {
        assistantsSwipe.setOnRefreshListener(new SwipeRefreshLayout.OnRefreshListener() {
            @Override
            public void onRefresh() {
                syncAssistants = new SyncAssistants(getActivity());
                syncAssistants.execute();
            }
        });
        int orange = getResources().getColor(R.color.librecon_main_orange);
        int orangeDark = getResources().getColor(R.color.librecon_main_orange_dark);
        assistantsSwipe.setColorSchemeColors(orange, orangeDark, orange, orangeDark);
    }

    @Override
    public void onCreateOptionsMenu(Menu menu, MenuInflater inflater) {
        inflater.inflate(R.menu.assistants, menu);
        SearchManager searchManager = (SearchManager) getActivity().getSystemService(Context.SEARCH_SERVICE);
        final SearchView searchView = (SearchView) menu.findItem(R.id.search).getActionView();
        if (null != searchView) {
            searchView.setSearchableInfo(searchManager.getSearchableInfo(getActivity().getComponentName()));
            searchView.setIconifiedByDefault(false);
        }

        SearchView.OnQueryTextListener queryTextListener = new SearchView.OnQueryTextListener() {
            public boolean onQueryTextChange(String newText) {
                if (mAssistantsAdapter != null) {
                    mAssistantsAdapter.getFilter().filter(newText.toString());
                }
                return true;
            }

            @Override
            public boolean onQueryTextSubmit(String query) {
                return false;
            }
        };
        searchView.setOnQueryTextListener(queryTextListener);
        searchView.setOnCloseListener(new SearchView.OnCloseListener() {
            @Override
            public boolean onClose() {
                InputMethodManager imm = (InputMethodManager) getActivity().getSystemService(
                        Context.INPUT_METHOD_SERVICE);
                imm.hideSoftInputFromWindow(searchView.getWindowToken(), 0);

                return false;
            }
        });
    }

    /*
    * AsyncTask that querys the API looking for changes and then updates the local SQLite, loads
    * the results into the adapter and notifies to update the GridViews
    */
    public class SyncAssistants extends AsyncTask<Void, Void, Boolean> {

        private Context context;

        public SyncAssistants(Context context) {
            this.context = context;
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            JSONObject response = downloadDataFromAPI();
            if (response != null) {
                parseAssistantsAndSave(response);
                mLocalAssistants = new ArrayList<Assistant>();
                mLocalAssistants = AssistantDomain.getAllAssistants(context);
                return true;
            }
            return false;
        }

        private JSONObject downloadDataFromAPI() {
            APILibrecon api = APILibrecon.getInstance();
            try {
                String version = getAssistantsVersion();
                String hash = getUserHash();
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.get(hash, APILibrecon.ASSISTANTS + APILibrecon.VERSION_PARAMETER + version);
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

        private String getAssistantsVersion() {
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified != null) {
                return lastModified.getAssistants();
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

        private void parseAssistantsAndSave(JSONObject jsonObject) {
            List<Assistant> tempValues = new ArrayList<Assistant>();
            GsonBuilder gsonBuilder = new GsonBuilder();
            gsonBuilder.registerTypeAdapter(Assistant.class, new AssistantDeserializer());
            Gson gson = gsonBuilder.create();
            String lastModifiedValue = "";
            try {
                JSONArray jsonArray = jsonObject.getJSONObject("data").getJSONArray("assistants");
                lastModifiedValue = jsonObject.getJSONObject("data").getString("version");
                for (int i = 0; i < jsonArray.length(); i++) {
                    Assistant assistant = gson.fromJson(jsonArray.get(i).toString(), Assistant.class);
                    tempValues.add(assistant);
                }
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            }
            AssistantDomain.insertDeleteOrUpdateInTransaction(context, tempValues);
            LastModified lastModified = LastModifiedDomain.getLastModifiedForId(context, 1l);
            if (lastModified == null) {
                lastModified = new LastModified();
                lastModified.setId(1l);
            }
            lastModified.setAssistants(lastModifiedValue);
            LastModifiedDomain.insertOrUpdate(context, lastModified);
        }

        @Override
        protected void onPostExecute(Boolean result) {
            super.onPostExecute(result);
            if (result) {
                Log.d(TAG, "Loaded from API - " + mLocalAssistants.size());
                if (mLocalAssistants != null && !mLocalAssistants.isEmpty()) {
                    mAssistantsAdapter.addItems(mLocalAssistants);
                }
            }
            assistantsSwipe.setRefreshing(false);
        }
    }

    private class LoadAssistantsFromLocalDB extends AsyncTask<Void, Void, Void> {

        private Context context;

        private LoadAssistantsFromLocalDB(Context context) {
            this.context = context;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            // TODO loading dialog
        }

        @Override
        protected Void doInBackground(Void... params) {
            mLocalAssistants = new ArrayList<Assistant>();
            mLocalAssistants = AssistantDomain.getAllAssistants(context);
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            Log.d(TAG, "Loaded from local - " + mLocalAssistants.size());
            if (mLocalAssistants != null && !mLocalAssistants.isEmpty())
                mAssistantsAdapter.addItems(mLocalAssistants);
            // TODO remove loading dialog
        }
    }

}

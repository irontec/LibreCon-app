package com.irontec.librecon;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.os.Bundle;
import android.text.Html;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.SimpleAdapter;
import android.widget.TextView;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.irontec.librecon.utils.DateUtils;
import com.manuelpeinado.fadingactionbar.FadingActionBarHelper;
import com.squareup.picasso.Picasso;

import org.json.JSONException;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import librecon.Expositor;
import librecon.Txoko;


public class PlaceDetailActivity extends Activity {

    private Txoko mTxoko;
    private Expositor mExpositor;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getActionBar().setDisplayHomeAsUpEnabled(true);
        setContentView(R.layout.activity_place_detail);

        Tracker tracker = ((DaoApplication) getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.PlaceDetailActivity");
        tracker.send(new HitBuilders.AppViewBuilder().build());

        Intent intent = getIntent();
        if (intent != null) {
            mTxoko = intent.getExtras().getParcelable("txoko");
            mExpositor = intent.getExtras().getParcelable("expositor");
        }

        FadingActionBarHelper helper = new FadingActionBarHelper()
                .actionBarBackground(R.drawable.ab_solid_librecon)
                .headerLayout(R.layout.layout_header)
                .contentLayout(R.layout.activity_schedule_detail);
        View view = helper.createView(this);
        setContentView(view);
        helper.initActionBar(this);

        ListView listView = (ListView) findViewById(android.R.id.list);

        // Header View
        View headerView = getLayoutInflater().inflate(R.layout.header_places_detail_list, null);
        TextView name = (TextView) headerView.findViewById(R.id.place_name);
        TextView description = (TextView) headerView.findViewById(R.id.place_description);

        ImageView image = (ImageView) view.findViewById(R.id.image_header);
        if (mTxoko != null && mTxoko.getPicUrl() != null && !mTxoko.getPicUrl().isEmpty()) {
            Picasso.with(this).
                    load(mTxoko.getPicUrl())
                    .placeholder(R.drawable.placeholder_schedule)
                    .error(R.drawable.placeholder_schedule)
                    .fit().centerCrop()
                    .into(image);
            name.setText(mTxoko.getTitle());
            description.setText(mTxoko.getText());
        } else if (mExpositor != null && mExpositor.getPicUrl() != null && !mExpositor.getPicUrl().isEmpty()) {
            Picasso.with(this).
                    load(mExpositor.getPicUrl())
                    .placeholder(R.drawable.placeholder_schedule)
                    .error(R.drawable.placeholder_schedule)
                    .fit().centerCrop()
                    .into(image);
            name.setText(mExpositor.getCompany());
            description.setText(mExpositor.getDescription());
        }

        listView.addHeaderView(headerView);
        final StableArrayAdapter adapter = new StableArrayAdapter(this,
                android.R.layout.simple_list_item_1, new ArrayList<String>());
        listView.setAdapter(adapter);
    }

    private class StableArrayAdapter extends ArrayAdapter<String> {

        HashMap<String, Integer> mIdMap = new HashMap<String, Integer>();

        public StableArrayAdapter(Context context, int textViewResourceId,
                                  List<String> objects) {
            super(context, textViewResourceId, objects);
            for (int i = 0; i < objects.size(); ++i) {
                mIdMap.put(objects.get(i), i);
            }
        }

        @Override
        public long getItemId(int position) {
            String item = getItem(position);
            return mIdMap.get(item);
        }

        @Override
        public boolean hasStableIds() {
            return true;
        }

    }


    @Override
    public boolean onCreateOptionsMenu(Menu menu) {
        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.place_detail, menu);
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
            return true;
        }
        return super.onOptionsItemSelected(item);
    }
}

package com.irontec.librecon;

import android.app.Activity;
import android.content.Context;
import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.text.Html;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ListView;
import android.widget.TextView;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.irontec.librecon.adapters.SpeakersAdapter;
import com.irontec.librecon.domains.ScheduleSpeakerDomain;
import com.irontec.librecon.domains.SpeakerDomain;
import com.irontec.librecon.utils.DateUtils;
import com.manuelpeinado.fadingactionbar.FadingActionBarHelper;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.List;

import librecon.Schedule;
import librecon.ScheduleSpeaker;
import librecon.Speaker;

public class ScheduleDetailActivity extends Activity {

    private final static String TAG = ScheduleDetailActivity.class.getSimpleName();

    private Schedule mSchedule;
    private List<Speaker> mSpeakers;
    private SpeakersAdapter mSpeakersAdapter;
    private LayoutInflater mLayoutInflater;

    // Threads
    private LoadSpeakers mLoadSpeakers;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        Tracker tracker = ((DaoApplication) getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.ScheduleDetailActivity");
        tracker.send(new HitBuilders.AppViewBuilder().build());

        getActionBar().setDisplayHomeAsUpEnabled(true);
        setContentView(R.layout.activity_schedule_detail);

        mLayoutInflater = (LayoutInflater) getSystemService(LAYOUT_INFLATER_SERVICE);

        Intent intent = getIntent();
        if (intent != null) {
            mSchedule = intent.getExtras().getParcelable("schedule");
        }

        FadingActionBarHelper helper = new FadingActionBarHelper()
                .actionBarBackground(R.drawable.ab_solid_librecon)
                .headerLayout(R.layout.layout_header)
                .contentLayout(R.layout.activity_schedule_detail);
        View view = helper.createView(this);
        setContentView(view);
        helper.initActionBar(this);

        ListView listView = (ListView) findViewById(android.R.id.list);

        ImageView image = (ImageView) view.findViewById(R.id.image_header);
        if (mSchedule != null && mSchedule.getPicUrl() != null && !mSchedule.getPicUrl().isEmpty()) {
            Picasso.with(this).
                    load(mSchedule.getPicUrl())
                    .placeholder(R.drawable.placeholder_schedule)
                    .error(R.drawable.placeholder_schedule)
                    .fit().centerCrop()
                    .into(image);
        } else {
            Picasso.with(this)
                    .load(R.drawable.placeholder_schedule)
                    .fit().centerCrop()
                    .into(image);
        }
        // Header View
        View headerView = getLayoutInflater().inflate(R.layout.header_speakers_list, null);
        TextView description = (TextView) headerView.findViewById(R.id.description);
        TextView name = (TextView) headerView.findViewById(R.id.name);
        TextView whenAndWhere = (TextView) headerView.findViewById(R.id.whenAndWhere);

        name.setText(mSchedule.getName());
        whenAndWhere.setText(mSchedule.getLocation() + ": " + DateUtils.getHourFromDate(mSchedule.getStartDatetime()) + " - " + DateUtils.getHourFromDate(mSchedule.getFinishDatetime()));
        description.setText(Html.fromHtml(mSchedule.getDescription()));

        // Footer View
        View footerView = getLayoutInflater().inflate(R.layout.footer_speakers_list, null);
        LinearLayout linksLayout = (LinearLayout) footerView.findViewById(R.id.linksLayout);
        LinearLayout speakerTags = (LinearLayout) footerView.findViewById(R.id.tagsLayout);
        try {
            generateLinksTv(linksLayout, mSchedule);
            generateTagsTv(speakerTags, mSchedule);
        } catch (JSONException jsEx) {
            jsEx.printStackTrace();
        }

        listView.addHeaderView(headerView);
        listView.addFooterView(footerView);

        mSpeakersAdapter = new SpeakersAdapter(this, new ArrayList<Speaker>());
        listView.setAdapter(mSpeakersAdapter);
        listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
            @Override
            public void onItemClick(AdapterView<?> parent, View view, int position, long id) {
                Speaker speaker = (Speaker) parent.getItemAtPosition(position);
                if (speaker != null) {
                    Intent intent = new Intent(ScheduleDetailActivity.this, SpeakerProfileActivity.class);
                    intent.putExtra("speaker", speaker);
                    startActivity(intent);
                }
            }
        });

        mLoadSpeakers = new LoadSpeakers(this);
        mLoadSpeakers.execute();
    }

    private void generateLinksTv(LinearLayout layout, Schedule schedule) throws JSONException {
        if (schedule.getLinks() != null) {
            JSONArray linksArray = new JSONArray(schedule.getLinks());
            for (int i = 0; i < linksArray.length(); i++) {
                JSONObject linkObj = linksArray.getJSONObject(i);
                TextView linkTv = new TextView(this);
                LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(
                        LinearLayout.LayoutParams.MATCH_PARENT,
                        LinearLayout.LayoutParams.WRAP_CONTENT, 0f);
                params.setMargins(0, 20, 0, 0);
                linkTv.setLayoutParams(params);

                String type = linkObj.getString("type");

                linkTv.setTag(linkObj.getString("url"));
                linkTv.setText(type);
                int drawable = 0;
                if (type.equalsIgnoreCase("github")) {
                    drawable = R.drawable.ic_github;
                } else if (type.equalsIgnoreCase("youtube")) {
                    drawable = R.drawable.ic_youtube;
                } else if (type.equalsIgnoreCase("slideshare")) {
                    drawable = R.drawable.ic_slide;
                } else if (type.equalsIgnoreCase("twitter")) {
                    drawable = R.drawable.ic_social;
                } else if (type.equalsIgnoreCase("facebook")) {
                    drawable = R.drawable.ic_facebook;
                } else if (type.equalsIgnoreCase("linkedin")) {
                    drawable = R.drawable.ic_linkedin;
                } else {
                    drawable = R.drawable.ic_info;
                }
                linkTv.setGravity(Gravity.CENTER_VERTICAL);
                linkTv.setCompoundDrawablesWithIntrinsicBounds(drawable, 0, 0, 0);
                linkTv.setOnClickListener(new View.OnClickListener() {
                    @Override
                    public void onClick(View v) {
                        String url = (String) v.getTag();
                        Intent browserIntent = new Intent(Intent.ACTION_VIEW, Uri.parse(url));
                        startActivity(browserIntent);
                    }
                });
                layout.addView(linkTv);
            }
        }
    }

    private void generateTagsTv(LinearLayout layout, Schedule schedule) throws JSONException {
        if (schedule.getTags() != null) {
            JSONArray tagsArray = new JSONArray(schedule.getTags());
            for (int i = 0; i < tagsArray.length(); i++) {
                JSONObject linkObj = tagsArray.getJSONObject(i);
                View tagView = mLayoutInflater.inflate(R.layout.view_tag, null);
                TextView tagName = (TextView) tagView.findViewById(R.id.tag_name);
                ImageView tagColor = (ImageView) tagView.findViewById(R.id.tag_color);
                tagName.setText(linkObj.getString("name_es"));
                if (!linkObj.getString("color").isEmpty()) {
                    tagColor.setBackgroundColor(Color.parseColor("#" + linkObj.getString("color")));
                } else {
                    tagColor.setBackgroundColor(Color.parseColor("#000000"));
                }
                layout.addView(tagView);
            }
        }
    }

    private class LoadSpeakers extends AsyncTask<Void, Void, Void> {

        private Context context;

        private LoadSpeakers(Context context) {
            this.context = context;
        }

        @Override
        protected Void doInBackground(Void... params) {
            mSpeakers = new ArrayList<Speaker>();
            List<ScheduleSpeaker> scheduleSpeakers = ScheduleSpeakerDomain.getAllScheduleSpeakersWithScheduleId(context, mSchedule.getId());
            for (ScheduleSpeaker scheduleSpeaker : scheduleSpeakers) {
                mSpeakers.add(SpeakerDomain.getSpeakerForId(context, scheduleSpeaker.getSpeakerId()));
            }
            return null;
        }

        @Override
        protected void onPostExecute(Void aVoid) {
            super.onPostExecute(aVoid);
            if (mSpeakers != null && !mSpeakers.isEmpty())
                mSpeakersAdapter.addItems(mSpeakers);
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

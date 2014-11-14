package com.irontec.librecon;

import android.app.Activity;
import android.content.Intent;
import android.graphics.Color;
import android.net.Uri;
import android.os.Bundle;
import android.text.Html;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.google.android.gms.analytics.HitBuilders;
import com.google.android.gms.analytics.Tracker;
import com.squareup.picasso.Picasso;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.Locale;

import librecon.Speaker;

public class SpeakerProfileActivity extends Activity {

    private LayoutInflater mLayoutInflater;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        Tracker tracker = ((DaoApplication) getApplication()).getTracker(
                DaoApplication.TrackerName.APP_TRACKER);
        tracker.setScreenName("com.irontec.librecon.SpeakerProfileActivity");
        tracker.send(new HitBuilders.AppViewBuilder().build());

        getActionBar().setDisplayHomeAsUpEnabled(true);
        setContentView(R.layout.activity_profile);

        mLayoutInflater = (LayoutInflater) getSystemService(LAYOUT_INFLATER_SERVICE);

        Intent intent = getIntent();
        Speaker speaker = null;
        if (intent != null) {
            speaker = intent.getExtras().getParcelable("speaker");
        }

        ImageView speakerPicture = (ImageView) findViewById(R.id.assistant_picture);
        TextView speakerName = (TextView) findViewById(R.id.assistant_name);
        TextView speakerCompany = (TextView) findViewById(R.id.assistant_company);
        TextView speakerPosition = (TextView) findViewById(R.id.assistant_position);
        TextView speakerDescription = (TextView) findViewById(R.id.assistant_description);
        LinearLayout speakerLinks = (LinearLayout) findViewById(R.id.linksLayout);
        LinearLayout speakerTags = (LinearLayout) findViewById(R.id.tagsLayout);

        if (speaker != null && speaker.getPicUrl() != null && !speaker.getPicUrl().isEmpty()) {
            Picasso.with(this)
                    .load(speaker.getPicUrlCircle())
                    .placeholder(R.drawable.user_placeholder)
                    .error(R.drawable.user_placeholder)
                    .into(speakerPicture);
        } else {
            Picasso.with(this).load(R.drawable.user_placeholder).into(speakerPicture);
        }
        speakerName.setText(speaker.getName());
        speakerCompany.setText(speaker.getCompany());
        speakerPosition.setText(speaker.getPosition());
        speakerDescription.setText(Html.fromHtml(speaker.getDescription()));
        try {
            generateLinksTv(speakerLinks, speaker);
            generateTagsTv(speakerTags, speaker);
        } catch (JSONException jsEx) {
            jsEx.printStackTrace();
        }

    }

    private void generateLinksTv(LinearLayout layout, Speaker speaker) throws JSONException {
        if (speaker.getLinks() != null) {
            JSONArray linksArray = new JSONArray(speaker.getLinks());
            for (int i = 0; i < linksArray.length(); i++) {
                JSONObject linkObj = linksArray.getJSONObject(i);
                TextView linkTv = new TextView(this);
                LinearLayout.LayoutParams params = new LinearLayout.LayoutParams(
                        LinearLayout.LayoutParams.MATCH_PARENT,
                        LinearLayout.LayoutParams.WRAP_CONTENT, 0f);
                params.setMargins(0, 20, 0, 0);
                linkTv.setLayoutParams(params);
                linkTv.setTag(linkObj.getString("url"));
                String type = linkObj.getString("type");
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

    private void generateTagsTv(LinearLayout layout, Speaker speaker) throws JSONException {
        if (speaker.getTags() != null) {
            JSONArray tagsArray = new JSONArray(speaker.getTags());
            Locale locale = Locale.getDefault();
            String language = locale.getLanguage();
            for (int i = 0; i < tagsArray.length(); i++) {
                JSONObject linkObj = tagsArray.getJSONObject(i);
                View tagView = mLayoutInflater.inflate(R.layout.view_tag, null);
                TextView tagName = (TextView) tagView.findViewById(R.id.tag_name);
                ImageView tagColor = (ImageView) tagView.findViewById(R.id.tag_color);
                if (language.equals("eu"))
                    tagName.setText(linkObj.getString("name_eu"));
                else if (language.equals("en"))
                    tagName.setText(linkObj.getString("name_en"));
                else
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

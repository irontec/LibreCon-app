package com.irontec.librecon.adapters;

import android.content.Context;
import android.graphics.Color;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.ImageView;
import android.widget.TextView;

import com.irontec.librecon.R;
import com.irontec.librecon.domains.ScheduleSpeakerDomain;
import com.irontec.librecon.domains.SpeakerDomain;
import com.irontec.librecon.utils.DateUtils;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;
import java.util.List;

import librecon.Schedule;
import librecon.ScheduleSpeaker;
import librecon.Speaker;
import librecon.SpeakerDao;

/**
 * Created by Asier Fernandez on 16/09/14.
 */
public class ScheduleAdapter extends BaseAdapter implements Filterable {

    private final static String TAG = ScheduleAdapter.class.getSimpleName();

    private final static String TRANSPARENCY = "#BF";

    private Context mContext;
    private LayoutInflater layoutInflater;
    private List<Schedule> mData;
    private List<Schedule> mOriginalValues;

    public ScheduleAdapter(Context context, List<Schedule> data) {
        this.mContext = context;
        this.layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mData = data;
        mOriginalValues = data;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        ViewHolder viewHolder;
        if (convertView == null) {
            convertView = layoutInflater.inflate(R.layout.row_schedule, parent, false);

            viewHolder = new ViewHolder();
            viewHolder.title = (TextView) convertView.findViewById(R.id.schedule_item_name);
            viewHolder.speaker = (TextView) convertView.findViewById(R.id.schedule_item_speaker);
            viewHolder.image = (ImageView) convertView.findViewById(R.id.schedule_item_picture);
            viewHolder.place = (TextView) convertView.findViewById(R.id.schedule_item_place);
            viewHolder.time = (TextView) convertView.findViewById(R.id.schedule_item_time);
            viewHolder.cover = (View) convertView.findViewById(R.id.schedule_item_cover);

            convertView.setTag(viewHolder);
        } else {
            viewHolder = (ViewHolder) convertView.getTag();
        }

        Schedule schedule = mData.get(position);

        if (schedule != null) {
            if (schedule.getPicUrlSquare() != null && !schedule.getPicUrlSquare().isEmpty()) {
                Picasso.with(mContext)
                        .load(schedule.getPicUrlSquare())
                        .placeholder(R.drawable.placeholder_square)
                        .error(R.drawable.placeholder_square)
                        .centerCrop()
                        .fit()
                        .into(viewHolder.image);
            } else {
                Picasso.with(mContext)
                        .load(R.drawable.placeholder_square)
                        .centerCrop()
                        .fit()
                        .into(viewHolder.image);
            }
            viewHolder.title.setText(schedule.getName());
            viewHolder.place.setText(schedule.getLocation());
            if (schedule.getTargetDate().equals("1")) {
                viewHolder.time.setText(
                        mContext.getString(
                                R.string.schedule_day_one,
                                DateUtils.getHourFromDate(schedule.getStartDatetime()),
                                DateUtils.getHourFromDate(schedule.getFinishDatetime())));
            } else {
                viewHolder.time.setText(
                        mContext.getString(
                                R.string.schedule_day_two,
                                DateUtils.getHourFromDate(schedule.getStartDatetime()),
                                DateUtils.getHourFromDate(schedule.getFinishDatetime())));
            }
            if (!schedule.getColor().isEmpty()) {
                viewHolder.cover.setBackgroundColor(Color.parseColor(TRANSPARENCY + schedule.getColor()));
            } else {
                viewHolder.cover.setBackgroundColor(Color.parseColor(TRANSPARENCY + "000000"));
            }

            List<ScheduleSpeaker> scheduleSpeakers = ScheduleSpeakerDomain.getAllScheduleSpeakersWithScheduleId(mContext, schedule.getId());
            if (!scheduleSpeakers.isEmpty()) {
                Speaker speaker = SpeakerDomain.getSpeakerForId(mContext, scheduleSpeakers.get(0).getSpeakerId());
                viewHolder.speaker.setText(speaker.getName());
            }

        }
        return convertView;
    }

    @Override
    public int getCount() {
        return mData.size();
    }

    @Override
    public Object getItem(int position) {
        return mData.get(position);
    }

    @Override
    public long getItemId(int position) {
        return mData.get(position).getId();
    }

    public void addItems(List<Schedule> items) {
        mData.clear();
        mData.addAll(items);
        notifyDataSetChanged();
    }

    static class ViewHolder {
        TextView title;
        ImageView image;
        TextView place;
        TextView speaker;
        TextView time;
        View cover;
    }

    @Override
    public Filter getFilter() {
        Filter filter = new Filter() {

            @SuppressWarnings("unchecked")
            @Override
            protected void publishResults(CharSequence constraint, FilterResults results) {

                mData = (List<Schedule>) results.values;
                notifyDataSetChanged();
            }

            @Override
            protected FilterResults performFiltering(CharSequence constraint) {
                FilterResults results = new FilterResults();        // Holds the results of a filtering operation in values
                List<Schedule> FilteredArrList = new ArrayList<Schedule>();

                // If constraint(CharSequence that is received) is null returns the mOriginalValues(Original) values
                // else does the Filtering and returns FilteredArrList(Filtered)
                if (constraint == null || constraint.length() == 0) {

                    // set the Original result to return
                    results.count = mOriginalValues.size();
                    results.values = mOriginalValues;
                } else {
                    constraint = constraint.toString().toLowerCase();
                    for (int i = 0; i < mOriginalValues.size(); i++) {
                        Schedule data = mOriginalValues.get(i);
                        if (data.getName().toLowerCase().contains(constraint.toString())
                                || data.getDescription().toLowerCase().contains(constraint.toString())
                                || data.getLocation().toLowerCase().contains(constraint.toString())
                                || data.getTags().toLowerCase().contains(constraint.toString())
                                || data.getSpeakers().toLowerCase().contains(constraint.toString())) {
                            FilteredArrList.add(data);
                        }
                    }
                    // set the Filtered result to return
                    results.count = FilteredArrList.size();
                    results.values = FilteredArrList;
                }
                return results;
            }
        };

        return filter;
    }

}
package com.irontec.librecon.adapters;

import android.content.Context;
import android.text.Html;
import android.text.Spanned;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.irontec.librecon.R;
import com.irontec.librecon.domains.AssistantDomain;
import com.irontec.librecon.domains.AssistantMeetingDomain;
import com.irontec.librecon.utils.DateUtils;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;
import java.util.List;

import librecon.Assistant;
import librecon.AssistantMeeting;
import librecon.Me;
import librecon.Meeting;

/**
 * Created by Asier Fernandez on 16/09/14.
 */
public class MeetingsAdapter extends BaseAdapter {

    private final static String TAG = MeetingsAdapter.class.getSimpleName();
    private Context mContext;
    private LayoutInflater layoutInflater;
    private List<Meeting> mData;
    private List<Assistant> mAssistants = new ArrayList<Assistant>();
    private Me mMe;

    public MeetingsAdapter(Context context, List<Meeting> data, Me me) {
        this.mContext = context;
        this.layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mData = data;
        this.mMe = me;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        ViewHolder viewHolder;
        if (convertView == null) {
            convertView = layoutInflater.inflate(R.layout.row_meeting, parent, false);

            viewHolder = new ViewHolder();
            viewHolder.from = (TextView) convertView.findViewById(R.id.meeting_from);
            viewHolder.to = (TextView) convertView.findViewById(R.id.meeting_to);
            viewHolder.picture = (ImageView) convertView.findViewById(R.id.assistant_picture);
            viewHolder.moment = (TextView) convertView.findViewById(R.id.meeting_moment);
            viewHolder.info = (TextView) convertView.findViewById(R.id.meeting_info);
            viewHolder.status = (TextView) convertView.findViewById(R.id.meeting_status);
            viewHolder.date = (TextView) convertView.findViewById(R.id.meeting_date);

            convertView.setTag(viewHolder);
        } else {
            viewHolder = (ViewHolder) convertView.getTag();
        }

        Meeting meeting = mData.get(position);
        Assistant assistant = mAssistants.get(position);


        if (meeting != null && assistant != null) {
            if (!assistant.getPicUrlCircle().isEmpty()) {
                Picasso.with(mContext)
                        .load(assistant.getPicUrlCircle())
                        .placeholder(R.drawable.user_placeholder)
                        .error(R.drawable.user_placeholder)
                        .centerCrop()
                        .fit()
                        .into(viewHolder.picture);
            } else {
                Picasso.with(mContext)
                        .load(R.drawable.user_placeholder)
                        .centerCrop()
                        .fit()
                        .into(viewHolder.picture);
            }
            String from = "";
            String to = "";
            if (meeting.getSendedByMe()) {
                from = mContext.getString(R.string.from_you);
                to = mContext.getString(R.string.to,
                        assistant.getName() + " " + assistant.getLastName());
            } else {
                from = mContext.getString(R.string.from,
                        assistant.getName() + " " + assistant.getLastName());
            }
            viewHolder.from.setText(from);

            if (to.isEmpty()) {
                viewHolder.to.setVisibility(View.GONE);
            }
            viewHolder.to.setText(to);

            String moment = constructMomentText(meeting);
            if (moment.isEmpty()) {
                viewHolder.moment.setVisibility(View.GONE);
            }
            viewHolder.moment.setText(moment);

            String info = constructInfoText(meeting);
            info = "";
            if (info.isEmpty()) {
                viewHolder.info.setVisibility(View.GONE);
            }
            viewHolder.info.setText(mContext.getString(R.string.shared_info, info));

            viewHolder.status.setText(getColoredStatusFromMeeting(meeting));

            String date;
            if (!meeting.getResponseDate().isEmpty()) {
                date = mContext.getString(
                        R.string.responded_at, DateUtils.getPrettyDate(meeting.getResponseDate()));
            } else {
                date = mContext.getString(
                        R.string.created_at, DateUtils.getPrettyDate(meeting.getCreatedAt()));
            }
            viewHolder.date.setText(date);
        }
        return convertView;
    }

    private String constructMomentText(Meeting meeting) {
        String moment = meeting.getMoment();
        if (moment.equals("atRightNow")) {
            return mContext.getString(R.string.now);
        } else if (moment.equals("atHalfHour")) {
            return mContext.getString(R.string.half);
        } else if (moment.equals("atOneHour")) {
            return mContext.getString(R.string.hour);
        }
        return "";
    }

    private String constructInfoText(Meeting meeting) {
        String text = "";
        String email = mContext.getResources().getString(R.string.meeting_email);
        String phone = mContext.getResources().getString(R.string.meeting_phone);
        if (meeting.getCellphoneShare())
            text += " " + phone;
        if (meeting.getEmailShare() && !text.isEmpty()) {
            text += ", " + email;
        } else if (meeting.getEmailShare()) {
            text += " " + email;
        }
        return text;
    }

    private Spanned getColoredStatusFromMeeting(Meeting meeting) {
        if (meeting.getStatus() == 1) {
            return Html.fromHtml(mContext.getResources().getString(R.string.meeting_pending_status));
        } else if (meeting.getStatus() == 2) {
            return Html.fromHtml(mContext.getResources().getString(R.string.meeting_accepted_status));
        } else {
            return Html.fromHtml(mContext.getResources().getString(R.string.meeting_canceled_status));
        }
    }

    @Override
    public int getCount() {
        return mData.size();
    }

    @Override
    public Meeting getItem(int position) {
        return mData.get(position);
    }

    @Override
    public long getItemId(int position) {
        return mData.get(position).getId();
    }

    public void addItems(List<Meeting> items, List<Assistant> assistants) {
        mData.clear();
        mData.addAll(items);
        mAssistants.clear();
        mAssistants.addAll(assistants);
        notifyDataSetChanged();
    }

    static class ViewHolder {
        TextView from;
        TextView to;
        ImageView picture;
        TextView moment;
        TextView info;
        TextView status;
        TextView date;
    }
}
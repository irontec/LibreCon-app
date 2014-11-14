package com.irontec.librecon.adapters;

import android.content.Context;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.irontec.librecon.R;
import com.squareup.picasso.Picasso;

import java.util.List;

import librecon.Speaker;

/**
 * Created by Asier Fernandez on 16/09/14.
 */
public class SpeakersAdapter extends BaseAdapter {

    private final static String TAG = SpeakersAdapter.class.getSimpleName();
    private Context mContext;
    private LayoutInflater layoutInflater;
    private List<Speaker> mData;

    public SpeakersAdapter(Context context, List<Speaker> data) {
        this.mContext = context;
        this.layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mData = data;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        ViewHolder viewHolder;
        if (convertView == null) {
            convertView = layoutInflater.inflate(R.layout.row_speaker, parent, false);

            viewHolder = new ViewHolder();
            viewHolder.name = (TextView) convertView.findViewById(R.id.speaker_name);
            viewHolder.image = (ImageView) convertView.findViewById(R.id.speaker_picture);
            viewHolder.company = (TextView) convertView.findViewById(R.id.speaker_company);
            viewHolder.description = (TextView) convertView.findViewById(R.id.speaker_description);

            convertView.setTag(viewHolder);
        } else {
            viewHolder = (ViewHolder) convertView.getTag();
        }

        Speaker speaker = mData.get(position);

        if (speaker != null) {
            if (!speaker.getPicUrlCircle().isEmpty()) {
                Picasso.with(mContext)
                        .load(speaker.getPicUrlCircle())
                        .centerCrop()
                        .fit()
                        .into(viewHolder.image);
            } else {
                Picasso.with(mContext)
                        .load(R.drawable.user_placeholder)
                        .centerCrop()
                        .fit()
                        .into(viewHolder.image);
            }
            viewHolder.name.setText(speaker.getName());
            viewHolder.company.setText(speaker.getCompany());
            viewHolder.description.setText(Html.fromHtml(speaker.getDescription()));
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

    public void addItems(List<Speaker> items) {
        mData.clear();
        mData.addAll(items);
        notifyDataSetChanged();
    }

    static class ViewHolder {
        TextView name;
        TextView company;
        ImageView image;
        TextView description;
    }

}

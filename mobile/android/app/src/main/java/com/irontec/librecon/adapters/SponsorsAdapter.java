package com.irontec.librecon.adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.irontec.librecon.R;
import com.squareup.picasso.Picasso;

import java.util.List;

import librecon.Sponsor;

/**
 * Created by Asier Fernandez on 16/09/14.
 */
public class SponsorsAdapter extends BaseAdapter {

    private final static String TAG = SponsorsAdapter.class.getSimpleName();
    private Context mContext;
    private LayoutInflater layoutInflater;
    private List<Sponsor> mData;

    public SponsorsAdapter(Context context, List<Sponsor> data) {
        this.mContext = context;
        this.layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mData = data;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        ViewHolder viewHolder;
        if (convertView == null) {
            convertView = layoutInflater.inflate(R.layout.row_sponsor, parent, false);

            viewHolder = new ViewHolder();
            viewHolder.picture = (ImageView) convertView.findViewById(R.id.sponsor_picture);
            viewHolder.name = (TextView) convertView.findViewById(R.id.sponsor_name);
            viewHolder.url = (TextView) convertView.findViewById(R.id.sponsor_url);

            convertView.setTag(viewHolder);
        } else {
            viewHolder = (ViewHolder) convertView.getTag();
        }

        Sponsor sponsor = mData.get(position);

        if (sponsor.getPicUrl() != null && !sponsor.getPicUrl().isEmpty()) {
            Picasso.with(mContext).load(sponsor.getPicUrl())
                    .placeholder(R.drawable.placeholder_large)
                    .error(R.drawable.placeholder_large)
                    .into(viewHolder.picture);
        } else {
            Picasso.with(mContext)
                    .load(R.drawable.placeholder_large)
                    .into(viewHolder.picture);
        }

        viewHolder.name.setText(sponsor.getName());
        viewHolder.url.setText(sponsor.getUrl());

        return convertView;
    }

    @Override
    public int getCount() {
        return mData.size();
    }

    @Override
    public Sponsor getItem(int position) {
        return mData.get(position);
    }

    @Override
    public long getItemId(int position) {
        return mData.get(position).getId();
    }

    public void addItems(List<Sponsor> items) {
        mData.clear();
        mData.addAll(items);
        notifyDataSetChanged();
    }

    static class ViewHolder {
        ImageView picture;
        TextView name;
        TextView url;
    }
}
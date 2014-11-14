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

import librecon.Expositor;

/**
 * Created by Asier Fernandez on 16/09/14.
 */
public class ExpositorsAdapter extends BaseAdapter {

    private final static String TAG = ExpositorsAdapter.class.getSimpleName();
    private Context mContext;
    private LayoutInflater layoutInflater;
    private List<Expositor> mData;

    public ExpositorsAdapter(Context context, List<Expositor> data) {
        this.mContext = context;
        this.layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mData = data;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        ViewHolder viewHolder;
        if (convertView == null) {
            convertView = layoutInflater.inflate(R.layout.row_expositor, parent, false);

            viewHolder = new ViewHolder();
            viewHolder.picture = (ImageView) convertView.findViewById(R.id.expositor_picture);
            viewHolder.name = (TextView) convertView.findViewById(R.id.expositor_name);

            convertView.setTag(viewHolder);
        } else {
            viewHolder = (ViewHolder) convertView.getTag();
        }

        Expositor expositor = mData.get(position);

        if (expositor.getPicUrl() != null && !expositor.getPicUrl().isEmpty()) {
            Picasso.with(mContext).load(expositor.getPicUrl())
                    .placeholder(R.drawable.placeholder_large)
                    .error(R.drawable.placeholder_large)
                    .into(viewHolder.picture);
        } else {
            Picasso.with(mContext)
                    .load(R.drawable.placeholder_large)
                    .into(viewHolder.picture);
        }

        viewHolder.name.setText(expositor.getCompany());

        return convertView;
    }

    @Override
    public int getCount() {
        return mData.size();
    }

    @Override
    public Expositor getItem(int position) {
        return mData.get(position);
    }

    @Override
    public long getItemId(int position) {
        return mData.get(position).getId();
    }

    public void addItems(List<Expositor> items) {
        mData.clear();
        mData.addAll(items);
        notifyDataSetChanged();
    }

    static class ViewHolder {
        ImageView picture;
        TextView name;
        TextView description;
    }
}
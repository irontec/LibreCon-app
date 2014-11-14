package com.irontec.librecon.adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;

import com.irontec.librecon.R;
import com.irontec.librecon.ui.SquaredImageView;
import com.squareup.picasso.Picasso;

import java.util.List;

/**
 * Created by Asier Fernandez on 16/09/14.
 */
public class PhotoCallAdapter extends BaseAdapter {

    private final static String TAG = PhotoCallAdapter.class.getSimpleName();
    private Context mContext;
    private LayoutInflater layoutInflater;
    private List<String> mData;
    private List<String> mUrls;

    public PhotoCallAdapter(Context context, List<String> data, List<String> urls) {
        this.mContext = context;
        this.layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mData = data;
        this.mUrls = urls;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        ViewHolder viewHolder;
        if(convertView==null){
            convertView = layoutInflater.inflate(R.layout.row_photocall, parent, false);

            viewHolder = new ViewHolder();
            viewHolder.image = (SquaredImageView) convertView.findViewById(R.id.photocall_image);

            convertView.setTag(viewHolder);
        } else {
            viewHolder = (ViewHolder) convertView.getTag();
        }

        String image = mData.get(position);
        String url = mUrls.get(position);

        if(image != null && !image.isEmpty()) {
            Picasso.with(mContext)
                    .load(url)
                    .placeholder(R.drawable.placeholder_large)
                    .error(R.drawable.placeholder_large)
                    .centerCrop()
                    .fit()
                    .into(viewHolder.image);
            viewHolder.image.setTag(url);
        } else {
            Picasso.with(mContext)
                    .load(R.drawable.placeholder_large)
                    .fit()
                    .centerCrop()
                    .into(viewHolder.image);
        }
        return convertView;
    }

    @Override
    public int getCount() {
        return mData.size();
    }

    @Override
    public Object getItem(int position) {
        return mUrls.get(position);
    }

    @Override
    public long getItemId(int position) {
        return position;
    }

    public void addItems(List<String> items, List<String> urls) {
        mData.clear();
        mData.addAll(items);
        mUrls.clear();
        mUrls.addAll(urls);
        notifyDataSetChanged();
    }

    static class ViewHolder {
        SquaredImageView image;
    }

}

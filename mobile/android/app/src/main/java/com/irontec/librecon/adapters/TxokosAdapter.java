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

import librecon.Txoko;

/**
 * Created by Asier Fernandez on 16/09/14.
 */
public class TxokosAdapter extends BaseAdapter {

    private final static String TAG = TxokosAdapter.class.getSimpleName();
    private Context mContext;
    private LayoutInflater layoutInflater;
    private List<Txoko> mData;

    public TxokosAdapter(Context context, List<Txoko> data) {
        this.mContext = context;
        this.layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mData = data;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        ViewHolder viewHolder;
        if (convertView == null) {
            convertView = layoutInflater.inflate(R.layout.row_txoko, parent, false);

            viewHolder = new ViewHolder();
            viewHolder.picture = (ImageView) convertView.findViewById(R.id.txoko_picture);
            viewHolder.title = (TextView) convertView.findViewById(R.id.txoko_title);

            convertView.setTag(viewHolder);
        } else {
            viewHolder = (ViewHolder) convertView.getTag();
        }

        Txoko txoko = mData.get(position);

        if (txoko.getPicUrl() != null && !txoko.getPicUrl().isEmpty()) {
            Picasso.with(mContext).load(txoko.getPicUrl())
                    .placeholder(R.drawable.placeholder_large)
                    .error(R.drawable.placeholder_large)
                    .into(viewHolder.picture);
        } else {
            Picasso.with(mContext)
                    .load(R.drawable.placeholder_large)
                    .into(viewHolder.picture);
        }

        viewHolder.title.setText(txoko.getTitle());

        return convertView;
    }

    @Override
    public int getCount() {
        return mData.size();
    }

    @Override
    public Txoko getItem(int position) {
        return mData.get(position);
    }

    @Override
    public long getItemId(int position) {
        return mData.get(position).getId();
    }

    public void addItems(List<Txoko> items) {
        mData.clear();
        mData.addAll(items);
        notifyDataSetChanged();
    }

    static class ViewHolder {
        ImageView picture;
        TextView title;
    }
}
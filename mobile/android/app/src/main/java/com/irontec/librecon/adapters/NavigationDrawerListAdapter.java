package com.irontec.librecon.adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.ImageView;
import android.widget.TextView;

import com.irontec.librecon.R;
import com.irontec.librecon.domains.MeDomain;
import com.squareup.picasso.Picasso;

import librecon.Me;

/**
 * Created by Asier Fernandez on 15/09/14.
 */
public class NavigationDrawerListAdapter extends BaseAdapter {

    private static final int TYPE_HEADER = 0;
    private static final int MAX_TYPES = 1;

    private Context mContext;
    private String[] mData;
    private LayoutInflater mInflater;
    private Me mMe;

    public NavigationDrawerListAdapter(Context ctx, String[] mData) {
        this.mContext = ctx;
        this.mData = mData;
        this.mInflater = (LayoutInflater) ctx.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
    }

    @Override
    public int getCount() {
        return mData.length;
    }

    @Override
    public long getItemId(int position) {
        return 0;
    }

    @Override
    public String getItem(int position) {
            return mData[position];
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        int type = getItemViewType(position);
            if (position == TYPE_HEADER) {
                convertView = mInflater.inflate(R.layout.row_navigation_drawer_header, null);

                ImageView userPicture = (ImageView) convertView.findViewById(R.id.navigation_drawer_header_user_picture);
                TextView userName = (TextView) convertView.findViewById(R.id.navigation_drawer_header_user_name);
                TextView userEmail = (TextView) convertView.findViewById(R.id.navigation_drawer_header_user_email);
                mMe = MeDomain.get(mContext);
                if (mMe != null) {
                    if (!mMe.getPicUrlCircle().isEmpty()) {
                        Picasso.with(mContext).load(mMe.getPicUrlCircle())
                                .placeholder(R.drawable.user_placeholder)
                                .error(R.drawable.user_placeholder)
                                .into(userPicture);
                    } else {
                        Picasso.with(mContext).load(R.drawable.user_placeholder)
                                .into(userPicture);
                    }
                    userName.setText(mMe.getName() + " " + mMe.getLastName());
                    userEmail.setText(mMe.getEmail());
                } else {
                    Picasso.with(mContext).load(R.drawable.user_placeholder).into(userPicture);
                    userName.setText(mContext.getString(R.string.guest));
                }
            } else {
                convertView = mInflater.inflate(R.layout.row_navigation_drawer_item, null);
                TextView title = (TextView) convertView.findViewById(R.id.title);
                ImageView icon = (ImageView) convertView.findViewById(R.id.icon);
                title.setText(mData[position]);
                int resourceId = 0;
                switch (position) {
                    case 1:
                        resourceId = R.drawable.ic_01_agenda;
                        break;
                    case 2:
                        resourceId = R.drawable.ic_02_asistentes;
                        break;
                    case 3:
                        resourceId = R.drawable.ic_03_reuniones;
                        break;
                    case 4:
                        resourceId = R.drawable.ic_04_ubicaciones;
                        break;
                    case 5:
                        resourceId = R.drawable.ic_05_patrocinadores;
                        break;
                    case 6:
                        resourceId = R.drawable.ic_06_photocall;
                        break;
                }
                Picasso.with(mContext).load(resourceId).into(icon);
            }

        return convertView;
    }

}

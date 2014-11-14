package com.irontec.librecon.adapters;

import android.content.Context;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.BaseAdapter;
import android.widget.Filter;
import android.widget.Filterable;
import android.widget.ImageView;
import android.widget.TextView;

import com.irontec.librecon.R;
import com.squareup.picasso.Picasso;

import java.util.ArrayList;
import java.util.List;

import librecon.Assistant;

/**
 * Created by Asier Fernandez on 16/09/14.
 */
public class AssistantsAdapter extends BaseAdapter implements Filterable {

    private final static String TAG = AssistantsAdapter.class.getSimpleName();
    private Context mContext;
    private LayoutInflater layoutInflater;
    private List<Assistant> mData;
    private List<Assistant> mOriginalValues;

    public AssistantsAdapter(Context context, List<Assistant> data) {
        this.mContext = context;
        this.layoutInflater = (LayoutInflater) context.getSystemService(Context.LAYOUT_INFLATER_SERVICE);
        this.mData = data;
        this.mOriginalValues = data;
    }

    @Override
    public View getView(int position, View convertView, ViewGroup parent) {
        ViewHolder viewHolder;
        if(convertView==null){
            convertView = layoutInflater.inflate(R.layout.row_assistant, parent, false);

            viewHolder = new ViewHolder();
            viewHolder.title = (TextView) convertView.findViewById(R.id.assistant_name);
            viewHolder.image = (ImageView) convertView.findViewById(R.id.assistant_picture);
            viewHolder.company = (TextView) convertView.findViewById(R.id.assistant_company);
            viewHolder.position = (TextView) convertView.findViewById(R.id.assistant_position);
            viewHolder.interests = (TextView) convertView.findViewById(R.id.assistant_interests);

            convertView.setTag(viewHolder);
        } else {
            viewHolder = (ViewHolder) convertView.getTag();
        }

        Assistant assistant = mData.get(position);

        if(assistant != null) {
            if (!assistant.getPicUrlCircle().isEmpty()) {
                Picasso.with(mContext)
                        .load(assistant.getPicUrlCircle())
                        .placeholder(R.drawable.user_placeholder)
                        .error(R.drawable.user_placeholder)
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
            viewHolder.title.setText(assistant.getName() + " " + assistant.getLastName());
            viewHolder.company.setText(assistant.getCompany());
            viewHolder.position.setText(assistant.getPosition());
            viewHolder.interests.setText(assistant.getInterests());
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

    public void addItems(List<Assistant> items) {
        mData.clear();
        mData.addAll(items);
        mOriginalValues.clear();
        mOriginalValues.addAll(items);
        notifyDataSetChanged();
    }

    static class ViewHolder {
        TextView title;
        ImageView image;
        TextView company;
        TextView position;
        TextView interests;
    }

    @Override
    public Filter getFilter() {
        Filter filter = new Filter() {

            @SuppressWarnings("unchecked")
            @Override
            protected void publishResults(CharSequence constraint, FilterResults results) {

                mData = (List<Assistant>) results.values;
                notifyDataSetChanged();
            }

            @Override
            protected FilterResults performFiltering(CharSequence constraint) {
                FilterResults results = new FilterResults();        // Holds the results of a filtering operation in values
                List<Assistant> FilteredArrList = new ArrayList<Assistant>();

                // If constraint(CharSequence that is received) is null returns the mOriginalValues(Original) values
                // else does the Filtering and returns FilteredArrList(Filtered)
                if (constraint == null || constraint.length() == 0) {

                    // set the Original result to return
                    results.count = mOriginalValues.size();
                    results.values = mOriginalValues;
                } else {
                    constraint = constraint.toString().toLowerCase();
                    for (int i = 0; i < mOriginalValues.size(); i++) {
                        Assistant data = mOriginalValues.get(i);
                        if (data.getName().toLowerCase().contains(constraint.toString())
                                || data.getLastName().toLowerCase().contains(constraint.toString())
                                || data.getCompany().toLowerCase().contains(constraint.toString())
                                || data.getInterests().toLowerCase().contains(constraint.toString()
                        )) {
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

package com.irontec.librecon.comparators;

import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Comparator;

import librecon.Meeting;

/**
 * Created by Asier Fernandez on 29/09/14.
 */
public class MeetingDateComparator implements Comparator<Meeting> {
    @Override
    public int compare(Meeting o1, Meeting o2) {

        SimpleDateFormat format = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
        long date1 = 0l;
        long date2 = 0l;
        try {
            date1 = format.parse(o1.getCreatedAt()).getTime();
            date2 = format.parse(o2.getCreatedAt()).getTime();
        } catch (ParseException pEx) {
            pEx.printStackTrace();
        }
        return (date1 > date2 ? -1 : 1);     //descending
        //  return (date1 > date2 ? 1 : -1);     //ascending
    }
}

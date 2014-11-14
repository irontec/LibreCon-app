package com.irontec.librecon.comparators;

import java.util.Comparator;

import librecon.Meeting;

/**
 * Created by Asier Fernandez on 29/09/14.
 */
public class MeetingStatusComparator implements Comparator<Meeting> {
    @Override
    public int compare(Meeting o1, Meeting o2) {
        return o1.getStatus().compareTo(o2.getStatus());
    }
}

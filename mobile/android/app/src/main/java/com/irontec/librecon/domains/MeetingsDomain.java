package com.irontec.librecon.domains;

import android.content.Context;

import com.irontec.librecon.DaoApplication;

import java.util.ArrayList;
import java.util.List;

import librecon.Meeting;
import librecon.MeetingDao;

/**
 * Created by Asier Fernandez on 18/09/14.
 */
public class MeetingsDomain {

    public static void insertOrUpdate(Context context, Meeting meeting) {
        getMeetingsDao(context).insertOrReplace(meeting);
    }

    public static void clearMeetings(Context context) {
        getMeetingsDao(context).deleteAll();
    }

    public static void insertOrUpdateInTransaction(Context context, List<Meeting> meetings) {
        clearMeetings(context);
        getMeetingsDao(context).insertOrReplaceInTx(meetings);
    }

    public static void deleteMeetingWithId(Context context, long id) {
        getMeetingsDao(context).delete(getMeetingForId(context, id));
    }

    public static List<Meeting> getAllMeetings(Context context) {
        return getMeetingsDao(context).loadAll();
    }

    public static Meeting getMeetingForId(Context context, long id) {
        return getMeetingsDao(context).load(id);
    }

    private static MeetingDao getMeetingsDao(Context c) {
        return ((DaoApplication) c.getApplicationContext()).getDaoSession().getMeetingDao();
    }

}

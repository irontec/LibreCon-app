package com.irontec.librecon.domains;

import android.content.Context;

import com.irontec.librecon.DaoApplication;

import java.util.List;

import librecon.ScheduleSpeaker;
import librecon.ScheduleSpeakerDao;

/**
 * Created by Asier Fernandez on 18/09/14.
 */
public class ScheduleSpeakerDomain {

    public static void insertOrUpdate(Context context, ScheduleSpeaker scheduleSpeaker) {
        getScheduleSpeakerDao(context).insertOrReplace(scheduleSpeaker);
    }

    public static void insertOrUpdateInTransaction(Context context, List<ScheduleSpeaker> scheduleSpeaker) {
        getScheduleSpeakerDao(context).insertOrReplaceInTx(scheduleSpeaker);
    }

    public static List<ScheduleSpeaker> getAllScheduleSpeakersWithScheduleId(Context context, long id) {
       return getScheduleSpeakerDao(context).queryBuilder()
                .where(ScheduleSpeakerDao.Properties.ScheduleId.eq(id)).list();
    }

    public static void clearScheduleSpeakers(Context context) {
        getScheduleSpeakerDao(context).deleteAll();
    }

    public static void deleteScheduleSpeakerWithId(Context context, long id) {
        getScheduleSpeakerDao(context).delete(getScheduleSpeakerForId(context, id));
    }

    public static List<ScheduleSpeaker> getAllScheduleSpeakers(Context context) {
        return getScheduleSpeakerDao(context).loadAll();
    }

    public static ScheduleSpeaker getScheduleSpeakerForId(Context context, long id) {
        return getScheduleSpeakerDao(context).load(id);
    }

    private static ScheduleSpeakerDao getScheduleSpeakerDao(Context c) {
        return ((DaoApplication) c.getApplicationContext()).getDaoSession().getScheduleSpeakerDao();
    }

}

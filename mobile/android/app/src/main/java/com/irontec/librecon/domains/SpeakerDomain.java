package com.irontec.librecon.domains;

import android.content.Context;

import com.irontec.librecon.DaoApplication;

import java.util.ArrayList;
import java.util.List;

import de.greenrobot.dao.query.Query;
import de.greenrobot.dao.query.WhereCondition;
import librecon.Speaker;
import librecon.SpeakerDao;

/**
 * Created by Asier Fernandez on 18/09/14.
 */
public class SpeakerDomain {

    public static void insertOrUpdate(Context context, Speaker speaker) {
        getSpeakerDao(context).insertOrReplace(speaker);
    }

    public static void insertOrUpdateInTransaction(Context context, List<Speaker> speakers) {
        List<String> ids = new ArrayList<String>();
        for (Speaker speaker : speakers) {
            ids.add(speaker.getId().toString());
        }
        List<Speaker> speakersToDelete = getSpeakerDao(context).queryBuilder().where(SpeakerDao.Properties.Id.notIn(ids)).list();
        getSpeakerDao(context).deleteInTx(speakersToDelete);
        getSpeakerDao(context).insertOrReplaceInTx(speakers);
    }

    public static void clearSpeakers(Context context) {
        getSpeakerDao(context).deleteAll();
    }

    public static void deleteSpeakerWithId(Context context, long id) {
        getSpeakerDao(context).delete(getSpeakerForId(context, id));
    }

    public static List<Speaker> getAllSpeakers(Context context) {
        return getSpeakerDao(context).loadAll();
    }

    public static Speaker getSpeakerForId(Context context, long id) {
        return getSpeakerDao(context).load(id);
    }

    public static Speaker getSpeakersOfSchedule(Context context, long id) {
        if (context == null) {
            return null;
        }
        List<Speaker> speakers = getSpeakerDao(context).queryRawCreate(
                "WHERE T._ID IN (SELECT SPEAKER_ID FROM SCHEDULE_SPEAKER WHERE SCHEDULE_ID = ?)", id)
                .list();
        if (!speakers.isEmpty())
            return speakers.get(0);
        else
            return null;
    }

    private static SpeakerDao getSpeakerDao(Context c) {
        return ((DaoApplication) c.getApplicationContext()).getDaoSession().getSpeakerDao();
    }

}

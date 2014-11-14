package com.irontec.librecon.domains;

import android.content.Context;

import com.irontec.librecon.DaoApplication;

import java.util.List;

import librecon.AssistantMeeting;
import librecon.AssistantMeetingDao;

/**
 * Created by Asier Fernandez on 18/09/14.
 */
public class AssistantMeetingDomain {

    public static void insertOrUpdate(Context context, AssistantMeeting assistantMeeting) {
        getAssistantMeetingDao(context).insertOrReplace(assistantMeeting);
    }

    public static void insertOrUpdateInTransaction(Context context, List<AssistantMeeting> assistantMeetings) {
        getAssistantMeetingDao(context).insertOrReplaceInTx(assistantMeetings);
    }

    public static AssistantMeeting getAssistantMeetingWithMeetingId(Context context, long id) {
       return getAssistantMeetingDao(context).queryBuilder()
                .where(AssistantMeetingDao.Properties.MeetingId.eq(id)).list().get(0);
    }

    public static void clearAssistantMeetings(Context context) {
        getAssistantMeetingDao(context).deleteAll();
    }

    public static void clearAssistantMeeting(Context context, AssistantMeeting assistantMeeting) {
        getAssistantMeetingDao(context).delete(assistantMeeting);
    }

    public static void deleteAssistantMeetingWithId(Context context, long id) {
        getAssistantMeetingDao(context).delete(getAssistantMeetingForId(context, id));
    }

    public static List<AssistantMeeting> getAllAssistantMeetings(Context context) {
        return getAssistantMeetingDao(context).loadAll();
    }

    public static AssistantMeeting getAssistantMeetingForId(Context context, long id) {
        return getAssistantMeetingDao(context).load(id);
    }

    private static AssistantMeetingDao getAssistantMeetingDao(Context c) {
        return ((DaoApplication) c.getApplicationContext()).getDaoSession().getAssistantMeetingDao();
    }

}

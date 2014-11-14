package com.irontec.librecon.domains;

import android.content.Context;

import com.irontec.librecon.DaoApplication;

import java.util.ArrayList;
import java.util.List;

import librecon.Schedule;
import librecon.ScheduleDao;

/**
 * Created by Asier Fernandez on 18/09/14.
 */
public class ScheduleDomain {

    public static void insertOrUpdate(Context context, Schedule schedule) {
        getScheduleDao(context).insertOrReplace(schedule);
    }

    public static void insertOrUpdateInTransaction(Context context, List<Schedule> schedules) {
        List<String> ids = new ArrayList<String>();
        for (Schedule schedule : schedules) {
            ids.add(schedule.getId().toString());
        }
        List<Schedule> schedulesToDelete = getScheduleDao(context).queryBuilder().where(ScheduleDao.Properties.Id.notIn(ids)).list();
        getScheduleDao(context).deleteInTx(schedulesToDelete);
        getScheduleDao(context).insertOrReplaceInTx(schedules);
    }

    public static void clearSchedules(Context context) {
        getScheduleDao(context).deleteAll();
    }

    public static void deleteScheduleWithId(Context context, long id) {
        getScheduleDao(context).delete(getScheduleForId(context, id));
    }

    public static List<Schedule> getAllSchedules(Context context) {
        return getScheduleDao(context).loadAll();
    }

    public static List<Schedule> getAllDayOneSchedules(Context context) {
        return getScheduleDao(context).queryBuilder()
                .where(ScheduleDao.Properties.TargetDate.eq("1"))
                .orderAsc(ScheduleDao.Properties.StartDatetime)
                .list();
    }

    public static List<Schedule> getAllDayTwoSchedules(Context context) {
        return getScheduleDao(context).queryBuilder()
                .where(ScheduleDao.Properties.TargetDate.eq("2"))
                .orderAsc(ScheduleDao.Properties.StartDatetime)
                .list();
    }

    public static Schedule getScheduleForId(Context context, long id) {
        return getScheduleDao(context).load(id);
    }

    private static ScheduleDao getScheduleDao(Context c) {
        return ((DaoApplication) c.getApplicationContext()).getDaoSession().getScheduleDao();
    }

}

package com.irontec.librecon.domains;

import android.content.Context;

import com.irontec.librecon.DaoApplication;

import java.util.ArrayList;
import java.util.List;

import librecon.Expositor;
import librecon.ExpositorDao;
import librecon.SponsorDao;

/**
 * Created by Asier Fernandez on 18/09/14.
 */
public class ExpositorsDomain {

    public static void insertOrUpdate(Context context, Expositor expositor) {
        getExpositorsDao(context).insertOrReplace(expositor);
    }

    public static void clearExpositors(Context context) {
        getExpositorsDao(context).deleteAll();
    }

    public static void insertOrUpdateInTransaction(Context context, List<Expositor> expositors) {
        clearExpositors(context);
        getExpositorsDao(context).insertOrReplaceInTx(expositors);
    }

    public static void deleteExpositorWithId(Context context, long id) {
        getExpositorsDao(context).delete(getExpositorForId(context, id));
    }

    public static List<Expositor> getAllExpositors(Context context) {
        return getExpositorsDao(context).queryBuilder().orderAsc(ExpositorDao.Properties.OrderField).list();
    }

    public static Expositor getExpositorForId(Context context, long id) {
        return getExpositorsDao(context).load(id);
    }

    private static ExpositorDao getExpositorsDao(Context c) {
        return ((DaoApplication) c.getApplicationContext()).getDaoSession().getExpositorDao();
    }

}

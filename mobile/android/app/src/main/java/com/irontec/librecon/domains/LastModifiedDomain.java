package com.irontec.librecon.domains;

import android.content.Context;

import com.irontec.librecon.DaoApplication;

import librecon.LastModified;
import librecon.LastModifiedDao;

/**
 * Created by Asier Fernandez on 18/09/14.
 */
public class LastModifiedDomain {

    public static void insertOrUpdate(Context context, LastModified lastModified) {
        getLastModifiedDao(context).insertOrReplace(lastModified);
    }

    public static void clearLastModified(Context context) {
        getLastModifiedDao(context).deleteAll();
    }

    public static void deleteLastModifiedWithId(Context context, long id) {
        getLastModifiedDao(context).delete(getLastModifiedForId(context, id));
    }

    public static LastModified getLastModifiedForId(Context context, long id) {
        return getLastModifiedDao(context).load(id);
    }

    private static LastModifiedDao getLastModifiedDao(Context c) {
        return ((DaoApplication) c.getApplicationContext()).getDaoSession().getLastModifiedDao();
    }

}

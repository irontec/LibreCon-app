package com.irontec.librecon.domains;

import android.content.Context;

import com.irontec.librecon.DaoApplication;

import java.util.List;

import librecon.Me;
import librecon.MeDao;

/**
 * Created by Asier Fernandez on 18/09/14.
 */
public class MeDomain {

    public static void insertOrUpdate(Context context, Me me) {
        getMeDao(context).insertOrReplace(me);
    }

    public static void clear(Context context) {
        getMeDao(context).deleteAll();
    }

    public static Me get(Context context) {
        List<Me> listOfMe = getMeDao(context).loadAll();
        if (!listOfMe.isEmpty())
            return listOfMe.get(0);
        else
            return null;
    }

    private static MeDao getMeDao(Context c) {
        return ((DaoApplication) c.getApplicationContext()).getDaoSession().getMeDao();
    }

}

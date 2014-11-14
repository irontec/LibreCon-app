package com.irontec.librecon.domains;

import android.content.Context;

import com.irontec.librecon.DaoApplication;

import java.util.ArrayList;
import java.util.List;

import librecon.Sponsor;
import librecon.SponsorDao;

/**
 * Created by Asier Fernandez on 18/09/14.
 */
public class SponsorsDomain {

    public static void insertOrUpdate(Context context, Sponsor sponsor) {
        getSponsorsDao(context).insertOrReplace(sponsor);
    }

    public static void clearSponsors(Context context) {
        getSponsorsDao(context).deleteAll();
    }

    public static void insertOrUpdateInTransaction(Context context, List<Sponsor> sponsors) {
        clearSponsors(context);
        getSponsorsDao(context).insertOrReplaceInTx(sponsors);
    }

    public static void deleteSponsorWithId(Context context, long id) {
        getSponsorsDao(context).delete(getSponsorForId(context, id));
    }

    public static List<Sponsor> getAllSponsors(Context context) {
        return getSponsorsDao(context).queryBuilder().orderAsc(SponsorDao.Properties.OrderField).list();
    }

    public static Sponsor getSponsorForId(Context context, long id) {
        return getSponsorsDao(context).load(id);
    }

    private static SponsorDao getSponsorsDao(Context c) {
        return ((DaoApplication) c.getApplicationContext()).getDaoSession().getSponsorDao();
    }

}

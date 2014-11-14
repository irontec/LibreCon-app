package com.irontec.librecon.domains;

import android.content.Context;

import com.irontec.librecon.DaoApplication;

import java.util.ArrayList;
import java.util.List;

import librecon.SponsorDao;
import librecon.Txoko;
import librecon.TxokoDao;

/**
 * Created by Asier Fernandez on 18/09/14.
 */
public class TxokosDomain {

    public static void insertOrUpdate(Context context, Txoko txoko) {
        getTxokosDao(context).insertOrReplace(txoko);
    }

    public static void clearTxokos(Context context) {
        getTxokosDao(context).deleteAll();
    }

    public static void insertOrUpdateInTransaction(Context context, List<Txoko> txokos) {
        clearTxokos(context);
        getTxokosDao(context).insertOrReplaceInTx(txokos);
    }

    public static void deleteTxokoWithId(Context context, long id) {
        getTxokosDao(context).delete(getTxokoForId(context, id));
    }

    public static List<Txoko> getAllTxokos(Context context) {
        return getTxokosDao(context).queryBuilder().orderAsc(TxokoDao.Properties.OrderField).list();
    }

    public static Txoko getTxokoForId(Context context, long id) {
        return getTxokosDao(context).load(id);
    }

    private static TxokoDao getTxokosDao(Context c) {
        return ((DaoApplication) c.getApplicationContext()).getDaoSession().getTxokoDao();
    }

}

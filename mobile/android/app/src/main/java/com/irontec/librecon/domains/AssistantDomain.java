package com.irontec.librecon.domains;

import android.content.Context;

import com.irontec.librecon.DaoApplication;

import java.util.ArrayList;
import java.util.List;

import librecon.Assistant;
import librecon.AssistantDao;

/**
 * Created by Asier Fernandez on 18/09/14.
 */
public class AssistantDomain {

    public static void insertOrUpdate(Context context, Assistant assistant) {
        getAssistantDao(context).insertOrReplace(assistant);
    }

    public static void clearAssistants(Context context) {
        getAssistantDao(context).deleteAll();
    }

    public static void insertDeleteOrUpdateInTransaction(Context context, List<Assistant> assistants) {
        clearAssistants(context);
        getAssistantDao(context).insertOrReplaceInTx(assistants);
    }

    public static void insertOrUpdateInTransaction(Context context, List<Assistant> assistants) {
        List<String> ids = new ArrayList<String>();
        for (Assistant assistant : assistants) {
            List<Assistant> saved = getAssistantDao(context).queryBuilder().where(AssistantDao.Properties.Id.eq(assistant.getId())).list();
            if (saved.isEmpty()) {
                getAssistantDao(context).insert(assistant);
            } else {
                if (assistant.getEmail().isEmpty() || !assistant.getCellPhone().isEmpty()) {
                    Assistant savedAssistant = saved.get(0);
                    savedAssistant.setEmail(assistant.getEmail());
                    savedAssistant.setCellPhone(assistant.getCellPhone());
                    getAssistantDao(context).update(savedAssistant);
                }
            }
        }
    }

    public static void deleteAssistantWithId(Context context, long id) {
        getAssistantDao(context).delete(getAssistantForId(context, id));
    }

    public static List<Assistant> getAllAssistants(Context context) {
        return getAssistantDao(context).queryBuilder().orderAsc(AssistantDao.Properties.Name).list();
    }

    public static Assistant getAssistantForId(Context context, long id) {
        return getAssistantDao(context).load(id);
    }

    private static AssistantDao getAssistantDao(Context c) {
        return ((DaoApplication) c.getApplicationContext()).getDaoSession().getAssistantDao();
    }

}

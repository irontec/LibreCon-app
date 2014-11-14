package com.irontec.librecon.utils;

import org.joda.time.DateTime;
import org.joda.time.format.DateTimeFormat;
import org.joda.time.format.DateTimeFormatter;

/**
 * Created by Asier Fernandez on 22/09/14.
 */
public class DateUtils {

    public static String getHourFromDate(String date) {
        //date = "2014-11-12 11:42:36";
        if (date != null && !date.isEmpty()) {
            DateTimeFormatter dateStringFormat = DateTimeFormat.forPattern("yyyy-MM-dd HH:mm:ss");
            DateTime dateTime = dateStringFormat.parseDateTime(date);
            DateTimeFormatter dtfOut = DateTimeFormat.forPattern("HH:mm");
            return dtfOut.print(dateTime);
        } else {
            return "";
        }
    }

    public static String getPrettyDate(String date) {
        //date = "2014-11-12 11:42:36";
        if (date != null && !date.isEmpty()) {
            DateTimeFormatter dateStringFormat = DateTimeFormat.forPattern("yyyy-MM-dd HH:mm:ss");
            DateTime dateTime = dateStringFormat.parseDateTime(date);
            DateTimeFormatter dtfOut = DateTimeFormat.forPattern("dd/MM/yyyy HH:mm");
            return dtfOut.print(dateTime);
        } else {
            return "";
        }
    }
}

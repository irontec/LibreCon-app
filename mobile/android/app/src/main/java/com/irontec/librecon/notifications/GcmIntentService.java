package com.irontec.librecon.notifications;

import android.app.IntentService;
import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.content.SharedPreferences;
import android.graphics.Color;
import android.media.RingtoneManager;
import android.net.Uri;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.support.v4.app.NotificationCompat;
import android.support.v4.app.TaskStackBuilder;

import com.google.android.gms.gcm.GoogleCloudMessaging;
import com.irontec.librecon.MainActivity;
import com.irontec.librecon.MeetingDetailActivity;
import com.irontec.librecon.R;

/**
 * Created by Asier Fernandez on 07/10/14.
 */
public class GcmIntentService extends IntentService {

    private final static String TAG = GcmIntentService.class.getSimpleName();

    public static final String CURRENT_NOTIFICATION_ID = "current_notification_id";
    public int NOTIFICATION_ID = 0;
    private NotificationManager mNotificationManager;
    private NotificationCompat.Builder mBuilder;

    public GcmIntentService() {
        super("GcmIntentService");
    }

    @Override
    protected void onHandleIntent(Intent intent) {
        Bundle extras = intent.getExtras();
        GoogleCloudMessaging gcm = GoogleCloudMessaging.getInstance(this);
        String messageType = gcm.getMessageType(intent);
        if (!extras.isEmpty()) {
            if (GoogleCloudMessaging.MESSAGE_TYPE_MESSAGE.equals(messageType)) {
                sendNotification(extras);
            }
        }
        GcmBroadcastReceiver.completeWakefulIntent(intent);
    }

    // Put the message into a notification and post it.
    // This is just one simple example of what you might choose to do with
    // a GCM message.
    private void sendNotification(Bundle bundle) {

        String message = bundle.getString("message");
        String meetingId = bundle.getString("meetingId");

        mNotificationManager = (NotificationManager)
                this.getSystemService(Context.NOTIFICATION_SERVICE);

        Intent intent = null;
        TaskStackBuilder stackBuilder = TaskStackBuilder.create(this);
        if (meetingId != null) {
            intent = new Intent(getApplicationContext(), MeetingDetailActivity.class);
            intent.putExtra("meetingId", meetingId);
        } else {
            intent = new Intent(getApplicationContext(), MainActivity.class);
        }

        intent.putExtra("navigateStack", true);
        stackBuilder.addNextIntent(intent);
        PendingIntent contentIntent = stackBuilder.getPendingIntent(0, PendingIntent.FLAG_UPDATE_CURRENT);

        mBuilder = new NotificationCompat.Builder(this)
                .setSmallIcon(R.drawable.ic_launcher)
                .setContentTitle(getString(R.string.app_name))
                .setLights(Color.BLUE, 500, 500)
                .setContentText(message)
                .setStyle(new NotificationCompat.BigTextStyle().bigText(message))
                .setContentIntent(contentIntent)
                .setAutoCancel(true)
                .setDefaults(Notification.DEFAULT_ALL); // requires VIBRATE permission

        NOTIFICATION_ID++;

        mNotificationManager.notify(NOTIFICATION_ID, mBuilder.build());

    }
}
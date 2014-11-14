package com.irontec.librecon;

import android.app.Activity;
import android.content.Intent;

import com.irontec.librecon.domains.AssistantDomain;
import com.irontec.librecon.domains.AssistantMeetingDomain;
import com.irontec.librecon.domains.LastModifiedDomain;
import com.irontec.librecon.domains.MeDomain;
import com.irontec.librecon.domains.MeetingsDomain;

/**
 * Created by Asier Fernandez on 06/10/14.
 */
public class BaseActivity extends Activity {

    public void logout() {
        MeDomain.clear(this);
        LastModifiedDomain.clearLastModified(this);
        MeetingsDomain.clearMeetings(this);
        AssistantDomain.clearAssistants(this);
        AssistantMeetingDomain.clearAssistantMeetings(this);
        Intent intent = new Intent(this, LoginPagerActivity.class);
        startActivity(intent);
        finish();
    }
}

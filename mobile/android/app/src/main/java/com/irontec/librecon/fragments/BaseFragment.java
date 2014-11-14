package com.irontec.librecon.fragments;

import android.app.Fragment;
import android.content.Intent;

import com.irontec.librecon.LoginPagerActivity;
import com.irontec.librecon.domains.AssistantDomain;
import com.irontec.librecon.domains.AssistantMeetingDomain;
import com.irontec.librecon.domains.LastModifiedDomain;
import com.irontec.librecon.domains.MeDomain;
import com.irontec.librecon.domains.MeetingsDomain;

/**
 * Created by Asier Fernandez on 06/10/14.
 */
public class BaseFragment extends Fragment {

    public void logout() {
        MeDomain.clear(getActivity());
        LastModifiedDomain.clearLastModified(getActivity());
        MeetingsDomain.clearMeetings(getActivity());
        AssistantDomain.clearAssistants(getActivity());
        AssistantMeetingDomain.clearAssistantMeetings(getActivity());
        Intent intent = new Intent(getActivity(), LoginPagerActivity.class);
        startActivity(intent);
        getActivity().finish();
    }

}

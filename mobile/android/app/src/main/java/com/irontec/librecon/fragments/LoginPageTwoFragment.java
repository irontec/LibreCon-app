package com.irontec.librecon.fragments;

import android.app.Activity;
import android.content.Context;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.net.Uri;
import android.os.AsyncTask;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.KeyEvent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.view.inputmethod.EditorInfo;
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.LinearLayout;
import android.widget.TextView;
import android.widget.Toast;

import com.google.gson.Gson;
import com.google.gson.GsonBuilder;
import com.irontec.librecon.LoginPagerActivity;
import com.irontec.librecon.R;
import com.irontec.librecon.api.APILibrecon;
import com.irontec.librecon.deserializers.MeDeserializer;
import com.irontec.librecon.domains.AssistantDomain;
import com.irontec.librecon.domains.AssistantMeetingDomain;
import com.irontec.librecon.domains.LastModifiedDomain;
import com.irontec.librecon.domains.MeDomain;
import com.irontec.librecon.domains.MeetingsDomain;
import com.irontec.librecon.ui.FloatLabelLayout;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;

import librecon.Me;

public class LoginPageTwoFragment extends Fragment {

    private final static String TAG = LoginPageTwoFragment.class.getSimpleName();
    // UI
    private InputMethodManager mInputMethodManager;
    private LinearLayout mProgressBar;
    private FloatLabelLayout mFloatLabelLayout, mFloatLabelEmailLayout;
    private EditText mCode, mEmail;
    private Button mEnterButton;
    private Button mCancelButton;
    private TextView mResendCode;
    private TextView mTutorial, mResendTutorial;


    // Threads
    private CodeValidationTask codeValidationTask;
    private ResendCodeTask resendCodeTask;

    // Listeners
    private OnFragmentInteractionListener mListener;

    public LoginPageTwoFragment() {
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (getArguments() != null) {
        }
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View rootView = inflater.inflate(R.layout.fragment_login_page_two, container, false);

        mInputMethodManager = (InputMethodManager) getActivity().getSystemService(Context.INPUT_METHOD_SERVICE);

        mProgressBar = (LinearLayout) rootView.findViewById(R.id.progressBar);
        mFloatLabelLayout = (FloatLabelLayout) rootView.findViewById(R.id.floatLabelLayout);
        mFloatLabelEmailLayout = (FloatLabelLayout) rootView.findViewById(R.id.floatLabelEmailLayout);
        mCode = (EditText) rootView.findViewById(R.id.edit_code);
        mEmail = (EditText) rootView.findViewById(R.id.edit_email);
        mTutorial = (TextView) rootView.findViewById(R.id.tutorial);
        mResendTutorial = (TextView) rootView.findViewById(R.id.resendTutorial);

        mResendCode = (TextView) rootView.findViewById(R.id.resendCode);
        mResendCode.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                fadeOutAnimation(mTutorial);
                fadeOutAnimation(mFloatLabelLayout);
                fadeInAnimation(mResendTutorial);
                fadeInAnimation(mFloatLabelEmailLayout);
                mEnterButton.setText(getString(R.string.send));
                mCancelButton.setText(getString(R.string.back));
            }
        });

        mEnterButton = (Button) rootView.findViewById(R.id.enterButton);
        mEnterButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mFloatLabelEmailLayout.getVisibility() == View.VISIBLE) {
                    if (mEmail.getText() != null && !mEmail.getText().toString().isEmpty()) {
                        resendCodeTask = new ResendCodeTask(getActivity(), mEmail.getText().toString());
                        resendCodeTask.execute();
                    }
                } else {
                    if (mCode.getText() != null && !mCode.getText().toString().isEmpty()) {
                        codeValidationTask = new CodeValidationTask(getActivity(), mCode.getText().toString());
                        codeValidationTask.execute();
                    }
                }
            }
        });

        mCancelButton = (Button) rootView.findViewById(R.id.cancelButton);
        mCancelButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (mFloatLabelEmailLayout.getVisibility() == View.VISIBLE){
                    fadeOutAnimation(mResendTutorial);
                    fadeOutAnimation(mFloatLabelEmailLayout);
                    fadeInAnimation(mTutorial);
                    fadeInAnimation(mFloatLabelLayout);
                    mEnterButton.setText(getString(R.string.enter));
                    mCancelButton.setText(getString(R.string.cancel));
                } else {
                    ((LoginPagerActivity) getActivity()).switchToPrevPage();
                }
            }
        });

        mCode.addTextChangedListener(new TextWatcher() {
            public void afterTextChanged(Editable s) {
                if (mCode != null && mCode.getText() != null && mCode.getText().toString().isEmpty()) {
                    mInputMethodManager.hideSoftInputFromWindow(mCode.getWindowToken(), 0);
                }
            }

            public void beforeTextChanged(CharSequence s, int start, int count, int after) {
            }

            public void onTextChanged(CharSequence s, int start, int before, int count) {
            }
        });
        mCode.setOnEditorActionListener(new EditText.OnEditorActionListener() {
            @Override
            public boolean onEditorAction(TextView v, int actionId, KeyEvent event) {
                if (actionId == EditorInfo.IME_ACTION_SEND) {
                    InputMethodManager imm = (InputMethodManager) getActivity().getSystemService(Context.INPUT_METHOD_SERVICE);
                    imm.hideSoftInputFromWindow(v.getWindowToken(), 0);
                    codeValidationTask = new CodeValidationTask(getActivity(), v.getText().toString());
                    codeValidationTask.execute();
                    return true;
                }
                return false;
            }
        });

        return rootView;
    }

    private void fadeInAnimation(View view) {
        if (getActivity() != null && !getActivity().isFinishing() && view.getVisibility() == View.GONE) {
            final Animation animationFadeIn = AnimationUtils.loadAnimation(getActivity(), android.R.anim.fade_in);
            view.startAnimation(animationFadeIn);
            view.setVisibility(View.VISIBLE);
        }
    }

    private void fadeOutAnimation(View view) {
        if (getActivity() != null && !getActivity().isFinishing() && view.getVisibility() == View.VISIBLE) {
            final Animation animationFadeOut = AnimationUtils.loadAnimation(getActivity(), android.R.anim.fade_out);
            view.startAnimation(animationFadeOut);
            view.setVisibility(View.GONE);
        }
    }

    private class CodeValidationTask extends AsyncTask<Void, Void, Boolean> {

        private Context context;
        private String code;

        private CodeValidationTask(Context context, String code) {
            this.context = context;
            this.code = code;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            // TODO loading
            mProgressBar.setVisibility(View.VISIBLE);
            mFloatLabelLayout.setVisibility(View.GONE);
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            JSONObject response = authCode();
            if (response != null) {
                parseMyInfoAndSave(response);
                return true;
            }
            return false;
        }

        private JSONObject authCode() {
            APILibrecon api = APILibrecon.getInstance();
            try {
                JSONObject jsonObj = new JSONObject();
                jsonObj.put("code", code);
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.post(APILibrecon.AUTH_CODE, jsonObj.toString());
                if (response != null && !response.has("errorCode")) {
                    return response;
                }
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            } catch (IOException ex) {
                ex.printStackTrace();
            }
            return null;
        }

        private boolean isNetworkAvailable() {
            ConnectivityManager connectivityManager
                    = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
            NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
            return activeNetworkInfo != null && activeNetworkInfo.isConnected();
        }

        private void parseMyInfoAndSave(JSONObject response) {
            GsonBuilder gsonBuilder = new GsonBuilder();
            gsonBuilder.registerTypeAdapter(Me.class, new MeDeserializer());
            Gson gson = gsonBuilder.create();
            JSONObject jsonObject = new JSONObject();
            try {
                jsonObject = response.getJSONObject("data").getJSONObject("assistant");
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            }
            Me me = gson.fromJson(jsonObject.toString(), Me.class);
            MeDomain.clear(context);
            LastModifiedDomain.clearLastModified(context);
            MeetingsDomain.clearMeetings(context);
            AssistantDomain.clearAssistants(context);
            AssistantMeetingDomain.clearAssistantMeetings(context);
            MeDomain.insertOrUpdate(context, me);
        }

        @Override
        protected void onPostExecute(Boolean aBool) {
            super.onPostExecute(aBool);
            if (aBool) {
                ((LoginPagerActivity) getActivity()).finishWithUserIfPossible();
            } else {
                mProgressBar.setVisibility(View.GONE);
                mFloatLabelLayout.setVisibility(View.VISIBLE);
                Toast.makeText(getActivity(), R.string.http_error, Toast.LENGTH_LONG).show();
            }
        }
    }

    private class ResendCodeTask extends AsyncTask<Void, Void, Boolean> {

        private Context context;
        private String email;

        private ResendCodeTask(Context context, String email) {
            this.context = context;
            this.email = email;
        }

        @Override
        protected void onPreExecute() {
            super.onPreExecute();
            // TODO loading
            mProgressBar.setVisibility(View.VISIBLE);
            mFloatLabelEmailLayout.setVisibility(View.GONE);
        }

        @Override
        protected Boolean doInBackground(Void... params) {
            JSONObject response = resendEmail();
            if (response != null) {
                return true;
            }
            return false;
        }

        private JSONObject resendEmail() {
            APILibrecon api = APILibrecon.getInstance();
            try {
                JSONObject jsonObj = new JSONObject();
                jsonObj.put("email", email);
                if (!isNetworkAvailable()) {
                    return null;
                }
                JSONObject response = api.post(APILibrecon.RESEND_EMAIL, jsonObj.toString());
                if (response != null && !response.has("errorCode")) {
                    return response;
                }
            } catch (JSONException jsEx) {
                jsEx.printStackTrace();
            } catch (IOException ex) {
                ex.printStackTrace();
            }
            return null;
        }

        private boolean isNetworkAvailable() {
            ConnectivityManager connectivityManager
                    = (ConnectivityManager) context.getSystemService(Context.CONNECTIVITY_SERVICE);
            NetworkInfo activeNetworkInfo = connectivityManager.getActiveNetworkInfo();
            return activeNetworkInfo != null && activeNetworkInfo.isConnected();
        }

        @Override
        protected void onPostExecute(Boolean aBool) {
            super.onPostExecute(aBool);
            mProgressBar.setVisibility(View.GONE);
            if (aBool) {
                Toast.makeText(getActivity(), R.string.http_200_resend_code, Toast.LENGTH_LONG).show();
                fadeOutAnimation(mResendTutorial);
                fadeOutAnimation(mFloatLabelEmailLayout);
                fadeInAnimation(mTutorial);
                fadeInAnimation(mFloatLabelLayout);
                mEnterButton.setText(getString(R.string.enter));
                mCancelButton.setText(getString(R.string.cancel));
            } else {
                Toast.makeText(getActivity(), R.string.http_500, Toast.LENGTH_LONG).show();
                mFloatLabelEmailLayout.setVisibility(View.VISIBLE);
            }
        }
    }

    @Override
    public void onAttach(Activity activity) {
        super.onAttach(activity);
        try {
            mListener = (OnFragmentInteractionListener) activity;
        } catch (ClassCastException e) {
            throw new ClassCastException(activity.toString()
                    + " must implement OnFragmentInteractionListener");
        }
    }

    @Override
    public void onDetach() {
        super.onDetach();
        mListener = null;
    }

    /**
     * This interface must be implemented by activities that contain this
     * fragment to allow an interaction in this fragment to be communicated
     * to the activity and potentially other fragments contained in that
     * activity.
     * <p/>
     * See the Android Training lesson <a href=
     * "http://developer.android.com/training/basics/fragments/communicating.html"
     * >Communicating with Other Fragments</a> for more information.
     */
    public interface OnFragmentInteractionListener {
        // TODO: Update argument type and name
        public void onFragmentInteraction(Uri uri);
    }

}

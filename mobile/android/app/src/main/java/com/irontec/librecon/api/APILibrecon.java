package com.irontec.librecon.api;

import android.util.Log;

import com.squareup.okhttp.MediaType;
import com.squareup.okhttp.OkHttpClient;
import com.squareup.okhttp.Request;
import com.squareup.okhttp.RequestBody;
import com.squareup.okhttp.Response;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.IOException;
import java.util.Date;
import java.util.concurrent.TimeUnit;

import okio.BufferedSource;
import okio.Timeout;

/**
 * Created by Asier Fernandez on 19/09/14.
 */
public class APILibrecon {

    private final static String TAG = APILibrecon.class.getSimpleName();

    private static APILibrecon INSTANCE;
    private OkHttpClient client;

    // MediaType
    public static final MediaType JSON = MediaType.parse("application/json; charset=utf-8");

    // BaseUrl & Endpoints
    private final static String BASE_URL = "https://mobile.librecon.io/secret/api/v1/";

    public final static String VERSION_PARAMETER = "?v=";
    public static String AUTH_CODE  = "auth/code";
    public static String RESEND_EMAIL  = "auth/mail";
    public static String SCHEDULES  = "schedules";
    public static String MEETINGS   = "meetings";
    public static String ASSISTANTS = "assistants";
    public static String EXPOSITORS = "expositors";
    public static String SPONSORS   = "sponsors";
    public static String TXOKOS     = "txokos";
    public static String PHOTO_CALL = "photocall";


    public static APILibrecon getInstance() {
        if (INSTANCE == null)
            INSTANCE = new APILibrecon();
        return INSTANCE;
    }

    protected APILibrecon() {
        client = new OkHttpClient();
        client.setConnectTimeout(10, TimeUnit.SECONDS);
        client.setWriteTimeout(10, TimeUnit.SECONDS);
        client.setReadTimeout(20, TimeUnit.SECONDS);
        Log.d(TAG, "getConnectTimeout: " + client.getConnectTimeout());
        Log.d(TAG, "getWriteTimeout: " + client.getWriteTimeout());
        Log.d(TAG, "getReadTimeout: " + client.getReadTimeout());
    }

    private String composeUrl(String endpoint) {
        return BASE_URL.concat(endpoint);
    }

    public JSONObject get(String endpoint) throws IOException, JSONException {
        return get("", endpoint);
    }

    public JSONObject get(String hash, String endpoint) throws IOException, JSONException {
        Date d1 = new Date();
        Request request;
        if (hash.isEmpty()) {
            request = getRequest(endpoint);
        } else {
            request = getRequestWithAuthorizationHeader(endpoint, hash);
        }
        Response response = client.newCall(request).execute();
        BufferedSource bufferedSource = response.body().source();
        Timeout timeout = bufferedSource.timeout();
        timeout.deadline(20, TimeUnit.SECONDS);
        String str = response.body().string();
        Log.d(TAG, "**********************************************************************************************");
        Log.d(TAG, "GET - " + composeUrl(endpoint) + " hash: " + hash);
        Log.d(TAG, "**********************************************************************************************");
        if (response.isSuccessful())
            return constructSuccesObj(str);
        else
            return constructErrorObj(response);
    }

    private Request getRequestWithAuthorizationHeader(String endpoint, String hash) {
        return new Request.Builder().url(composeUrl(endpoint)).addHeader("Authorization", hash).build();
    }

    private Request getRequest(String endpoint) {
        return new Request.Builder().url(composeUrl(endpoint)).build();
    }

    public JSONObject post(String endpoint, String json) throws IOException, JSONException {
        return post("", endpoint, json);
    }

    public JSONObject post(String hash, String endpoint, String json) throws IOException, JSONException {
        RequestBody body = RequestBody.create(JSON, json);
        Request request;
        if (hash.isEmpty()) {
            request = getPostRequest(endpoint, body);
        } else {
            request = getPostRequestWithAuthorizationHeader(endpoint, body, hash);
        }
        Response response = client.newCall(request).execute();
        BufferedSource bufferedSource = response.body().source();
        Timeout timeout = bufferedSource.timeout();
        timeout.deadline(20, TimeUnit.SECONDS);
        String str = response.body().string();
        Log.d(TAG, "**********************************************************************************************");
        Log.d(TAG, "POST - " + composeUrl(endpoint) + " hash: " + hash);
        Log.d(TAG, "DATA - " + json.toString());
        Log.d(TAG, "**********************************************************************************************");
        if (response.isSuccessful())
            return constructSuccesObj(str);
        else
            return constructErrorObj(response);
    }

    private Request getPostRequestWithAuthorizationHeader(String endpoint, RequestBody body, String hash) {
        return new Request.Builder().url(composeUrl(endpoint)).post(body).addHeader("Authorization", hash).build();
    }

    private Request getPostRequest(String endpoint, RequestBody body) {
        return new Request.Builder().url(composeUrl(endpoint)).post(body).build();
    }

    public JSONObject put(String hash, String endpoint, String json) throws IOException, JSONException {
        Log.d(TAG, "**********************************************************************************************");
        Log.d(TAG, "PUT - " + composeUrl(endpoint) + " hash: " + hash);
        Log.d(TAG, "DATA - " + json.toString());
        Log.d(TAG, "**********************************************************************************************");
        RequestBody body = RequestBody.create(JSON, json);
        Request request;
        if (hash.isEmpty()) {
            request = getPutRequest(endpoint, body);
        } else {
            request = getPutRequestWithAuthorizationHeader(endpoint, body, hash);
        }
        Response response = client.newCall(request).execute();
        String str = response.body().string();
        if (response.isSuccessful())
            return constructSuccesObj(str);
        else
            return constructErrorObj(response);
    }

    private Request getPutRequestWithAuthorizationHeader(String endpoint, RequestBody body, String hash) {
        return new Request.Builder().url(composeUrl(endpoint)).put(body).addHeader("Authorization", hash).build();
    }

    private Request getPutRequest(String endpoint, RequestBody body) {
        return new Request.Builder().url(composeUrl(endpoint)).put(body).build();
    }

    public JSONObject constructSuccesObj(String response) throws JSONException {
        Log.d(TAG, "SUCCESS - " + response);
        return new JSONObject(response);
    }

    public JSONObject constructErrorObj(Response response) throws JSONException {
        JSONObject jsonObject = new JSONObject();
        jsonObject.put("errorCode", response.code());
        jsonObject.put("errorMsg", response.message());
        Log.d(TAG, "ERROR - " + jsonObject.toString());
        return jsonObject;
    }
}

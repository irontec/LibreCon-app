package com.irontec.librecon.deserializers;

import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParseException;

import java.lang.reflect.Type;

import librecon.Meeting;

/**
 * Created by Asier Fernandez on 19/09/14.
 */
public class MeetingDeserializer implements JsonDeserializer<Meeting> {

    public MeetingDeserializer() {
    }

    @Override
    public Meeting deserialize(JsonElement json, Type typeOfT, JsonDeserializationContext context) throws JsonParseException {
        JsonObject jsonObj = (JsonObject) json;
        Meeting meeting = new Meeting();
        meeting.setId(jsonObj.get("id").getAsLong());
        meeting.setSendedByMe(jsonObj.get("sendedByMe").getAsBoolean());
        meeting.setCreatedAt(jsonObj.get("createdAt").getAsString());
        String status = jsonObj.get("status").getAsString();
        if (status.equals("pending")) {
            meeting.setStatus(1);
        } else if (status.equals("accepted")) {
            meeting.setStatus(2);
        } else {
            meeting.setStatus(3);
        }
        meeting.setEmailShare(jsonObj.get("emailShare").getAsBoolean());
        meeting.setCellphoneShare(jsonObj.get("cellphoneShare").getAsBoolean());
        meeting.setMoment(jsonObj.get("moment").getAsString());
        meeting.setResponseDate(jsonObj.get("responseDate").getAsString());
        return meeting;
    }
}

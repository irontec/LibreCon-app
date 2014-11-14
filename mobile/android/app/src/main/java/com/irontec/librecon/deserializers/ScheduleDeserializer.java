package com.irontec.librecon.deserializers;

import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParseException;

import java.lang.reflect.Type;

import librecon.Schedule;

/**
 * Created by Asier Fernandez on 19/09/14.
 */
public class ScheduleDeserializer implements JsonDeserializer<Schedule> {

    public ScheduleDeserializer() {
    }

    @Override
    public Schedule deserialize(JsonElement json, Type typeOfT, JsonDeserializationContext context) throws JsonParseException {
        JsonObject jsonObj = (JsonObject) json;
        Schedule schedule = new Schedule();
        schedule.setId(jsonObj.get("id").getAsLong());
        schedule.setNameEs(jsonObj.get("name_es").getAsString());
        schedule.setNameEu(jsonObj.get("name_eu").getAsString());
        schedule.setNameEn(jsonObj.get("name_en").getAsString());
        schedule.setDate(jsonObj.get("targetDate").getAsString());
        schedule.setDescriptionEs(jsonObj.get("description_es").getAsString());
        schedule.setDescriptionEu(jsonObj.get("description_eu").getAsString());
        schedule.setDescriptionEn(jsonObj.get("description_en").getAsString());
        schedule.setPicUrl(jsonObj.get("picUrl").getAsString());
        schedule.setPicUrlSquare(jsonObj.get("picUrlSquare").getAsString());
        schedule.setStartDatetime(jsonObj.get("startDateTime").getAsString());
        schedule.setFinishDatetime(jsonObj.get("finishDateTime").getAsString());
        String links = jsonObj.getAsJsonArray("links").toString();
        schedule.setLinks(links);
        String tags = jsonObj.getAsJsonArray("tags").toString();
        schedule.setTags(tags);
        schedule.setLocation(jsonObj.get("location").getAsString());
        schedule.setTargetDate(jsonObj.get("targetDate").getAsString());
        schedule.setColor(jsonObj.get("color").getAsString());
        return schedule;
    }
}

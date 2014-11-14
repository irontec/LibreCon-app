package com.irontec.librecon.deserializers;

import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParseException;

import java.lang.reflect.Type;

import librecon.Speaker;

/**
 * Created by Asier Fernandez on 19/09/14.
 */
public class SpeakerDeserializer implements JsonDeserializer<Speaker> {

    public SpeakerDeserializer() {
    }

    @Override
    public Speaker deserialize(JsonElement json, Type typeOfT, JsonDeserializationContext context) throws JsonParseException {
        JsonObject jsonObj = (JsonObject) json;
        Speaker speaker = new Speaker();
        speaker.setId(jsonObj.get("id").getAsLong());
        speaker.setName(jsonObj.get("name").getAsString());
        speaker.setDescriptionEs(jsonObj.get("description_es").getAsString());
        speaker.setDescriptionEu(jsonObj.get("description_eu").getAsString());
        speaker.setDescriptionEn(jsonObj.get("description_en").getAsString());
        speaker.setPicUrl(jsonObj.get("picUrl").getAsString());
        speaker.setPicUrlCircle(jsonObj.get("picUrlCircle").getAsString());
        speaker.setCompany(jsonObj.get("company").getAsString());
        String links = jsonObj.getAsJsonArray("links").toString();
        speaker.setLinks(links);
        String tags = jsonObj.getAsJsonArray("tags").toString();
        speaker.setTags(tags);
        return speaker;
    }
}

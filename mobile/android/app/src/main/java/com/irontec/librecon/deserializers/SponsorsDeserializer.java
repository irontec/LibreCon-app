package com.irontec.librecon.deserializers;

import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParseException;

import java.lang.reflect.Type;

import librecon.Sponsor;

/**
 * Created by Asier Fernandez on 19/09/14.
 */
public class SponsorsDeserializer implements JsonDeserializer<Sponsor> {

    public SponsorsDeserializer() {
    }

    @Override
    public Sponsor deserialize(JsonElement json, Type typeOfT, JsonDeserializationContext context) throws JsonParseException {
        JsonObject jsonObj = (JsonObject) json;
        Sponsor sponsor = new Sponsor();
        sponsor.setId(jsonObj.get("id").getAsLong());
        sponsor.setNameEs(jsonObj.get("name_es").getAsString());
        sponsor.setNameEn(jsonObj.get("name_en").getAsString());
        sponsor.setNameEu(jsonObj.get("name_eu").getAsString());
        sponsor.setPicUrl(jsonObj.get("picUrl").getAsString());
        sponsor.setUrl(jsonObj.get("url").getAsString());
        sponsor.setOrderField(jsonObj.get("orderField").getAsInt());
        return sponsor;
    }
}

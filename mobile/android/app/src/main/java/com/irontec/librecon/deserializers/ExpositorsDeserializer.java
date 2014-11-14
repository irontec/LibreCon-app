package com.irontec.librecon.deserializers;

import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParseException;

import java.lang.reflect.Type;

import librecon.Expositor;

/**
 * Created by Asier Fernandez on 19/09/14.
 */
public class ExpositorsDeserializer implements JsonDeserializer<Expositor> {

    public ExpositorsDeserializer() {
    }

    @Override
    public Expositor deserialize(JsonElement json, Type typeOfT, JsonDeserializationContext context) throws JsonParseException {
        JsonObject jsonObj = (JsonObject) json;
        Expositor expositor = new Expositor();
        expositor.setId(jsonObj.get("id").getAsLong());
        expositor.setCompany(jsonObj.get("companyName").getAsString());
        expositor.setDescriptionEs(jsonObj.get("description_es").getAsString());
        expositor.setDescriptionEu(jsonObj.get("description_eu").getAsString());
        expositor.setDescriptionEn(jsonObj.get("description_en").getAsString());
        expositor.setPicUrl(jsonObj.get("picUrl").getAsString());
        expositor.setOrderField(jsonObj.get("orderField").getAsInt());
        return expositor;
    }
}

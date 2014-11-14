package com.irontec.librecon.deserializers;

import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParseException;

import java.lang.reflect.Type;

import librecon.Txoko;

/**
 * Created by Asier Fernandez on 19/09/14.
 */
public class TxokosDeserializer implements JsonDeserializer<Txoko> {

    public TxokosDeserializer() {
    }

    @Override
    public Txoko deserialize(JsonElement json, Type typeOfT, JsonDeserializationContext context) throws JsonParseException {
        JsonObject jsonObj = (JsonObject) json;
        Txoko txoko = new Txoko();
        txoko.setId(jsonObj.get("id").getAsLong());
        txoko.setTitleEs(jsonObj.get("title_es").getAsString());
        txoko.setTextEu(jsonObj.get("title_eu").getAsString());
        txoko.setTextEn(jsonObj.get("title_en").getAsString());
        txoko.setTextEs(jsonObj.get("text_es").getAsString());
        txoko.setTextEu(jsonObj.get("text_eu").getAsString());
        txoko.setTextEn(jsonObj.get("text_en").getAsString());
        txoko.setPicUrl(jsonObj.get("picUrl").getAsString());
        txoko.setOrderField(jsonObj.get("orderField").getAsInt());
        return txoko;
    }
}

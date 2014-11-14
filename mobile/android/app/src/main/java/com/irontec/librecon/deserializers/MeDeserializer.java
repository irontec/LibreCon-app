package com.irontec.librecon.deserializers;

import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParseException;

import java.lang.reflect.Type;

import librecon.Me;

/**
 * Created by Asier Fernandez on 19/09/14.
 */
public class MeDeserializer implements JsonDeserializer<Me> {

    public MeDeserializer() {
    }

    @Override
    public Me deserialize(JsonElement json, Type typeOfT, JsonDeserializationContext context) throws JsonParseException {
        JsonObject jsonObj = (JsonObject) json;
        Me me = new Me();
        me.setId(jsonObj.get("id").getAsLong());
        me.setName(jsonObj.get("name").getAsString());
        me.setLastName(jsonObj.get("lastName").getAsString());
        me.setEmail(jsonObj.get("email").getAsString());
        me.setCellPhone(jsonObj.get("cellPhone").getAsString());
        me.setCompany(jsonObj.get("company").getAsString());
        me.setPosition(jsonObj.get("position").getAsString());
        me.setPicUrl(jsonObj.get("picUrl").getAsString());
        me.setPicUrlCircle(jsonObj.get("picUrlCircle").getAsString());
        me.setAddress(jsonObj.get("address").getAsString());
        me.setLocation(jsonObj.get("location").getAsString());
        me.setCountry(jsonObj.get("country").getAsString());
        me.setPostalCode(jsonObj.get("postalCode").getAsString());
        me.setHash(jsonObj.get("hash").getAsString());
        return me;
    }
}

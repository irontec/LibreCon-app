package com.irontec.librecon.deserializers;

import com.google.gson.JsonDeserializationContext;
import com.google.gson.JsonDeserializer;
import com.google.gson.JsonElement;
import com.google.gson.JsonObject;
import com.google.gson.JsonParseException;

import java.lang.reflect.Type;

import librecon.Assistant;

/**
 * Created by Asier Fernandez on 19/09/14.
 */
public class AssistantDeserializer implements JsonDeserializer<Assistant> {

    public AssistantDeserializer() {
    }

    @Override
    public Assistant deserialize(JsonElement json, Type typeOfT, JsonDeserializationContext context) throws JsonParseException {
        JsonObject jsonObj = (JsonObject) json;
        Assistant assistant = new Assistant();
        assistant.setId(jsonObj.get("id").getAsLong());
        assistant.setName(jsonObj.get("name").getAsString());
        assistant.setLastName(jsonObj.get("lastName").getAsString());
        assistant.setEmail(jsonObj.get("email").getAsString());
        assistant.setCellPhone(jsonObj.get("cellPhone").getAsString());
        assistant.setCompany(jsonObj.get("company").getAsString());
        assistant.setPosition(jsonObj.get("position").getAsString());
        assistant.setPicUrl(jsonObj.get("picUrl").getAsString());
        assistant.setPicUrlCircle(jsonObj.get("picUrlCircle").getAsString());
        assistant.setAddress(jsonObj.get("address").getAsString());
        assistant.setLocation(jsonObj.get("location").getAsString());
        assistant.setCountry(jsonObj.get("country").getAsString());
        assistant.setPostalCode(jsonObj.get("postalCode").getAsString());
        assistant.setInterests(jsonObj.get("interests").getAsString());
        return assistant;
    }
}

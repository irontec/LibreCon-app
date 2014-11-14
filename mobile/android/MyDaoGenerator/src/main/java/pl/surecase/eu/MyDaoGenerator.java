package pl.surecase.eu;

import de.greenrobot.daogenerator.DaoGenerator;
import de.greenrobot.daogenerator.Entity;
import de.greenrobot.daogenerator.Property;
import de.greenrobot.daogenerator.Schema;
import de.greenrobot.daogenerator.ToMany;

public class MyDaoGenerator {

    public static void main(String args[]) throws Exception {
        Schema schema = new Schema(19, "librecon");

        // Assistant
        Entity assistant = schema.addEntity("Assistant");
        assistant.addIdProperty();
        assistant.addStringProperty("name");
        assistant.addStringProperty("lastName");
        assistant.addStringProperty("email");
        assistant.addStringProperty("cellPhone");
        assistant.addStringProperty("company");
        assistant.addStringProperty("position");
        assistant.addStringProperty("picUrl");
        assistant.addStringProperty("picUrlCircle");
        assistant.addStringProperty("address");
        assistant.addStringProperty("location");
        assistant.addStringProperty("country");
        assistant.addStringProperty("postalCode");
        assistant.addStringProperty("interests");

        // Speaker
        Entity speaker = schema.addEntity("Speaker");
        speaker.addIdProperty();
        speaker.addStringProperty("name");
        speaker.addStringProperty("company");
        speaker.addStringProperty("position");
        speaker.addStringProperty("descriptionEs");
        speaker.addStringProperty("descriptionEu");
        speaker.addStringProperty("descriptionEn");
        speaker.addStringProperty("picUrl");
        speaker.addStringProperty("links");
        speaker.addStringProperty("tags");
        speaker.addStringProperty("picUrlCircle");


        // Schedule
        Entity schedule = schema.addEntity("Schedule");
        schedule.addIdProperty();
        schedule.addStringProperty("nameEs");
        schedule.addStringProperty("nameEu");
        schedule.addStringProperty("nameEn");
        schedule.addStringProperty("date");
        schedule.addStringProperty("descriptionEs");
        schedule.addStringProperty("descriptionEu");
        schedule.addStringProperty("descriptionEn");
        schedule.addStringProperty("picUrl");
        schedule.addStringProperty("picUrlSquare");
        schedule.addStringProperty("startDatetime");
        schedule.addStringProperty("finishDatetime");
        schedule.addStringProperty("links");
        schedule.addStringProperty("tags");
        schedule.addStringProperty("location");
        schedule.addStringProperty("targetDate");
        schedule.addStringProperty("color");

        // Rel Schedule-Speaker
        Entity scheduleSpeaker = schema.addEntity("ScheduleSpeaker");
        scheduleSpeaker.addIdProperty().autoincrement();
        scheduleSpeaker.addLongProperty("speakerId");
        scheduleSpeaker.addLongProperty("scheduleId");

        // Tags
        Entity tags = schema.addEntity("Tag");
        tags.addIdProperty();
        tags.addStringProperty("nameEs");
        tags.addStringProperty("nameEu");
        tags.addStringProperty("nameEn");
        tags.addStringProperty("color");

        // Sponsors
        Entity sponsor = schema.addEntity("Sponsor");
        sponsor.addIdProperty();
        sponsor.addStringProperty("nameEs");
        sponsor.addStringProperty("nameEu");
        sponsor.addStringProperty("nameEn");
        sponsor.addStringProperty("picUrl");
        sponsor.addStringProperty("url");
        sponsor.addIntProperty("orderField");

        // Expositors
        Entity expositor = schema.addEntity("Expositor");
        expositor.addIdProperty();
        expositor.addStringProperty("descriptionEs");
        expositor.addStringProperty("descriptionEu");
        expositor.addStringProperty("descriptionEn");
        expositor.addStringProperty("company");
        expositor.addStringProperty("picUrl");
        expositor.addStringProperty("url");
        expositor.addIntProperty("orderField");

        // Txokos
        Entity txoko = schema.addEntity("Txoko");
        txoko.addIdProperty();
        txoko.addStringProperty("titleEs");
        txoko.addStringProperty("titleEu");
        txoko.addStringProperty("titleEn");
        txoko.addStringProperty("textEs");
        txoko.addStringProperty("textEu");
        txoko.addStringProperty("textEn");
        txoko.addStringProperty("picUrl");
        txoko.addIntProperty("orderField");

        // LastModified
        Entity lastModified = schema.addEntity("LastModified");
        lastModified.addIdProperty();
        lastModified.addStringProperty("assistants");
        lastModified.addStringProperty("expositors");
        lastModified.addStringProperty("schedules");
        lastModified.addStringProperty("meetings");
        lastModified.addStringProperty("txokos");
        lastModified.addStringProperty("sponsors");

        //Me
        Entity me = schema.addEntity("Me");
        me.addIdProperty();
        me.addStringProperty("name");
        me.addStringProperty("lastName");
        me.addStringProperty("email");
        me.addStringProperty("cellPhone");
        me.addStringProperty("company");
        me.addStringProperty("position");
        me.addStringProperty("picUrl");
        me.addStringProperty("picUrlCircle");
        me.addStringProperty("address");
        me.addStringProperty("location");
        me.addStringProperty("country");
        me.addStringProperty("postalCode");
        me.addStringProperty("hash");

        // Meeting
        Entity meeting = schema.addEntity("Meeting");
        meeting.addIdProperty();
        meeting.addBooleanProperty("sendedByMe");
        meeting.addStringProperty("createdAt");
        meeting.addIntProperty("status");
        meeting.addBooleanProperty("emailShare");
        meeting.addBooleanProperty("cellphoneShare");
        meeting.addStringProperty("moment");
        meeting.addStringProperty("responseDate");

        // Rel Assistant-Meeting
        Entity assistantMeeting = schema.addEntity("AssistantMeeting");
        assistantMeeting.addIdProperty().autoincrement();
        assistantMeeting.addLongProperty("assistantId");
        assistantMeeting.addLongProperty("meetingId");

        new DaoGenerator().generateAll(schema, args[0]);
    }
}

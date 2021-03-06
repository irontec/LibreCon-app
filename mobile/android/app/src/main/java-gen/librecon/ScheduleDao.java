package librecon;

import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteStatement;

import de.greenrobot.dao.AbstractDao;
import de.greenrobot.dao.Property;
import de.greenrobot.dao.internal.DaoConfig;

import librecon.Schedule;

// THIS CODE IS GENERATED BY greenDAO, DO NOT EDIT.
/** 
 * DAO for table SCHEDULE.
*/
public class ScheduleDao extends AbstractDao<Schedule, Long> {

    public static final String TABLENAME = "SCHEDULE";

    /**
     * Properties of entity Schedule.<br/>
     * Can be used for QueryBuilder and for referencing column names.
    */
    public static class Properties {
        public final static Property Id = new Property(0, Long.class, "id", true, "_id");
        public final static Property NameEs = new Property(1, String.class, "nameEs", false, "NAME_ES");
        public final static Property NameEu = new Property(2, String.class, "nameEu", false, "NAME_EU");
        public final static Property NameEn = new Property(3, String.class, "nameEn", false, "NAME_EN");
        public final static Property Date = new Property(4, String.class, "date", false, "DATE");
        public final static Property DescriptionEs = new Property(5, String.class, "descriptionEs", false, "DESCRIPTION_ES");
        public final static Property DescriptionEu = new Property(6, String.class, "descriptionEu", false, "DESCRIPTION_EU");
        public final static Property DescriptionEn = new Property(7, String.class, "descriptionEn", false, "DESCRIPTION_EN");
        public final static Property PicUrl = new Property(8, String.class, "picUrl", false, "PIC_URL");
        public final static Property PicUrlSquare = new Property(9, String.class, "picUrlSquare", false, "PIC_URL_SQUARE");
        public final static Property StartDatetime = new Property(10, String.class, "startDatetime", false, "START_DATETIME");
        public final static Property FinishDatetime = new Property(11, String.class, "finishDatetime", false, "FINISH_DATETIME");
        public final static Property Links = new Property(12, String.class, "links", false, "LINKS");
        public final static Property Tags = new Property(13, String.class, "tags", false, "TAGS");
        public final static Property Location = new Property(14, String.class, "location", false, "LOCATION");
        public final static Property TargetDate = new Property(15, String.class, "targetDate", false, "TARGET_DATE");
        public final static Property Color = new Property(16, String.class, "color", false, "COLOR");
    };


    public ScheduleDao(DaoConfig config) {
        super(config);
    }
    
    public ScheduleDao(DaoConfig config, DaoSession daoSession) {
        super(config, daoSession);
    }

    /** Creates the underlying database table. */
    public static void createTable(SQLiteDatabase db, boolean ifNotExists) {
        String constraint = ifNotExists? "IF NOT EXISTS ": "";
        db.execSQL("CREATE TABLE " + constraint + "'SCHEDULE' (" + //
                "'_id' INTEGER PRIMARY KEY ," + // 0: id
                "'NAME_ES' TEXT," + // 1: nameEs
                "'NAME_EU' TEXT," + // 2: nameEu
                "'NAME_EN' TEXT," + // 3: nameEn
                "'DATE' TEXT," + // 4: date
                "'DESCRIPTION_ES' TEXT," + // 5: descriptionEs
                "'DESCRIPTION_EU' TEXT," + // 6: descriptionEu
                "'DESCRIPTION_EN' TEXT," + // 7: descriptionEn
                "'PIC_URL' TEXT," + // 8: picUrl
                "'PIC_URL_SQUARE' TEXT," + // 9: picUrlSquare
                "'START_DATETIME' TEXT," + // 10: startDatetime
                "'FINISH_DATETIME' TEXT," + // 11: finishDatetime
                "'LINKS' TEXT," + // 12: links
                "'TAGS' TEXT," + // 13: tags
                "'LOCATION' TEXT," + // 14: location
                "'TARGET_DATE' TEXT," + // 15: targetDate
                "'COLOR' TEXT);"); // 16: color
    }

    /** Drops the underlying database table. */
    public static void dropTable(SQLiteDatabase db, boolean ifExists) {
        String sql = "DROP TABLE " + (ifExists ? "IF EXISTS " : "") + "'SCHEDULE'";
        db.execSQL(sql);
    }

    /** @inheritdoc */
    @Override
    protected void bindValues(SQLiteStatement stmt, Schedule entity) {
        stmt.clearBindings();
 
        Long id = entity.getId();
        if (id != null) {
            stmt.bindLong(1, id);
        }
 
        String nameEs = entity.getNameEs();
        if (nameEs != null) {
            stmt.bindString(2, nameEs);
        }
 
        String nameEu = entity.getNameEu();
        if (nameEu != null) {
            stmt.bindString(3, nameEu);
        }
 
        String nameEn = entity.getNameEn();
        if (nameEn != null) {
            stmt.bindString(4, nameEn);
        }
 
        String date = entity.getDate();
        if (date != null) {
            stmt.bindString(5, date);
        }
 
        String descriptionEs = entity.getDescriptionEs();
        if (descriptionEs != null) {
            stmt.bindString(6, descriptionEs);
        }
 
        String descriptionEu = entity.getDescriptionEu();
        if (descriptionEu != null) {
            stmt.bindString(7, descriptionEu);
        }
 
        String descriptionEn = entity.getDescriptionEn();
        if (descriptionEn != null) {
            stmt.bindString(8, descriptionEn);
        }
 
        String picUrl = entity.getPicUrl();
        if (picUrl != null) {
            stmt.bindString(9, picUrl);
        }
 
        String picUrlSquare = entity.getPicUrlSquare();
        if (picUrlSquare != null) {
            stmt.bindString(10, picUrlSquare);
        }
 
        String startDatetime = entity.getStartDatetime();
        if (startDatetime != null) {
            stmt.bindString(11, startDatetime);
        }
 
        String finishDatetime = entity.getFinishDatetime();
        if (finishDatetime != null) {
            stmt.bindString(12, finishDatetime);
        }
 
        String links = entity.getLinks();
        if (links != null) {
            stmt.bindString(13, links);
        }
 
        String tags = entity.getTags();
        if (tags != null) {
            stmt.bindString(14, tags);
        }
 
        String location = entity.getLocation();
        if (location != null) {
            stmt.bindString(15, location);
        }
 
        String targetDate = entity.getTargetDate();
        if (targetDate != null) {
            stmt.bindString(16, targetDate);
        }
 
        String color = entity.getColor();
        if (color != null) {
            stmt.bindString(17, color);
        }
    }

    /** @inheritdoc */
    @Override
    public Long readKey(Cursor cursor, int offset) {
        return cursor.isNull(offset + 0) ? null : cursor.getLong(offset + 0);
    }    

    /** @inheritdoc */
    @Override
    public Schedule readEntity(Cursor cursor, int offset) {
        Schedule entity = new Schedule( //
            cursor.isNull(offset + 0) ? null : cursor.getLong(offset + 0), // id
            cursor.isNull(offset + 1) ? null : cursor.getString(offset + 1), // nameEs
            cursor.isNull(offset + 2) ? null : cursor.getString(offset + 2), // nameEu
            cursor.isNull(offset + 3) ? null : cursor.getString(offset + 3), // nameEn
            cursor.isNull(offset + 4) ? null : cursor.getString(offset + 4), // date
            cursor.isNull(offset + 5) ? null : cursor.getString(offset + 5), // descriptionEs
            cursor.isNull(offset + 6) ? null : cursor.getString(offset + 6), // descriptionEu
            cursor.isNull(offset + 7) ? null : cursor.getString(offset + 7), // descriptionEn
            cursor.isNull(offset + 8) ? null : cursor.getString(offset + 8), // picUrl
            cursor.isNull(offset + 9) ? null : cursor.getString(offset + 9), // picUrlSquare
            cursor.isNull(offset + 10) ? null : cursor.getString(offset + 10), // startDatetime
            cursor.isNull(offset + 11) ? null : cursor.getString(offset + 11), // finishDatetime
            cursor.isNull(offset + 12) ? null : cursor.getString(offset + 12), // links
            cursor.isNull(offset + 13) ? null : cursor.getString(offset + 13), // tags
            cursor.isNull(offset + 14) ? null : cursor.getString(offset + 14), // location
            cursor.isNull(offset + 15) ? null : cursor.getString(offset + 15), // targetDate
            cursor.isNull(offset + 16) ? null : cursor.getString(offset + 16) // color
        );
        return entity;
    }
     
    /** @inheritdoc */
    @Override
    public void readEntity(Cursor cursor, Schedule entity, int offset) {
        entity.setId(cursor.isNull(offset + 0) ? null : cursor.getLong(offset + 0));
        entity.setNameEs(cursor.isNull(offset + 1) ? null : cursor.getString(offset + 1));
        entity.setNameEu(cursor.isNull(offset + 2) ? null : cursor.getString(offset + 2));
        entity.setNameEn(cursor.isNull(offset + 3) ? null : cursor.getString(offset + 3));
        entity.setDate(cursor.isNull(offset + 4) ? null : cursor.getString(offset + 4));
        entity.setDescriptionEs(cursor.isNull(offset + 5) ? null : cursor.getString(offset + 5));
        entity.setDescriptionEu(cursor.isNull(offset + 6) ? null : cursor.getString(offset + 6));
        entity.setDescriptionEn(cursor.isNull(offset + 7) ? null : cursor.getString(offset + 7));
        entity.setPicUrl(cursor.isNull(offset + 8) ? null : cursor.getString(offset + 8));
        entity.setPicUrlSquare(cursor.isNull(offset + 9) ? null : cursor.getString(offset + 9));
        entity.setStartDatetime(cursor.isNull(offset + 10) ? null : cursor.getString(offset + 10));
        entity.setFinishDatetime(cursor.isNull(offset + 11) ? null : cursor.getString(offset + 11));
        entity.setLinks(cursor.isNull(offset + 12) ? null : cursor.getString(offset + 12));
        entity.setTags(cursor.isNull(offset + 13) ? null : cursor.getString(offset + 13));
        entity.setLocation(cursor.isNull(offset + 14) ? null : cursor.getString(offset + 14));
        entity.setTargetDate(cursor.isNull(offset + 15) ? null : cursor.getString(offset + 15));
        entity.setColor(cursor.isNull(offset + 16) ? null : cursor.getString(offset + 16));
     }
    
    /** @inheritdoc */
    @Override
    protected Long updateKeyAfterInsert(Schedule entity, long rowId) {
        entity.setId(rowId);
        return rowId;
    }
    
    /** @inheritdoc */
    @Override
    public Long getKey(Schedule entity) {
        if(entity != null) {
            return entity.getId();
        } else {
            return null;
        }
    }

    /** @inheritdoc */
    @Override    
    protected boolean isEntityUpdateable() {
        return true;
    }
    
}

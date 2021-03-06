package librecon;

import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteStatement;

import de.greenrobot.dao.AbstractDao;
import de.greenrobot.dao.Property;
import de.greenrobot.dao.internal.DaoConfig;

import librecon.Me;

// THIS CODE IS GENERATED BY greenDAO, DO NOT EDIT.
/** 
 * DAO for table ME.
*/
public class MeDao extends AbstractDao<Me, Long> {

    public static final String TABLENAME = "ME";

    /**
     * Properties of entity Me.<br/>
     * Can be used for QueryBuilder and for referencing column names.
    */
    public static class Properties {
        public final static Property Id = new Property(0, Long.class, "id", true, "_id");
        public final static Property Name = new Property(1, String.class, "name", false, "NAME");
        public final static Property LastName = new Property(2, String.class, "lastName", false, "LAST_NAME");
        public final static Property Email = new Property(3, String.class, "email", false, "EMAIL");
        public final static Property CellPhone = new Property(4, String.class, "cellPhone", false, "CELL_PHONE");
        public final static Property Company = new Property(5, String.class, "company", false, "COMPANY");
        public final static Property Position = new Property(6, String.class, "position", false, "POSITION");
        public final static Property PicUrl = new Property(7, String.class, "picUrl", false, "PIC_URL");
        public final static Property PicUrlCircle = new Property(8, String.class, "picUrlCircle", false, "PIC_URL_CIRCLE");
        public final static Property Address = new Property(9, String.class, "address", false, "ADDRESS");
        public final static Property Location = new Property(10, String.class, "location", false, "LOCATION");
        public final static Property Country = new Property(11, String.class, "country", false, "COUNTRY");
        public final static Property PostalCode = new Property(12, String.class, "postalCode", false, "POSTAL_CODE");
        public final static Property Hash = new Property(13, String.class, "hash", false, "HASH");
    };


    public MeDao(DaoConfig config) {
        super(config);
    }
    
    public MeDao(DaoConfig config, DaoSession daoSession) {
        super(config, daoSession);
    }

    /** Creates the underlying database table. */
    public static void createTable(SQLiteDatabase db, boolean ifNotExists) {
        String constraint = ifNotExists? "IF NOT EXISTS ": "";
        db.execSQL("CREATE TABLE " + constraint + "'ME' (" + //
                "'_id' INTEGER PRIMARY KEY ," + // 0: id
                "'NAME' TEXT," + // 1: name
                "'LAST_NAME' TEXT," + // 2: lastName
                "'EMAIL' TEXT," + // 3: email
                "'CELL_PHONE' TEXT," + // 4: cellPhone
                "'COMPANY' TEXT," + // 5: company
                "'POSITION' TEXT," + // 6: position
                "'PIC_URL' TEXT," + // 7: picUrl
                "'PIC_URL_CIRCLE' TEXT," + // 8: picUrlCircle
                "'ADDRESS' TEXT," + // 9: address
                "'LOCATION' TEXT," + // 10: location
                "'COUNTRY' TEXT," + // 11: country
                "'POSTAL_CODE' TEXT," + // 12: postalCode
                "'HASH' TEXT);"); // 13: hash
    }

    /** Drops the underlying database table. */
    public static void dropTable(SQLiteDatabase db, boolean ifExists) {
        String sql = "DROP TABLE " + (ifExists ? "IF EXISTS " : "") + "'ME'";
        db.execSQL(sql);
    }

    /** @inheritdoc */
    @Override
    protected void bindValues(SQLiteStatement stmt, Me entity) {
        stmt.clearBindings();
 
        Long id = entity.getId();
        if (id != null) {
            stmt.bindLong(1, id);
        }
 
        String name = entity.getName();
        if (name != null) {
            stmt.bindString(2, name);
        }
 
        String lastName = entity.getLastName();
        if (lastName != null) {
            stmt.bindString(3, lastName);
        }
 
        String email = entity.getEmail();
        if (email != null) {
            stmt.bindString(4, email);
        }
 
        String cellPhone = entity.getCellPhone();
        if (cellPhone != null) {
            stmt.bindString(5, cellPhone);
        }
 
        String company = entity.getCompany();
        if (company != null) {
            stmt.bindString(6, company);
        }
 
        String position = entity.getPosition();
        if (position != null) {
            stmt.bindString(7, position);
        }
 
        String picUrl = entity.getPicUrl();
        if (picUrl != null) {
            stmt.bindString(8, picUrl);
        }
 
        String picUrlCircle = entity.getPicUrlCircle();
        if (picUrlCircle != null) {
            stmt.bindString(9, picUrlCircle);
        }
 
        String address = entity.getAddress();
        if (address != null) {
            stmt.bindString(10, address);
        }
 
        String location = entity.getLocation();
        if (location != null) {
            stmt.bindString(11, location);
        }
 
        String country = entity.getCountry();
        if (country != null) {
            stmt.bindString(12, country);
        }
 
        String postalCode = entity.getPostalCode();
        if (postalCode != null) {
            stmt.bindString(13, postalCode);
        }
 
        String hash = entity.getHash();
        if (hash != null) {
            stmt.bindString(14, hash);
        }
    }

    /** @inheritdoc */
    @Override
    public Long readKey(Cursor cursor, int offset) {
        return cursor.isNull(offset + 0) ? null : cursor.getLong(offset + 0);
    }    

    /** @inheritdoc */
    @Override
    public Me readEntity(Cursor cursor, int offset) {
        Me entity = new Me( //
            cursor.isNull(offset + 0) ? null : cursor.getLong(offset + 0), // id
            cursor.isNull(offset + 1) ? null : cursor.getString(offset + 1), // name
            cursor.isNull(offset + 2) ? null : cursor.getString(offset + 2), // lastName
            cursor.isNull(offset + 3) ? null : cursor.getString(offset + 3), // email
            cursor.isNull(offset + 4) ? null : cursor.getString(offset + 4), // cellPhone
            cursor.isNull(offset + 5) ? null : cursor.getString(offset + 5), // company
            cursor.isNull(offset + 6) ? null : cursor.getString(offset + 6), // position
            cursor.isNull(offset + 7) ? null : cursor.getString(offset + 7), // picUrl
            cursor.isNull(offset + 8) ? null : cursor.getString(offset + 8), // picUrlCircle
            cursor.isNull(offset + 9) ? null : cursor.getString(offset + 9), // address
            cursor.isNull(offset + 10) ? null : cursor.getString(offset + 10), // location
            cursor.isNull(offset + 11) ? null : cursor.getString(offset + 11), // country
            cursor.isNull(offset + 12) ? null : cursor.getString(offset + 12), // postalCode
            cursor.isNull(offset + 13) ? null : cursor.getString(offset + 13) // hash
        );
        return entity;
    }
     
    /** @inheritdoc */
    @Override
    public void readEntity(Cursor cursor, Me entity, int offset) {
        entity.setId(cursor.isNull(offset + 0) ? null : cursor.getLong(offset + 0));
        entity.setName(cursor.isNull(offset + 1) ? null : cursor.getString(offset + 1));
        entity.setLastName(cursor.isNull(offset + 2) ? null : cursor.getString(offset + 2));
        entity.setEmail(cursor.isNull(offset + 3) ? null : cursor.getString(offset + 3));
        entity.setCellPhone(cursor.isNull(offset + 4) ? null : cursor.getString(offset + 4));
        entity.setCompany(cursor.isNull(offset + 5) ? null : cursor.getString(offset + 5));
        entity.setPosition(cursor.isNull(offset + 6) ? null : cursor.getString(offset + 6));
        entity.setPicUrl(cursor.isNull(offset + 7) ? null : cursor.getString(offset + 7));
        entity.setPicUrlCircle(cursor.isNull(offset + 8) ? null : cursor.getString(offset + 8));
        entity.setAddress(cursor.isNull(offset + 9) ? null : cursor.getString(offset + 9));
        entity.setLocation(cursor.isNull(offset + 10) ? null : cursor.getString(offset + 10));
        entity.setCountry(cursor.isNull(offset + 11) ? null : cursor.getString(offset + 11));
        entity.setPostalCode(cursor.isNull(offset + 12) ? null : cursor.getString(offset + 12));
        entity.setHash(cursor.isNull(offset + 13) ? null : cursor.getString(offset + 13));
     }
    
    /** @inheritdoc */
    @Override
    protected Long updateKeyAfterInsert(Me entity, long rowId) {
        entity.setId(rowId);
        return rowId;
    }
    
    /** @inheritdoc */
    @Override
    public Long getKey(Me entity) {
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

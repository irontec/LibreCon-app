alter table Assistants add device varchar(15) not null default 'android' COMMENT '[enum:android|ios]';

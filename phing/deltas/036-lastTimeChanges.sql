
alter table Expositor add orderField mediumint unsigned not null default 0 after companyName;

alter table Assistants add mailOne tinyint unsigned not null default 0;

alter table Assistants add mailTwo mediumint unsigned not null default 0;

alter table Assistants add mailRemember mediumint unsigned not null default 0;

 
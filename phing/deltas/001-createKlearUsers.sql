CREATE TABLE `KlearUsers` (
  `userId` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `login` varchar(40) NOT NULL,
  `email` varchar(255) NOT NULL,
  `pass` varchar(80) NOT NULL COMMENT '[password]',
  `active` tinyint(1) DEFAULT '1',
  `createdOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `login` (`login`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='[entity]';
insert into KlearUsers (login, pass, active) values ('admin','$2a$08$O.hiS1UIlYag9BMoHe8Gj.47ljX9Ip1tkHBEVDlAQUkp.Ka/8zBkO', 1);
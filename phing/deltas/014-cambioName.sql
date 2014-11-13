ALTER TABLE `RelScheduleSpeaker` DROP FOREIGN KEY `RelScheduleSpeaker_ibfk_1`;

ALTER TABLE `RelScheduleSpeaker` CHANGE `idScheduele` `idSchedule` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `RelScheduleSpeaker` ADD FOREIGN KEY (`idSchedule`) REFERENCES `Schedule`(`id`) ON DELETE SET NULL ON UPDATE SET NULL;
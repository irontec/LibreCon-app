ALTER TABLE `Schedule` ADD `iconBaseName` VARCHAR(255) COMMENT '' AFTER `icon`;
ALTER TABLE `Schedule` ADD `iconMimeType` VARCHAR(80) COMMENT '' AFTER `icon`;
ALTER TABLE `Schedule` ADD `iconFileSize` INT(11) UNSIGNED COMMENT '[FSO]' AFTER `icon`;
ALTER TABLE `Schedule` DROP icon;

ALTER TABLE `Assistants` ADD `iconBaseName` VARCHAR(255) COMMENT '' AFTER `icon`;
ALTER TABLE `Assistants` ADD `iconMimeType` VARCHAR(80) COMMENT '' AFTER `icon`;
ALTER TABLE `Assistants` ADD `iconFileSize` INT(11) UNSIGNED COMMENT '[FSO]' AFTER `icon`;
ALTER TABLE `Assistants` DROP icon;
ALTER TABLE `Speaker` ADD `iconBaseName` VARCHAR(255) COMMENT '' AFTER `icon`;
ALTER TABLE `Speaker` ADD `iconMimeType` VARCHAR(80) COMMENT '' AFTER `icon`;
ALTER TABLE `Speaker` ADD `iconFileSize` INT(11) UNSIGNED COMMENT '[FSO]' AFTER `icon`;
ALTER TABLE `Speaker` DROP icon;

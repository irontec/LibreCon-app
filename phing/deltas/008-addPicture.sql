ALTER TABLE `Assistants` ADD `pictureBaseName` VARCHAR(255) COMMENT '' AFTER `picture`;
ALTER TABLE `Assistants` ADD `pictureMimeType` VARCHAR(80) COMMENT '' AFTER `picture`;
ALTER TABLE `Assistants` ADD `pictureFileSize` INT(11) UNSIGNED COMMENT '[FSO]' AFTER `picture`;
ALTER TABLE `Assistants` DROP picture;

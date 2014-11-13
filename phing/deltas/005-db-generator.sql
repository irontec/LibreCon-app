ALTER TABLE `Sponsors` ADD `logoBaseName` VARCHAR(255) COMMENT '' AFTER `logo`;
ALTER TABLE `Sponsors` ADD `logoMimeType` VARCHAR(80) COMMENT '' AFTER `logo`;
ALTER TABLE `Sponsors` ADD `logoFileSize` INT(11) UNSIGNED COMMENT '[FSO]' AFTER `logo`;
ALTER TABLE `Sponsors` DROP logo;

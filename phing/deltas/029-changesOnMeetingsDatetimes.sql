ALTER TABLE `Meeting` DROP atRightNowWhen;
ALTER TABLE `Meeting` DROP atHalfHourWhen;
ALTER TABLE `Meeting` DROP atOneHourWhen;
ALTER TABLE `Meeting` ADD responseDate datetime DEFAULT NULL;

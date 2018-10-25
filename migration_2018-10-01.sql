ALTER TABLE `message` ADD `notifyImage` VARCHAR(256) NOT NULL AFTER `body`;
ALTER TABLE `message_log` ADD `sent` TINYINT(1) NOT NULL DEFAULT '0' AFTER `isActive`;
ALTER TABLE `message` ADD `receivers` VARCHAR(256) NOT NULL DEFAULT 'all' COMMENT 'values will be segmented or all' AFTER `completed`;

#runthis
ALTER TABLE `apps_users_group_mc` ADD `groupDescription` TEXT NULL AFTER `userGroupName`;
ALTER TABLE `apps_users_group`  ADD `groupDescription` TEXT NULL  AFTER `userGroupName`;
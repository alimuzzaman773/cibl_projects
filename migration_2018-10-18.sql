
/**
 * Author:  shahid
 * Created: Oct 20, 2018
 */
ALTER TABLE `admin_users_group` CHANGE `permissions` `permissions` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;
ALTER TABLE `admin_users_group` CHANGE `machine_name` `machine_name` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;

ALTER TABLE `admin_users_group_mc` ADD `machine_name` VARCHAR(255) NULL AFTER `permissions`;
ALTER TABLE `admin_users_group_mc` CHANGE `permissions` `permissions` LONGTEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL;


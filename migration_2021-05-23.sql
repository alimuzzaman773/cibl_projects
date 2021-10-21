
/**
 * Author:  shahid
 * Created: May 23, 2021
 */

ALTER TABLE `admin_users` ADD `adUserName` VARCHAR(100) NULL AFTER `checkerActionBy`;
ALTER TABLE `admin_users_mc` ADD `adUserName` VARCHAR(100) NULL AFTER `checkerActionBy`;

CREATE TABLE `card_transaction_gls` (
    `ctgId` INT(11) NOT NULL AUTO_INCREMENT,
    `binNumber` VARCHAR(10) NULL DEFAULT NULL,
    `glAccountNo` VARCHAR(20) NULL DEFAULT NULL,
    `feeAccountNo` VARCHAR(20) NULL DEFAULT NULL,
    `vatAccountNo` VARCHAR(20) NULL DEFAULT NULL,
    `createdBy` INT(11) NULL,
    `created` DATETIME NULL,
    `updated` DATETIME NULL,
    PRIMARY KEY (`ctgId`)
) COLLATE='utf8_general_ci';

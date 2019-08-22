/**
 * Author:  shahid
 * Created: Aug 21, 2019
 */

ALTER TABLE `apps_users` ADD `isOwnAccTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `userName2`, ADD `isInterAccTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isOwnAccTransfer`, ADD `isOtherAccTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isInterAccTransfer`, ADD `isAccToCardTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isOtherAccTransfer`, ADD `isCardToAccTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isAccToCardTransfer`, ADD `isUtilityTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isCardToAccTransfer`, ADD `isQrPayment` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isUtilityTransfer`;

ALTER TABLE `apps_users_mc` ADD `isOwnAccTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `userName2`, ADD `isInterAccTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isOwnAccTransfer`, ADD `isOtherAccTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isInterAccTransfer`, ADD `isAccToCardTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isOtherAccTransfer`, ADD `isCardToAccTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isAccToCardTransfer`, ADD `isUtilityTransfer` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isCardToAccTransfer`, ADD `isQrPayment` TINYINT(1) NOT NULL DEFAULT '1' AFTER `isUtilityTransfer`;
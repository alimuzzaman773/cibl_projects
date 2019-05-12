ALTER TABLE `discount_partners` ADD `isActive` TINYINT(1) NOT NULL DEFAULT '1' AFTER `DiscountUploadImage`;
ALTER TABLE `zip_partners` ADD `isActive` TINYINT(1) NOT NULL DEFAULT '1' AFTER `banner`;

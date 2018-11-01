ALTER TABLE `api_services` ADD `vat_applicable` TINYINT(1) NOT NULL DEFAULT '0' AFTER `api_url`, ADD `vat_account` VARCHAR(256) NULL DEFAULT NULL AFTER `vat_applicable`, ADD `tax_applicable` TINYINT(1) NOT NULL DEFAULT '0' AFTER `vat_account`, ADD `tax_account` VARCHAR(256) NULL DEFAULT NULL AFTER `tax_applicable`;
ALTER TABLE `api_services` ADD `logo` VARCHAR(256) NULL DEFAULT NULL AFTER `api_service_id`;
ALTER TABLE `api_service_fields` CHANGE `field_type` `field_type` ENUM('dropdown','text','text_area','date','account_number','card_number') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text';
ALTER TABLE `api_service_fields` ADD `ordering` INT(3) NOT NULL DEFAULT '0' AFTER `is_required`;

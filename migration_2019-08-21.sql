ALTER TABLE `apps_users_group` ADD `ccMinTxnLim` DECIMAL(10) NOT NULL AFTER `obtEffectiveData`, ADD `ccMaxTxnLim` DECIMAL(10) NOT NULL AFTER `ccMinTxnLim`, ADD `ccDayTxnLim` DECIMAL(10) NOT NULL AFTER `ccMaxTxnLim`, ADD `ccNoOfTxn` INT NOT NULL AFTER `ccDayTxnLim`, ADD `ccEffectiveDate` DATE NOT NULL AFTER `ccNoOfTxn`, ADD `ccEffectiveData` VARCHAR(200) NOT NULL AFTER `ccEffectiveDate`;
ALTER TABLE `apps_users_group_mc` ADD `ccMinTxnLim` DECIMAL(10) NOT NULL AFTER `obtEffectiveData`, ADD `ccMaxTxnLim` DECIMAL(10) NOT NULL AFTER `ccMinTxnLim`, ADD `ccDayTxnLim` DECIMAL(10) NOT NULL AFTER `ccMaxTxnLim`, ADD `ccNoOfTxn` INT NOT NULL AFTER `ccDayTxnLim`, ADD `ccEffectiveDate` DATE NOT NULL AFTER `ccNoOfTxn`, ADD `ccEffectiveData` VARCHAR(200) NOT NULL AFTER `ccEffectiveDate`

ALTER TABLE `apps_users_group_mc` CHANGE `ccMinTxnLim` `ccMinTxnLim` DECIMAL(10,2) NOT NULL;
ALTER TABLE `apps_users_group_mc` CHANGE `ccMaxTxnLim` `ccMaxTxnLim` DECIMAL(10,2) NOT NULL;
ALTER TABLE `apps_users_group_mc` CHANGE `ccDayTxnLim` `ccDayTxnLim` DECIMAL(10,2) NOT NULL;


ALTER TABLE `apps_users_group` CHANGE `ccMinTxnLim` `ccMinTxnLim` DECIMAL(10,2) NOT NULL;
ALTER TABLE `apps_users_group` CHANGE `ccMaxTxnLim` `ccMaxTxnLim` DECIMAL(10,2) NOT NULL;
ALTER TABLE `apps_users_group` CHANGE `ccDayTxnLim` `ccDayTxnLim` DECIMAL(10,2) NOT NULL;
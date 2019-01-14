/**
 * Author:  shahid
 * Created: Jan 6, 2019
 */

ALTER TABLE `apps_users_group` ADD `globalLimit` DECIMAL(11,2) NOT NULL DEFAULT '0.00' AFTER `obtEffectiveData`;
ALTER TABLE `apps_users_group_mc` ADD `globalLimit` DECIMAL(11,2) NOT NULL DEFAULT '0.00' AFTER `obtEffectiveData`

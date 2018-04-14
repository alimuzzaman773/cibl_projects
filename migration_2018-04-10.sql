
/**
 * Author:  Shahid
 * Created: Apr 10, 2018
 */


CREATE TABLE `permission` (
  `permissionId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `groups` enum('Apps User Module','Device Module','Pin Module','Admin User Module','Admin User Group Module','Limit Package Module','Routing Number Module','Biller Setup Module','Bill Type Setup Module','Password Policy') NOT NULL,
  `updateDtTm` datetime NOT NULL,
  `creationDtTm` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `permission`
  ADD PRIMARY KEY (`permissionId`);

ALTER TABLE `permission`
  MODIFY `permissionId` int(11) NOT NULL AUTO_INCREMENT;

/*admin_users_group*/
ALTER TABLE `admin_users_group` ADD `permissions` LONGTEXT NOT NULL AFTER `checkerActionBy`, ADD `machine_name` VARCHAR(20) NOT NULL AFTER `permissions`;
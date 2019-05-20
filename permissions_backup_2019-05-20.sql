-- phpMyAdmin SQL Dump
-- version 4.8.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 20, 2019 at 10:48 AM
-- Server version: 10.1.33-MariaDB
-- PHP Version: 7.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_ibanking_pbl_live`
--

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `permissionId` int(11) NOT NULL,
  `name` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `category` enum('Apps User Module','Device Module','Pin Module','Admin User Module','Admin User Group Module','Limit Package Module','Routing Number Module','Biller Setup Module','Bill Type Setup Module','Password Policy','Content Setup Module','Service Request Module','Navigation','Call Center') CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `route` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `updateDtTm` datetime NOT NULL,
  `creationDtTm` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permissionId`, `name`, `description`, `category`, `route`, `updateDtTm`, `creationDtTm`) VALUES
(1, 'canViewAppUser', 'Control app user view functionality', 'Apps User Module', 'client_registration/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(2, 'can add app user', 'Control app user add functionality', 'Apps User Module', 'apps_users/addAppsUser/Add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(3, 'canEditAppUser', 'Control app user edit functionality', 'Apps User Module', 'apps_users/editAppsUser', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(4, 'canViewAppUserDevice', 'Control app user device view functionality', 'Apps User Module', 'client_registration/deviceInfo', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(5, 'canAddAppUser', 'Control app user device add functionality', 'Apps User Module', 'client_registration/addDeviceInfo', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(6, 'canActiveAppUser', 'Control app user active functionality', 'Apps User Module', 'client_registration/userActive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(7, 'canInactiveAppUser', 'Control app user inactive functionality', 'Apps User Module', 'client_registration/userInactive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(8, 'canLockAppUser', 'Control app user lock functionality', 'Apps User Module', 'client_registration/userLock', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(9, 'canUnlockAppUser', 'Control app user unlock functionality', 'Apps User Module', 'client_registration/userUnlock', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(10, 'canActiveAppUserDevice', 'Control app user device active functionality', 'Apps User Module', 'client_registration/deviceActive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(11, 'canInactiveAppUserDevice', 'Control app user device inactive functionality', 'Apps User Module', 'client_registration/deviceInactive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(12, 'canLockAppUserDevice', 'Control app user device lock functionality', 'Apps User Module', 'client_registration/deviceLock', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(13, 'canUnlockAppUserDevice', 'Control app user device unlock functionality', 'Apps User Module', 'client_registration/deviceUnlock', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(14, 'canDeleteAppUser', 'Control app user delete functionality', 'Apps User Module', 'client_registration/userDelete', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(15, 'canViewAdminUsersMaker', 'Control admin user view functionality', 'Admin User Module', 'admin_users_maker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(16, 'canAddAdminUsersMaker', 'Control admin user add functionality', 'Admin User Module', 'admin_users_maker/addNewUser/Add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(17, 'canViewLimitPackage', 'canViewLimitPackage', 'Limit Package Module', 'transaction_limit_setup_maker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(18, 'canEditAdminUsersMaker', 'Control admin user edit functionality', 'Admin User Module', 'admin_users_maker/editUser/', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(19, 'canAddLimitPackage', 'canAddLimitPackage', 'Limit Package Module', 'transaction_limit_setup_maker/createGroup/Add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(20, 'canActiveAdminUsersMaker', 'Control admin user active functionality', 'Admin User Module', 'admin_users_maker/adminUserActive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(21, 'canEditLimitPackage', 'canEditLimitPackage', 'Limit Package Module', 'transaction_limit_setup_maker/editTransactionLimitPackage', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(22, 'canInactiveAdminUsersMaker', 'Control admin user inactive functionality', 'Admin User Module', 'admin_users_maker/adminUserInactive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(23, 'canLockAdminUsersMaker', 'Control admin user lock functionality', 'Admin User Module', 'admin_users_maker/adminUserLock', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(24, 'canUnloockAdminUsersMaker', 'Control admin user unlock functionality', 'Admin User Module', 'admin_users_maker/adminUserUnlock', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(25, 'canViewAdminUserGroup', 'Control admin user group view functionality', 'Admin User Group Module', 'admin_user_group_maker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(26, 'canAddAdminUserGroup', 'Control admin user group add functionality', 'Admin User Group Module', 'admin_user_group_maker/selectModule/Add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(27, 'canEditAdminUserGroup', 'Control admin user group edit functionality', 'Admin User Group Module', 'admin_user_group_maker/editModule', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(28, 'canActiveAdminUserGroup', 'Control admin user group active functionality', 'Admin User Group Module', 'admin_user_group_maker/groupActive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(29, 'canInactiveAdminUserGroup', 'Control admin user group Inactive functionality', 'Admin User Group Module', 'admin_user_group_maker/groupInactive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(30, 'canLockAdminUserGroup', 'Control admin user group lock functionality', 'Admin User Group Module', 'admin_user_group_maker/groupLock', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(31, 'canUnlockAdminUserGroup', 'Control admin user group unlock functionality', 'Admin User Group Module', 'admin_user_group_maker/groupUnlock', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(32, 'canEditLimitPackgae', 'edit limit package module', 'Limit Package Module', 'transaction_limit_setup_maker/editTransactionLimitPackage', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(33, 'canActiveLimitPackgae', 'Active Limit Package Module', 'Limit Package Module', 'transaction_limit_setup_maker/packageActive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(34, 'canInactiveLimitPackgae', 'Inactive Transaction Limit Package', 'Limit Package Module', 'transaction_limit_setup_maker/packageActive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(35, 'canAddBillType', 'Add Bill Type Setup', 'Bill Type Setup Module', 'bill_type_setup/index/add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(36, 'canEditBillType', 'Edit Bill Type Setup', 'Bill Type Setup Module', 'bill_type_setup/index/edit/', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(40, 'canAddRoutingNumber', 'Add Routing Number', 'Routing Number Module', 'routing_number/index/add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(41, 'canEditRoutingNumber', 'Edit Routing Number', 'Routing Number Module', 'routing_number/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(42, 'canViewRoutingNumber', 'canViewRoutingNumber', 'Routing Number Module', 'routing_number/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(45, 'canAddBillerSetup', 'Add Biller Setup', 'Biller Setup Module', 'biller_setup_maker/addNewBiller/Add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(46, 'canEditBillerSetup', 'Edit Biller Setup', 'Biller Setup Module', 'biller_setup_maker/editBiller', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(47, 'canActiveBillerSetup', 'Active Biller Setup', 'Biller Setup Module', 'biller_setup_maker/billerActive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(48, 'canAddAppUserDevice', 'Control app user device view functionality', 'Apps User Module', 'client_registration/addDeviceInfo', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(49, 'canInactiveBillerSetup', 'Biller Setup Inactive', 'Biller Setup Module', 'biller_setup_maker/billerInactive', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(50, 'canEditAppUserDevice', 'Control app user device view functionality', 'Apps User Module', 'client_registration/editDevice', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(51, 'canEditPasswordPolicy', 'Edit Password Policy', 'Password Policy', 'validation_setup/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(52, 'canEmailBankingRequest', 'Email Banking Request', 'Service Request Module', 'banking_service_request/processRequestById', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(53, 'canEmailProductRequest', 'Email Product Request', 'Service Request Module', 'product_request_process/processRequestById', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(54, 'canEmailPriorityRequest', 'Email Priority Request', 'Service Request Module', 'priority_request_process/processRequestById', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(55, 'canAddProduct', 'Add Product', 'Content Setup Module', 'product_setup/index/add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(56, 'canEditProduct', 'Edit Product', 'Content Setup Module', 'product_setup/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(57, 'canCreatePin', 'Control pin functionality', 'Pin Module', 'pin_generation/newRequest/Create', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(59, 'canCreateNewRquestPin', 'Control pin functionality', 'Pin Module', 'pin_generation/newRequest/Create', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(61, 'canViewProduct', 'canViewProduct', 'Content Setup Module', 'product_setup/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(62, 'canDeleteAppsUser', 'Delete Apps User', 'Apps User Module', 'apps_user_delete_checker/getAppsUserForApproval', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(63, 'canApprovePasswordPolicy', 'Password Policy Approve', 'Password Policy', 'password_policy_checker/getPasswordPolicyApproval', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(64, 'canApprovePinReset', 'Approve PIN Reset', 'Pin Module', 'pin_generation_checker/getResetActionForApproval', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(65, 'canApproveBillerSetup', 'Approve Biller Setup', 'Biller Setup Module', 'biller_setup_checker/getBillerFroApproval', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(66, 'canApproveDevice', 'Approve Device Information', 'Device Module', 'device_info_checker/getDeviceForApproval', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(67, 'canAddLocatorSetup', 'Control Locator Functionality', 'Content Setup Module', 'locator_setup/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(68, 'canApproveAppsUser', 'Approve Apps User', 'Apps User Module', 'client_registration_checker/getAppsUserForApproval', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(69, 'canEditLocatorSetup', 'Control Locator Functionality', 'Content Setup Module', 'locator_setup/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(73, 'canAddZipPartner', 'Control Zip Partner Add Functionality', 'Content Setup Module', 'zip_partners/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(74, 'canEditZipPartner', 'Control Zip Partner Edit Functionality', 'Content Setup Module', 'zip_partners/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(75, 'canViewZipPartner', 'Control Zip Partner View Functionality', 'Content Setup Module', 'zip_partners/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(76, 'canViewLocatorSetup', 'Control Locator Functionality', 'Content Setup Module', 'locator_setup/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(77, 'canAddPriorityProduct', 'Control Priority Product Add Functionality', 'Content Setup Module', 'priority_products/index/add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(78, 'canEditPriorityProduct', 'Control Priority Product Edit Functionality', 'Content Setup Module', 'priority_products/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(79, 'canViewPriorityProduct', 'Control Priority Product View Functionality', 'Content Setup Module', 'priority_products/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(80, 'canDeletePriorityProduct', 'Control Priority Product Delete Functionality', 'Content Setup Module', 'priority_products/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(81, 'canApproveLimitPackage', 'Approve Limit Package', 'Limit Package Module', 'transaction_limit_setup_checker/getPackageForApproval', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(82, 'canViewDiscountPartners', 'Control Discount Partners View Functionality', 'Content Setup Module', 'discount_partners/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(83, 'canAddDiscountPartner', 'Control Discount Partners Add Functionality', 'Content Setup Module', 'discount_partners/index/add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(84, 'canEditDiscountPartner', 'Control Discount Partners Edit Functionality', 'Content Setup Module', 'discount_partners/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(85, 'canViewNewsEvents', 'Control News Events View Functionality', 'Content Setup Module', 'news_events/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(86, 'canAddNewsEvenet', 'Control News Events Add Functionality', 'Content Setup Module', 'news_events/index/add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(87, 'canEditNewsEvenet', 'Control News Events Edit Functionality', 'Content Setup Module', 'news_events/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(88, 'canApproveAdminUser', 'Add Admin User', 'Admin User Module', 'admin_users_checker/getUserForApproval', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(89, 'canApproveAdminUserGroup', 'Approve Admin User Group', 'Admin User Group Module', 'admin_user_group_checker/getGroupForApproval', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(90, 'canViewAdvertisement', 'Control Advertisement View Functionality', 'Content Setup Module', 'advertisement/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(91, 'canDeleteAdvertisement', 'Control Advertisement DeleteFunctionality', 'Content Setup Module', 'advertisement/index/delete', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(92, 'canAddAdvertisement', 'Control Advertisement Add Functionality', 'Content Setup Module', 'advertisement/index/add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(93, 'canEditAdvertisement', 'Control Advertisement Edit Functionality', 'Content Setup Module', 'advertisement/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(94, 'canEditHelpSetup', 'Control Help Setup Edit Functionality', 'Content Setup Module', 'help_setup/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(95, 'canAddHelpSetup', 'Control Help Setup Add Functionality', 'Content Setup Module', 'help_setup/index/add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(96, 'canViewHelpSetup', 'Control Help Setup View Functionality', 'Content Setup Module', 'help_setup/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(97, 'canViewLimitPackgaeMenu', 'View Transaction Limit Packgae', 'Navigation', 'transaction_limit_setup_maker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(98, 'canViewBillTypeSetupMenu', 'View Bill Type Setup', 'Navigation', 'bill_type_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(99, 'canViewRoutingNumberMenu', 'View Routing Number', 'Navigation', 'routing_number', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(100, 'canViewBillerSetupMenu', 'View Biller Setup', 'Navigation', 'biller_setup_maker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(101, 'canViewPasswordPolicyMenu', 'View Password Policy', 'Navigation', 'validation_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(102, 'canViewProductRequestMenu', 'View Product Menu', 'Navigation', 'product_request_process/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(103, 'canViewAppsUserDeleteAuthorizationMenu', 'View Apps User Delete Menu', 'Navigation', 'apps_user_delete_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(104, 'canViewPasswordPolicyAuthorizationMenu', 'View Password Policy Menu', 'Navigation', 'password_policy_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(105, 'canViewPinResetAuthorizationMenu', 'View PIN reset Authorization', 'Navigation', 'pin_generation_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(106, 'canViewBillerSetupAuthorizationMenu', 'View Biller Setup Authorization Menu', 'Navigation', 'biller_setup_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(107, 'canViewProductsSetupMenu', 'canViewProductsSetupMenu', 'Navigation', 'product_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(108, 'canViewDeviceAuthorizationMenu', 'View Device Authorization Menu', 'Navigation', 'device_info_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(109, 'canViewLocationSetupMenu', 'canViewLocationSetupMenu', 'Navigation', 'locator_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(110, 'canViewZipPartnersSetupMenu', 'can View Zip Partners Setup Menu', 'Navigation', 'zip_partners', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(111, 'canViewAppsUserAuthorizationMenu', 'View Apps User Authorization Menu', 'Navigation', 'client_registration_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(112, 'canViewPrioritySetupMenu', 'canViewPrioritySetupMenu', 'Navigation', 'priority_products', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(113, 'canViewBenefitsSetupMenu', 'canViewBenefitsSetupMenu', 'Navigation', 'discount_partners', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(114, 'canViewLimitSetupAuthorizationMenu', 'View Limit Setup Authorization Menu', 'Navigation', 'transaction_limit_setup_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(115, 'canViewNewsAndEventsSetupMenu', 'canViewNewsAndEventsSetupMenu', 'Navigation', 'news_events', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(116, 'canViewNotificationSetupMenu', 'canViewNotificationSetupMenu', 'Navigation', 'push_notification', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(117, 'canViewAdminUserAuthorizationMenu', 'View Admin User Authorization Menu', 'Navigation', 'admin_users_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(118, 'canViewAdvertisementSetupMenu', 'canViewAdvertisementSetupMenu', 'Navigation', 'advertisement', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(119, 'canViewHelpSetupMenu', 'canViewHelpSetupMenu', 'Navigation', 'help_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(120, 'canViewAdminUserGroupAuthorizationMenu', 'View Admin User Group Authorization Menu', 'Navigation', 'admin_user_group_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(121, 'canViewPinCreateMenu', 'View Pin Create Menu', 'Navigation', 'pin_generation/viewPinByAction', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(122, 'canViewAppsUserCreateMenu', 'View Apps User Create Menu', 'Navigation', 'client_registration/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(123, 'canViewAdminUserGroupCreateMenu', 'View Admin User Group Create Menu', 'Navigation', 'admin_user_group_maker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(124, 'canViewAdminUserCreateMenu', 'View Admin User Create Menu', 'Navigation', 'admin_users_maker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(125, 'canViewPermission', 'Control  permission view functionality', 'Admin User Group Module', 'permission', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(126, 'canViewContentSetupMenu', 'View Content Setup Menu', 'Navigation', 'n/a', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(127, 'canSetuPermissionAdminUserGroup', 'Control admin user group permission setup functionality', 'Admin User Group Module', 'admin_user_group_maker/set_permission/', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(128, 'canViewAdminSetupMenu', 'View Admin Setup Menu', 'Navigation', 'n/a', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(129, 'canDeleteProductCategories', 'Delete Product Categories', 'Content Setup Module', 'product_setup/categories/index/delete', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(130, 'canViewNotificationSetup', 'canViewNotificationSetup', 'Navigation', 'push_notification', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(131, 'canViewComplaintInfo', 'Control ComplaintInfo Setup View Functionality', 'Content Setup Module', 'complaintInfo/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(132, 'canEditComplaintInfo', 'Control ComplaintInfo Setup Edit Functionality', 'Content Setup Module', 'complaintInfo/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(133, 'canAddComplaintInfo', 'Control ComplaintInfo Setup Add Functionality', 'Content Setup Module', 'complaintInfo/index/add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(134, 'canDeleteComplaintInfo', 'Control ComplaintInfo Setup Delete Functionality', 'Content Setup Module', 'complaintInfo/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(135, 'canViewBankingRequestMenu', 'canViewBankingRequestMenu', 'Navigation', 'banking_service_request/getRequests', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(136, 'canViewPin', 'View Pin', 'Navigation', 'pin_generation/viewPinByAction', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(137, 'canViewBillerSetup', 'View Biller Setup', 'Navigation', 'biller_setup_maker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(138, 'canViewPriorityRequest', 'View Priority Request', 'Service Request Module', 'priority_request_process/getRequests', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(139, 'canViewProductRequest', 'View Product Request', 'Service Request Module', 'product_request_process/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(140, 'canAddAdminUserGroupAuthorization', 'Add Admin User Group Authorization', 'Admin User Group Module', 'admin_user_group_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(141, 'canViewAdminUserGroupAuthorization', 'View Admin User Group Authorization', 'Admin User Group Module', 'admin_user_group_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(142, 'canViewBankingRequest', 'canViewBankingRequest', 'Navigation', 'banking_service_request/getRequests', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(143, 'canViewAdminUserAuthorization', 'View Admin User Authorization', 'Admin User Module', 'admin_users_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(144, 'canViewLimitSetupAuthorization', 'View Limit Setup Authorization', 'Limit Package Module', 'transaction_limit_setup_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(145, 'canViewAppsUserAuthorization', 'View Apps User Authorization', 'Apps User Module', 'client_registration_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(146, 'canViewDeviceAuthorization', 'View Device Authorization', 'Device Module', 'device_info_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(147, 'canViewBillerSetupAuthorization', 'View Biller Setup Authorization', 'Biller Setup Module', 'biller_setup_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(148, 'canViewPinResetAuthorization', 'View PIN reset Authorization', 'Pin Module', 'pin_generation_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(149, 'canViewPasswordPolicyAuthorization', 'View Password Policy', 'Password Policy', 'password_policy_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(150, 'canViewAppsUserDeleteAuthorization', 'View Apps User Delete', 'Apps User Module', 'apps_user_delete_checker', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(151, 'canViewPermissionMenu', 'Control  permission view functionality', 'Navigation', 'permission', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(152, 'canDeleteProduct', 'Delete Product', 'Content Setup Module', 'product_setup/index/delete', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(153, 'canDeleteLocatorSetup', 'Control Locator Functionality', 'Content Setup Module', 'locator_setup/index/delete', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(154, 'canDeleteZipPartner', 'Control Zip Partner Delete Functionality', 'Content Setup Module', 'zip_partners/index/delete', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(155, 'canDeleteDiscountPartner', 'Control Discount Partners Add Functionality', 'Content Setup Module', 'discount_partners/index/delete', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(156, 'canDeleteNewsEvenet', 'Control News Events Add Functionality', 'Content Setup Module', 'news_events/index/delete', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(157, 'canAddProductCategories', 'Add Product Categories', 'Content Setup Module', 'product_setup/categories/index/add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(158, 'canEditProductCategories', 'Edit Product Categories', 'Content Setup Module', 'product_setup/categories/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(159, 'canViewProductCategories', 'View Product Categories', 'Content Setup Module', 'product_setup/categories/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(160, 'canViewReportMenu', 'View Report Menu', 'Navigation', 'n/a', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(161, 'canViewChatbotMenu', 'View Chatbot Menu', 'Navigation', 'n/a', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(162, 'canViewProduct', 'View Product', 'Content Setup Module', 'product_setup/index', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(163, 'canViewPasswordPolicySetup', 'canViewPasswordPolicySetup', 'Password Policy', '/password_policy_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(164, 'canViewPasswordPolicy', 'canViewPasswordPolicy', 'Password Policy', '/validation_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(165, 'canViewBillType', 'canViewBillType', 'Bill Type Setup Module', '/bill_type_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(166, 'canViewNotification', 'canViewNotification', 'Content Setup Module', '/notification', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(167, 'canAddNotification', 'canAddNotification', 'Content Setup Module', '/notification/index/add', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(168, 'canEditNotification', 'canEditNotification', 'Content Setup Module', '/notification/index/edit', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(169, 'canDeleteNotification', 'canDeleteNotification', 'Content Setup Module', '/notification/index/delete', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(170, 'canViewMakerMenu', 'canViewMakerMenu', 'Navigation', 'admin_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(171, 'canViewCheckerMenu', 'canViewCheckerMenu', 'Navigation', 'admin_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(172, 'canViewRequestProcessMenu', 'canViewRequestProcessMenu', 'Navigation', 'admin_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(173, 'canViewConfigurationMenu', 'canViewConfigurationMenu', 'Navigation', 'admin_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(174, 'canViewApplicationSettingsMenu', 'canViewApplicationSettingsMenu', 'Navigation', 'admin_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(175, 'canViewLogMenu', 'canViewLogMenu', 'Navigation', 'admin_setup', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(176, 'canViewCallCenterMenu', 'canViewCallCenterMenu', 'Navigation', 'call_center', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(177, 'canViewTransactionListMenu', 'canViewTransactionListMenu', 'Navigation', 'transaction_list', '2019-04-21 15:57:51', '2019-04-21 15:57:51'),
(178, 'canViewCallCenterAppsUser', 'canViewCallCenterAppsUser', 'Call Center', 'call_center/#/user_list', '2018-12-19 16:44:51', '2018-12-19 16:44:51'),
(179, 'canViewCallCenterAccount', 'canViewCallCenterAccount', 'Call Center', 'call_center/#/request_account', '2018-12-19 16:45:27', '2018-12-19 16:45:27'),
(180, 'callCenterMaker', 'callCenterMaker', 'Call Center', 'call_center/#/user_approve', '2018-12-20 20:06:25', '0000-00-00 00:00:00'),
(181, 'callCenterChecker', 'callCenterChecker', 'Call Center', 'call_center/#/user_approve', '2018-12-20 20:09:13', '0000-00-00 00:00:00'),
(182, 'canAddNotificationUsers', 'canAddNotificationUsers', 'Content Setup Module', '/notification/user', '2019-04-21 18:29:32', '0000-00-00 00:00:00'),
(183, 'canViewFiles', 'Control Files View Functionality', 'Content Setup Module', 'files/index', '2019-05-15 13:52:22', '2019-05-15 13:52:22'),
(184, 'canAddFiles', 'Can Add Files Functionality', 'Content Setup Module', 'files/index/add', '2019-05-15 13:58:11', '2019-05-15 13:53:38'),
(185, 'canEditFiles', 'can Edit Files Functionality', 'Content Setup Module', 'files/index/edit', '2019-05-15 13:58:25', '2019-05-15 13:54:46'),
(186, 'canDeleteFiles', 'Can Delete Files Functionality', 'Content Setup Module', 'files/index/delete', '2019-05-15 13:58:39', '2019-05-15 13:57:04'),
(187, 'canViewFilesMenu', 'View Files Menu', 'Navigation', 'files', '2019-05-15 14:03:56', '2019-05-15 14:03:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permissionId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permissionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

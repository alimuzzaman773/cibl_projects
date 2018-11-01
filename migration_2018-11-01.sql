-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2018 at 03:54 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pbl_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_service_fields`
--

CREATE TABLE `api_service_fields` (
  `field_id` int(11) NOT NULL,
  `api_service_id` int(11) NOT NULL,
  `label` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `field_name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `field_type` enum('dropdown','text','text_area','date','account_number','card_number') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'text',
  `field_format` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `data` longtext COLLATE utf8_unicode_ci,
  `field_type_cast` enum('string','integer','float','date') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'string',
  `is_required` int(1) NOT NULL DEFAULT '1',
  `service_type` enum('bill-info','bill-payment') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'bill-info',
  `ordering` int(3) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `api_service_fields`
--

INSERT INTO `api_service_fields` (`field_id`, `api_service_id`, `label`, `field_name`, `field_type`, `field_format`, `data`, `field_type_cast`, `is_required`, `service_type`, `ordering`, `created`, `updated`) VALUES
(1, 1, 'Exchange Code', 'exchange_code', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-10-15 15:37:26', '2018-10-29 13:52:49'),
(2, 1, 'Phone No', 'phone_number', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-10-15 15:38:38', '2018-10-29 13:53:24'),
(3, 1, 'Last Payment Date', 'last_payment_date', 'date', 'yyyy-mm-dd', NULL, 'date', 1, 'bill-info', 0, '2018-10-15 16:55:45', '2018-10-29 13:56:11'),
(4, 1, 'Remarks', 'remarks', 'text_area', NULL, NULL, 'string', 0, 'bill-info', 0, '2018-10-29 13:56:42', '2018-10-29 13:56:42'),
(5, 1, 'Branch Code', 'branch_code', 'text', NULL, NULL, 'integer', 1, 'bill-info', 0, '2018-10-29 13:57:21', '2018-10-29 13:57:21'),
(6, 1, 'BTCL Bill Status', 'btcl_bill_status', 'dropdown', NULL, 'R|\"R\"=Regular Bill\nP2|\"P2\"=Part2 Bill\nRB|\"RB\"=Revised Bill\nI|\"I\"=Instalment Bill\nO|\"O\"=Other Bill\nFB|\"FB\"=First Bill\nCO|\"CO\"=Ceiling Based\nCP|\"CP\"=Ceiling Based Duplicate\nA|\"A\"=Adjustment Bill\nFL|\"FL\"=Final/Closed Bill\nFI|\"FI\"=Final/Closed Bill', 'string', 1, 'bill-info', 0, '2018-10-29 13:58:38', '2018-10-29 13:58:38'),
(7, 2, 'Utility Bill No', 'billno', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-10-31 18:28:12', '2018-10-31 18:28:12'),
(8, 3, 'Location Code', 'location_code', 'dropdown', NULL, 'A1|A1\nA2|A2\nA3|A3\nA4|A4\nA5|A5\nA6|A6\nA7|A7\nA8|A8\nA9|A9\nB1|B1\nB2|B2\nB3|B3\nB4|B4\nB5|B5\nB6|B6\nB7|B7\nB8|B8\nB9|B9\nC1|C1\nC2|C2\nC3|C3\nC6|C6\nD1|D1\nD2|D2\nD3|D3\nD4|D4\nD5|D5\nD6|D6\nD8|D8\nD9|D9\nE1|E1\nE2|E2\nE3|E3\nF1|F1\nF2|F2\nG1|G1', 'string', 1, 'bill-info', 0, '2018-10-31 18:38:17', '2018-10-31 19:05:00'),
(9, 3, 'Bill Year', 'bill_years', 'dropdown', NULL, '2014|2014\n2015|2015\n2016|2016\n2017|2017\n2018|2018', 'integer', 1, 'bill-info', 0, '2018-10-31 18:42:02', '2018-10-31 19:18:05'),
(10, 3, 'Bill Month', 'bill_months', 'dropdown', NULL, '01|Jan\n02|Feb\n03|Mar\n04|Apr\n05|May\n06|Jun\n07|Jul\n08|Aug\n09|Sep\n10|Oct\n11|Nov\n11|Dec', 'integer', 1, 'bill-info', 0, '2018-10-31 18:50:39', '2018-10-31 19:18:17'),
(11, 3, 'Account Number', 'account_number', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-10-31 19:19:08', '2018-10-31 19:19:08'),
(12, 4, 'Customer', 'customer', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 15:04:27', '2018-11-01 15:04:27'),
(13, 4, 'Amount', 'amount', 'text', NULL, NULL, 'float', 1, 'bill-info', 0, '2018-11-01 15:05:18', '2018-11-01 15:05:18'),
(14, 4, 'Particulars', 'particulars', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 15:05:52', '2018-11-01 15:05:52'),
(15, 4, 'Surcharge', 'surcharge', 'text', NULL, NULL, 'float', 1, 'bill-info', 0, '2018-11-01 16:11:50', '2018-11-01 16:11:50'),
(16, 7, 'Bill NO', 'billno', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 16:49:50', '2018-11-01 17:43:37'),
(17, 11, 'Recipient MSISDN', 'recipient_msisdn', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 16:52:55', '2018-11-01 17:16:57'),
(18, 11, 'Amount', 'amount', 'text', NULL, NULL, 'float', 1, 'bill-info', 0, '2018-11-01 16:55:51', '2018-11-01 16:55:51'),
(19, 11, 'Connection Type', 'connection_type', 'dropdown', NULL, 'prepaid|Prepaid\npostpaid|Postpaid', 'string', 1, 'bill-info', 0, '2018-11-01 16:56:29', '2018-11-01 17:27:43'),
(20, 7, 'Account NO', 'account_no', 'text', NULL, NULL, 'integer', 1, 'bill-info', 0, '2018-11-01 17:11:33', '2018-11-01 17:44:49'),
(21, 6, 'Customer', 'customer', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 17:12:13', '2018-11-01 17:46:45'),
(22, 6, 'Amount', 'amount', 'text', NULL, NULL, 'integer', 1, 'bill-info', 0, '2018-11-01 17:13:55', '2018-11-01 17:47:59'),
(23, 6, 'Subcharge', 'surcharge', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 17:15:17', '2018-11-01 17:48:37'),
(24, 6, 'Particulars', 'particulars', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 17:15:47', '2018-11-01 17:49:42'),
(25, 10, 'IVAC ID', 'ivac_id', 'text', NULL, NULL, 'integer', 1, 'bill-info', 0, '2018-11-01 17:17:55', '2018-11-01 17:17:55'),
(26, 5, 'Invoice NO', 'invoiceNo', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 17:57:47', '2018-11-01 17:57:47'),
(27, 5, 'Customer Code', 'customerCode', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:00:49', '2018-11-01 18:00:49'),
(28, 5, 'Source Tax Amount', 'sourceTaxAmount', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:01:40', '2018-11-01 18:01:40'),
(29, 5, 'Branch Code', 'branchCode', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:02:27', '2018-11-01 18:02:27'),
(30, 5, 'Chalan NO', 'chalanNo', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:02:59', '2018-11-01 18:02:59'),
(31, 5, 'Chalan Date', 'chalanDate', 'date', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:03:29', '2018-11-01 18:03:29'),
(32, 5, 'Chalan Bank', 'chalanBank', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:04:10', '2018-11-01 18:04:10'),
(33, 5, 'Chalan Branch', 'chalanBranch', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:04:52', '2018-11-01 18:04:52'),
(34, 8, 'Invoice ID', 'invoice_id', 'text', NULL, NULL, 'integer', 1, 'bill-info', 0, '2018-11-01 18:12:33', '2018-11-01 18:12:33'),
(35, 8, 'Utility User', 'utility_user', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:13:59', '2018-11-01 18:13:59'),
(36, 8, 'Utility Pass', 'utility_pass', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:14:33', '2018-11-01 18:14:33'),
(37, 8, 'Service', 'service', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:15:03', '2018-11-01 18:15:03'),
(38, 9, 'Invoice ID', 'invoice_id', 'text', NULL, NULL, 'integer', 1, 'bill-info', 0, '2018-11-01 18:16:01', '2018-11-01 18:16:01'),
(39, 9, 'Time Stamp', 'timestamp', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:16:29', '2018-11-01 18:16:29'),
(40, 9, 'Authentication Key', 'auth_key', 'text', NULL, NULL, 'integer', 1, 'bill-info', 0, '2018-11-01 18:19:05', '2018-11-01 18:19:05'),
(41, 9, 'Bank Details', 'bank_details', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:19:35', '2018-11-01 18:19:35'),
(42, 9, 'Request', 'request', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:20:00', '2018-11-01 18:20:00'),
(43, 9, 'Daily Serial NO', 'daily_serial_no', 'text', NULL, NULL, 'integer', 1, 'bill-info', 0, '2018-11-01 18:20:26', '2018-11-01 18:20:26'),
(44, 9, 'Amount', 'amount', 'text', NULL, NULL, 'integer', 1, 'bill-info', 0, '2018-11-01 18:21:06', '2018-11-01 18:21:06'),
(45, 10, 'Web File ID', 'webfile_id', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:21:51', '2018-11-01 18:21:51'),
(46, 10, 'Passport NO', 'passport_no', 'text', NULL, NULL, 'integer', 1, 'bill-info', 0, '2018-11-01 18:22:23', '2018-11-01 18:22:23'),
(47, 10, 'Appoint Type', 'appoint_type', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:23:00', '2018-11-01 18:23:00'),
(48, 10, 'Appoint Date', 'appoint_date', 'date', NULL, NULL, 'date', 1, 'bill-info', 0, '2018-11-01 18:24:02', '2018-11-01 18:24:02'),
(49, 10, 'Mobile NO', 'mobile_no', 'text', NULL, NULL, 'integer', 1, 'bill-info', 0, '2018-11-01 18:24:38', '2018-11-01 18:24:38'),
(50, 10, 'Email Address', 'email_address', 'text', NULL, NULL, 'string', 1, 'bill-info', 0, '2018-11-01 18:25:06', '2018-11-01 18:25:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_service_fields`
--
ALTER TABLE `api_service_fields`
  ADD PRIMARY KEY (`field_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_service_fields`
--
ALTER TABLE `api_service_fields`
  MODIFY `field_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;




-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2018 at 03:53 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pbl_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_services`
--

CREATE TABLE `api_services` (
  `api_service_id` int(11) NOT NULL,
  `logo` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `machine_name` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `method` enum('get','post') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'post',
  `api_url` text COLLATE utf8_unicode_ci NOT NULL,
  `vat_applicable` tinyint(1) NOT NULL DEFAULT '0',
  `vat_account` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tax_applicable` tinyint(1) NOT NULL DEFAULT '0',
  `tax_account` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `config_data` text COLLATE utf8_unicode_ci,
  `is_active` int(1) NOT NULL DEFAULT '0',
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `api_services`
--

INSERT INTO `api_services` (`api_service_id`, `logo`, `name`, `machine_name`, `method`, `api_url`, `vat_applicable`, `vat_account`, `tax_applicable`, `tax_account`, `config_data`, `is_active`, `created`, `updated`) VALUES
(1, '', 'BTCL', 'btcl', 'post', 'http://api.sslwireless.com/api/bill-payment', 0, '123456', 1, '123456', '[{\"type\":\"header\",\"key\":\"AUTH-KEY\",\"val\":\"BD6pFSIfSOLEIgKyru67MeBhICkRiFla\"},{\"type\":\"header\",\"key\":\"STK-CODE\",\"val\":\"DEMO\"},{\"type\":\"body\",\"key\":\"utility_auth_key\",\"val\":\"BT15174601406729\"},{\"type\":\"body\",\"key\":\"utility_secret_key\",\"val\":\"8QGXH93GHOEOQECX\"}]', 0, '2018-10-15 14:39:46', '2018-10-29 14:38:27'),
(2, NULL, 'DESCO', 'desco', 'post', 'http://api.sslwireless.com/api/bill-payment', 0, '1234', 0, '1234', '[{\"type\":\"body\",\"key\":\"utility_auth_key\",\"val\":\"DE15147853658738\"},{\"type\":\"body\",\"key\":\"utility_secret_key\",\"val\":\"McjteFIIuM5RIhjt\"},{\"type\":\"header\",\"key\":\"STK-CODE\",\"val\":\"DEMO\"},{\"type\":\"header\",\"key\":\"AUTH-KEY\",\"val\":\"BD6pFSIfSOLEIgKyru67MeBhICkRiFla\"}]', 0, '2018-10-31 18:09:43', '2018-10-31 18:44:41'),
(3, NULL, 'DPDC', 'dpdc', 'post', 'http://api.sslwireless.com/api/bill-payment', 0, '2233', 0, '2233', '[{\"type\":\"body\",\"key\":\"utility_auth_key\",\"val\":\"DP15174601225360\"},{\"type\":\"body\",\"key\":\"utility_secret_key\",\"val\":\"9QlwtE7M4i93HdJt\"}]', 0, '2018-10-31 18:28:13', '2018-10-31 18:37:37'),
(4, NULL, 'TITAS-NON-METERED', 'titas-non-metered', 'post', 'http://api.sslwireless.com/api/bill-payment', 0, '123456', 0, '123456', '[{\"type\":\"header\",\"key\":\"AUTH-KEY\",\"val\":\"BD6pFSIfSOLEIgKyru67MeBhICkRiFla\"},{\"type\":\"header\",\"key\":\"STK-CODE\",\"val\":\"DEMO\"},{\"type\":\"body\",\"key\":\"utility_auth_key\",\"val\":\"TI15108145974407\"},{\"type\":\"body\",\"key\":\"utility_secret_key\",\"val\":\"txLjDbBTRaqjEPV1\"},{\"type\":\"body\",\"key\":\"utility_bill_type\",\"val\":\"NON-METERED\"}]', 0, '2018-11-01 14:55:35', '2018-11-01 16:51:36'),
(5, NULL, 'TITAS-METERED', 'titas-metered', 'post', 'http://api.sslwireless.com/api/bill-payment', 1, '123456', 1, '123456', '[{\"type\":\"header\",\"key\":\"utility_bill_type\",\"val\":\"METERED\"},{\"type\":\"header\",\"key\":\"utility_auth_key\",\"val\":\"TI15108145467546\"},{\"type\":\"\",\"key\":\"utility_secret_key\",\"val\":\"TMs3SezZ\\/OGlVdp6\"}]', 0, '2018-11-01 15:25:19', '2018-11-01 15:32:08'),
(6, NULL, 'TITAS-DEMAND-NOTE', 'titas-demand-note', 'post', 'http://api.sslwireless.com/api/bill-payment', 1, '123456', 1, '123456', '[{\"type\":\"header\",\"key\":\"utility_bill_type\",\"val\":\"DEMAND-NOTE\"},{\"type\":\"header\",\"key\":\"utility_auth_key\",\"val\":\"TI15108145974407\"},{\"type\":\"header\",\"key\":\"utility_secret_key\",\"val\":\"KcydQdN0DssANqdo\"}]', 0, '2018-11-01 15:35:43', '2018-11-01 15:41:59'),
(7, NULL, 'WASA', 'wasa', 'post', 'http://api.sslwireless.com/api/bill-payment', 1, '123456', 1, '123456', '[{\"type\":\"header\",\"key\":\"utility_auth_key\",\"val\":\"TI15108146472886\"},{\"type\":\"header\",\"key\":\"utility_secret_key\",\"val\":\"KcydQdN0DssANqdo\"}]', 0, '2018-11-01 15:39:05', '2018-11-01 18:35:14'),
(8, NULL, 'BUFT', 'buft', 'post', 'http://api.sslwireless.com/api/bill-payment', 1, '123456', 1, '123456', '[{\"type\":\"header\",\"key\":\"utility_auth_key\",\"val\":\"BU15202276054830\"},{\"type\":\"header\",\"key\":\"utility_secret_key\",\"val\":\"mrf4w5BxX0eOaY1z\"}]', 0, '2018-11-01 15:43:34', '2018-11-01 18:35:23'),
(9, NULL, 'OIS', 'ois', 'post', 'http://api.sslwireless.com/api/bill-payment', 1, '123456', 1, '123456', '[{\"type\":\"header\",\"key\":\"utility_auth_key\",\"val\":\"OI15202276840286\"},{\"type\":\"\",\"key\":\"utility_secret_key\",\"val\":\"2zjpN8fUTCUdtPqp\"}]', 0, '2018-11-01 15:46:10', '2018-11-01 18:35:03'),
(10, NULL, 'IVAC', 'ivac', 'post', 'http://api.sslwireless.com/api/bill-payment', 1, '123456', 1, '123456', '[{\"type\":\"header\",\"key\":\"utility_auth_key\",\"val\":\"IV15202284900784\"},{\"type\":\"header\",\"key\":\"utility_secret_key\",\"val\":\"0UML5iFiqFWkvR76\"}]', 0, '2018-11-01 16:38:38', '2018-11-01 18:34:51'),
(11, NULL, 'TOP-UP', 'top_up', 'post', 'http://api.sslwireless.com/api/bill-payment', 1, '123456', 1, '123456', '[{\"type\":\"body\",\"key\":\"utility_auth_key\",\"val\":\"TI15108146472886\"},{\"type\":\"body\",\"key\":\"utility_secret_key\",\"val\":\"KcydQdN0DssANqdo\"}]', 0, '2018-11-01 16:42:16', '2018-11-01 20:42:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `api_services`
--
ALTER TABLE `api_services`
  ADD PRIMARY KEY (`api_service_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `api_services`
--
ALTER TABLE `api_services`
  MODIFY `api_service_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;





-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 01, 2018 at 03:54 PM
-- Server version: 10.1.25-MariaDB
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pbl_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `ssl_bill_payment`
--

CREATE TABLE `ssl_bill_payment` (
  `payment_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `bill_response` text,
  `payment_response` text,
  `created` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ssl_bill_payment`
--

INSERT INTO `ssl_bill_payment` (`payment_id`, `service_id`, `bill_response`, `payment_response`, `created`, `updated`) VALUES
(1, 3, NULL, NULL, '2018-11-01 16:08:55', NULL),
(2, 4, NULL, NULL, '2018-11-01 16:23:42', NULL),
(3, 4, NULL, NULL, '2018-11-01 16:28:13', NULL),
(4, 4, NULL, NULL, '2018-11-01 16:30:29', NULL),
(5, 4, NULL, NULL, '2018-11-01 16:31:26', NULL),
(6, 4, NULL, NULL, '2018-11-01 16:33:47', NULL),
(7, 4, NULL, NULL, '2018-11-01 16:34:46', NULL),
(8, 4, NULL, NULL, '2018-11-01 16:35:25', NULL),
(9, 4, NULL, NULL, '2018-11-01 16:36:06', NULL),
(10, 4, NULL, NULL, '2018-11-01 16:36:57', NULL),
(11, 4, NULL, NULL, '2018-11-01 16:45:24', NULL),
(12, 4, NULL, NULL, '2018-11-01 16:45:56', NULL),
(13, 4, NULL, NULL, '2018-11-01 16:49:35', NULL),
(14, 4, NULL, NULL, '2018-11-01 16:49:49', NULL),
(15, 4, NULL, NULL, '2018-11-01 16:52:19', NULL),
(16, 4, NULL, NULL, '2018-11-01 16:55:18', NULL),
(17, 4, NULL, NULL, '2018-11-01 17:13:06', NULL),
(18, 4, NULL, NULL, '2018-11-01 17:17:56', NULL),
(19, 11, NULL, NULL, '2018-11-01 17:31:15', NULL),
(20, 11, NULL, NULL, '2018-11-01 17:33:50', NULL),
(21, 11, NULL, NULL, '2018-11-01 17:37:18', NULL),
(22, 11, NULL, NULL, '2018-11-01 17:37:43', NULL),
(23, 11, NULL, NULL, '2018-11-01 17:38:09', NULL),
(24, 11, NULL, NULL, '2018-11-01 17:40:09', NULL),
(25, 11, NULL, NULL, '2018-11-01 17:40:50', NULL),
(26, 11, NULL, NULL, '2018-11-01 17:41:10', NULL),
(27, 11, NULL, NULL, '2018-11-01 18:01:43', NULL),
(28, 11, NULL, NULL, '2018-11-01 18:02:10', NULL),
(29, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 11:58:34 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:02:33', '2018-11-01 18:02:33'),
(30, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 11:59:01 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:03:00', '2018-11-01 18:03:01'),
(31, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 11:59:41 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:03:39', '2018-11-01 18:03:40'),
(32, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:05:15 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:09:13', '2018-11-01 18:09:13'),
(33, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:05:40 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:09:38', '2018-11-01 18:09:39'),
(34, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:07:02 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:11:00', '2018-11-01 18:11:01'),
(35, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:08:47 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:12:45', '2018-11-01 18:12:46'),
(36, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:15:06 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:19:04', '2018-11-01 18:19:05'),
(37, 11, NULL, NULL, '2018-11-01 18:19:31', NULL),
(38, 11, NULL, NULL, '2018-11-01 18:20:16', NULL),
(39, 11, NULL, NULL, '2018-11-01 18:20:37', NULL),
(40, 11, NULL, NULL, '2018-11-01 18:25:46', NULL),
(41, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:22:00 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:25:59', '2018-11-01 18:26:00'),
(42, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:26:15 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:30:13', '2018-11-01 18:30:13'),
(43, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:27:12 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:31:10', '2018-11-01 18:31:11'),
(44, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:28:20 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:32:18', '2018-11-01 18:32:18'),
(45, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:29:26 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:33:24', '2018-11-01 18:33:25'),
(46, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:29:57 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:33:56', '2018-11-01 18:33:56'),
(47, 11, '{\"success\":true,\"data\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"request\":{\"body\":\"{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"raw\":\"HTTP\\/1.1 200 OK\\r\\nDate: Thu, 01 Nov 2018 12:34:39 GMT\\r\\nServer: Apache\\/2.4.6 (CentOS) OpenSSL\\/1.0.2k-fips PHP\\/7.0.27\\r\\nX-Powered-By: PHP\\/7.0.27\\r\\nCache-Control: no-cache, private\\r\\nX-RateLimit-Limit: 60\\r\\nX-RateLimit-Remaining: 59\\r\\nContent-Length: 116\\r\\nContent-Type: application\\/json\\r\\n\\r\\n{\\\"status\\\":\\\"error\\\",\\\"message\\\":\\\"Some of the required parameters are missing or blank.\\\",\\\"status_code\\\":\\\"412\\\",\\\"data\\\":null}\",\"headers\":{},\"status_code\":200,\"success\":true,\"redirects\":0,\"url\":\"http:\\/\\/api.sslwireless.com\\/api\\/bill-info\",\"history\":[],\"cookies\":{}}}', NULL, '2018-11-01 18:38:38', '2018-11-01 18:38:38'),
(48, 11, NULL, NULL, '2018-11-01 18:39:05', NULL),
(49, 11, NULL, NULL, '2018-11-01 18:39:17', NULL),
(50, 11, NULL, NULL, '2018-11-01 18:40:52', NULL),
(51, 11, NULL, NULL, '2018-11-01 18:43:12', NULL),
(52, 11, NULL, NULL, '2018-11-01 18:43:21', NULL),
(53, 11, NULL, NULL, '2018-11-01 20:44:53', NULL),
(54, 11, NULL, NULL, '2018-11-01 20:45:57', NULL),
(55, 11, NULL, NULL, '2018-11-01 20:46:38', NULL),
(56, 11, NULL, NULL, '2018-11-01 20:48:18', NULL),
(57, 11, NULL, NULL, '2018-11-01 20:49:18', NULL),
(58, 11, NULL, NULL, '2018-11-01 20:50:03', NULL),
(59, 11, NULL, NULL, '2018-11-01 20:51:33', NULL),
(60, 11, NULL, NULL, '2018-11-01 20:51:49', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ssl_bill_payment`
--
ALTER TABLE `ssl_bill_payment`
  ADD PRIMARY KEY (`payment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ssl_bill_payment`
--
ALTER TABLE `ssl_bill_payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

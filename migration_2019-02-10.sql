DROP TABLE IF EXISTS `account_add_requests`;
CREATE TABLE `account_add_requests`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `skyId` int(11) NOT NULL,
  `type` enum('account','card') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `entityNumber` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `status` enum('rejected','completed','pending') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'pending',
  `created` datetime(0) NOT NULL,
  `updated` datetime(0) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;

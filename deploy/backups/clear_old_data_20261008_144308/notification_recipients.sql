-- Backup of 14 rows from `notification_recipients` deleted 2026-10-08T14:43:08+00:00
CREATE TABLE IF NOT EXISTS `notification_recipients` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `notificationID` int(10) unsigned NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `usertypeID` tinyint(3) unsigned NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_recipient` (`userID`,`usertypeID`,`is_read`),
  KEY `idx_notification` (`notificationID`),
  CONSTRAINT `fk_notification_recipients_notification` FOREIGN KEY (`notificationID`) REFERENCES `notifications` (`notificationID`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2262 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1925','22','5','3','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1926','22','22','4','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1927','22','1','1','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1956','50','5','3','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1957','50','22','4','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1958','50','1','1','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1959','51','2041','3','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1960','51','81','4','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1961','51','1','1','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1962','52','2041','3','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1963','53','2484','3','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1964','53','81','4','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1965','53','77','2','0',NULL);
INSERT INTO `notification_recipients` (`id`,`notificationID`,`userID`,`usertypeID`,`is_read`,`read_at`) VALUES ('1966','54','2484','3','0',NULL);

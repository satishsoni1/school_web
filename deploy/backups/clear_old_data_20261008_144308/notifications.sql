-- Backup of 6 rows from `notifications` deleted 2026-10-08T14:43:08+00:00
CREATE TABLE IF NOT EXISTS `notifications` (
  `notificationID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) NOT NULL,
  `referenceID` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`notificationID`),
  KEY `idx_type` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `notifications` (`notificationID`,`title`,`message`,`type`,`referenceID`,`created_at`) VALUES ('22','Leave Application Submitted','A leave application has been submitted for 05 Oct 2026 - 05 Oct 2026.','leaveapplication','1','2026-10-04 19:09:10');
INSERT INTO `notifications` (`notificationID`,`title`,`message`,`type`,`referenceID`,`created_at`) VALUES ('50','Leave Application Submitted','A leave application has been submitted for 07 Oct 2026 - 09 Oct 2026.','leaveapplication','2','2026-10-07 10:31:22');
INSERT INTO `notifications` (`notificationID`,`title`,`message`,`type`,`referenceID`,`created_at`) VALUES ('51','Leave Application Submitted','A leave application has been submitted for 08 Oct 2026 - 09 Oct 2026.','leaveapplication','3','2026-10-07 10:51:49');
INSERT INTO `notifications` (`notificationID`,`title`,`message`,`type`,`referenceID`,`created_at`) VALUES ('52','Leave Application 1','Your leave application has been 1.','leaveapplication','3','2026-10-07 10:52:40');
INSERT INTO `notifications` (`notificationID`,`title`,`message`,`type`,`referenceID`,`created_at`) VALUES ('53','Leave Application Submitted','A leave application has been submitted for 08 Oct 2026 - 09 Oct 2026.','leaveapplication','4','2026-10-07 10:55:42');
INSERT INTO `notifications` (`notificationID`,`title`,`message`,`type`,`referenceID`,`created_at`) VALUES ('54','Leave Application 0','Your leave application has been 0.','leaveapplication','4','2026-10-07 10:56:28');

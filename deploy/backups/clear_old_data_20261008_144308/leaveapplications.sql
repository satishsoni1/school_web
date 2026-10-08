-- Backup of 4 rows from `leaveapplications` deleted 2026-10-08T14:43:08+00:00
CREATE TABLE IF NOT EXISTS `leaveapplications` (
  `leaveapplicationID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `leavecategoryID` int(10) unsigned NOT NULL,
  `apply_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `od_status` tinyint(1) NOT NULL DEFAULT 0,
  `from_date` date NOT NULL,
  `from_time` time DEFAULT NULL,
  `to_date` date NOT NULL,
  `to_time` time DEFAULT NULL,
  `leave_days` int(11) NOT NULL,
  `reason` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attachment` varchar(200) DEFAULT NULL,
  `attachmentorginalname` varchar(200) DEFAULT NULL,
  `create_date` datetime NOT NULL,
  `modify_date` datetime NOT NULL,
  `create_userID` int(11) NOT NULL,
  `create_usertypeID` int(11) unsigned NOT NULL,
  `applicationto_userID` int(11) unsigned DEFAULT NULL,
  `applicationto_usertypeID` int(11) unsigned DEFAULT NULL,
  `approver_userID` int(11) unsigned DEFAULT NULL,
  `approver_usertypeID` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `schoolyearID` int(11) NOT NULL,
  PRIMARY KEY (`leaveapplicationID`),
  KEY `leave_categoryID` (`leavecategoryID`),
  KEY `from_date` (`from_date`),
  KEY `to_date` (`to_date`),
  KEY `approver_userID` (`approver_userID`),
  KEY `approver_usertypeID` (`approver_usertypeID`),
  KEY `applicationto_usertypeID` (`applicationto_usertypeID`),
  KEY `applicationto_userID` (`applicationto_userID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `leaveapplications` (`leaveapplicationID`,`leavecategoryID`,`apply_date`,`od_status`,`from_date`,`from_time`,`to_date`,`to_time`,`leave_days`,`reason`,`attachment`,`attachmentorginalname`,`create_date`,`modify_date`,`create_userID`,`create_usertypeID`,`applicationto_userID`,`applicationto_usertypeID`,`approver_userID`,`approver_usertypeID`,`status`,`schoolyearID`) VALUES ('1','5','2026-10-04 13:39:10','0','2026-10-05','19:09:10','2026-10-05','19:09:10','1','to Test','','','2026-10-04 19:09:10','2026-10-04 19:09:10','5','3','1','1',NULL,NULL,NULL,'4');
INSERT INTO `leaveapplications` (`leaveapplicationID`,`leavecategoryID`,`apply_date`,`od_status`,`from_date`,`from_time`,`to_date`,`to_time`,`leave_days`,`reason`,`attachment`,`attachmentorginalname`,`create_date`,`modify_date`,`create_userID`,`create_usertypeID`,`applicationto_userID`,`applicationto_usertypeID`,`approver_userID`,`approver_usertypeID`,`status`,`schoolyearID`) VALUES ('2','1','2026-10-07 05:01:22','0','2026-10-07','10:31:22','2026-10-09','10:31:22','3','fever','','','2026-10-07 10:31:22','2026-10-07 10:31:22','5','3','1','1',NULL,NULL,NULL,'4');
INSERT INTO `leaveapplications` (`leaveapplicationID`,`leavecategoryID`,`apply_date`,`od_status`,`from_date`,`from_time`,`to_date`,`to_time`,`leave_days`,`reason`,`attachment`,`attachmentorginalname`,`create_date`,`modify_date`,`create_userID`,`create_usertypeID`,`applicationto_userID`,`applicationto_usertypeID`,`approver_userID`,`approver_usertypeID`,`status`,`schoolyearID`) VALUES ('3','1','2026-10-07 05:21:49','0','2026-10-08','10:51:49','2026-10-09','10:51:49','2','personal','','','2026-10-07 10:51:49','2026-10-07 10:52:40','2041','3','1','1',NULL,NULL,'1','4');
INSERT INTO `leaveapplications` (`leaveapplicationID`,`leavecategoryID`,`apply_date`,`od_status`,`from_date`,`from_time`,`to_date`,`to_time`,`leave_days`,`reason`,`attachment`,`attachmentorginalname`,`create_date`,`modify_date`,`create_userID`,`create_usertypeID`,`applicationto_userID`,`applicationto_usertypeID`,`approver_userID`,`approver_usertypeID`,`status`,`schoolyearID`) VALUES ('4','1','2026-10-07 05:25:42','0','2026-10-08','10:55:42','2026-10-09','10:55:42','2','Personal','','','2026-10-07 10:55:42','2026-10-07 10:56:28','2484','3','77','2',NULL,NULL,'0','4');

-- Backup of 3 rows from `assignmentanswer` deleted 2026-10-08T14:43:08+00:00
CREATE TABLE IF NOT EXISTS `assignmentanswer` (
  `assignmentanswerID` int(11) NOT NULL AUTO_INCREMENT,
  `assignmentID` int(11) NOT NULL,
  `schoolyearID` int(11) NOT NULL,
  `uploaderID` int(11) NOT NULL,
  `uploadertypeID` int(11) NOT NULL,
  `answerfile` text NOT NULL,
  `answerfileoriginal` text NOT NULL,
  `answerdate` date NOT NULL,
  PRIMARY KEY (`assignmentanswerID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `assignmentanswer` (`assignmentanswerID`,`assignmentID`,`schoolyearID`,`uploaderID`,`uploadertypeID`,`answerfile`,`answerfileoriginal`,`answerdate`) VALUES ('1','223','3','56','3','2b8ccdb27cf47c25c4dd7bed0aecdce50706e406b534119c736b46ab3a471ed9b9373aa63def24f62688bd55b8b4691fb267052fee0766e0acde3a7ee1b2654b.jpg','Rev 1 HW Done.jpg','2025-09-10');
INSERT INTO `assignmentanswer` (`assignmentanswerID`,`assignmentID`,`schoolyearID`,`uploaderID`,`uploadertypeID`,`answerfile`,`answerfileoriginal`,`answerdate`) VALUES ('2','224','3','56','3','4962e5bdc9c27f2143a8493e482a6da3a2c3efded97aa7fe555fd569e1c6796c675b3ba0e592970f3b01ae21e1ffdc00180cfa3371e971eee0771544be157611.jpg','Rev 2 HW Done.jpg','2025-09-10');
INSERT INTO `assignmentanswer` (`assignmentanswerID`,`assignmentID`,`schoolyearID`,`uploaderID`,`uploadertypeID`,`answerfile`,`answerfileoriginal`,`answerdate`) VALUES ('3','235','3','56','3','d10f2943bf8a5e281d9ec9d3d3b82d01a78b17d6d047b3669bb7572b2fdb585a42750534d8513e6546ecfb572f53726ab8e9292feb4b379082c24aa0fe4c3a15.jpg','Hindi HW Done.jpg','2025-09-12');

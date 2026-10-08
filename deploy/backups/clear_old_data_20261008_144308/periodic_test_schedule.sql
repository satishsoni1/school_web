-- Backup of 75 rows from `periodic_test_schedule` deleted 2026-10-08T14:43:08+00:00
CREATE TABLE IF NOT EXISTS `periodic_test_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_date` date NOT NULL,
  `day` varchar(20) NOT NULL,
  `classesID` int(11) NOT NULL,
  `subject` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('1','2026-06-29','MONDAY','1','ENGLISH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('2','2026-06-29','MONDAY','2','ENGLISH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('3','2026-06-29','MONDAY','3','ENGLISH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('4','2026-06-29','MONDAY','4','MATH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('5','2026-06-29','MONDAY','5','MATH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('6','2026-06-29','MONDAY','6','SCIENCE');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('7','2026-06-29','MONDAY','7','MATH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('8','2026-06-29','MONDAY','8','ENGLISH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('9','2026-06-29','MONDAY','1','G.K.');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('10','2026-06-29','MONDAY','2','G.K.');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('11','2026-06-29','MONDAY','3','G.K.');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('12','2026-06-29','MONDAY','4','ARTS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('13','2026-06-29','MONDAY','5','ARTS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('14','2026-06-29','MONDAY','6','COMPUTER');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('15','2026-06-29','MONDAY','7','G.K.');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('16','2026-06-29','MONDAY','8','ARTS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('17','2026-06-30','TUESDAY','1','MATH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('18','2026-06-30','TUESDAY','2','MATH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('19','2026-06-30','TUESDAY','3','MATH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('20','2026-06-30','TUESDAY','4','HINDI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('21','2026-06-30','TUESDAY','5','MARATHI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('22','2026-06-30','TUESDAY','6','MATH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('23','2026-06-30','TUESDAY','7','ENGLISH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('24','2026-06-30','TUESDAY','8','SCIENCE');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('25','2026-06-30','TUESDAY','1','COMPUTER');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('26','2026-06-30','TUESDAY','2','COMPUTER');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('27','2026-06-30','TUESDAY','3','SOCIALS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('28','2026-06-30','TUESDAY','4','SOCIALS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('29','2026-06-30','TUESDAY','5','SCIENCE');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('30','2026-06-30','TUESDAY','6','ARTS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('31','2026-06-30','TUESDAY','7','ARTS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('32','2026-06-30','TUESDAY','8','G.K.');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('33','2026-07-01','WEDNESDAY','1','EVS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('34','2026-07-01','WEDNESDAY','2','EVS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('35','2026-07-01','WEDNESDAY','3','MARATHI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('36','2026-07-01','WEDNESDAY','4','ENGLISH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('37','2026-07-01','WEDNESDAY','5','HINDI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('38','2026-07-01','WEDNESDAY','6','SOCIALS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('39','2026-07-01','WEDNESDAY','7','SANSKRIT');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('40','2026-07-01','WEDNESDAY','8','MARATHI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('41','2026-07-01','WEDNESDAY','1','ARTS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('42','2026-07-01','WEDNESDAY','2','ARTS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('43','2026-07-01','WEDNESDAY','3','ARTS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('44','2026-07-01','WEDNESDAY','4','G.K.');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('45','2026-07-01','WEDNESDAY','5','SOCIALS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('46','2026-07-01','WEDNESDAY','6','HINDI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('47','2026-07-01','WEDNESDAY','7','COMPUTER');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('48','2026-07-01','WEDNESDAY','8','SOCIALS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('49','2026-07-02','THURSDAY','1','HINDI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('50','2026-07-02','THURSDAY','2','HINDI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('51','2026-07-02','THURSDAY','3','HINDI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('52','2026-07-02','THURSDAY','4','MARATHI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('53','2026-07-02','THURSDAY','5','SANSKRIT');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('54','2026-07-02','THURSDAY','6','ENGLISH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('55','2026-07-02','THURSDAY','7','SOCIALS');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('56','2026-07-02','THURSDAY','8','MATH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('57','2026-07-02','THURSDAY','3','COMPUTER');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('58','2026-07-02','THURSDAY','4','SCIENCE');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('59','2026-07-02','THURSDAY','5','COMPUTER');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('60','2026-07-02','THURSDAY','6','G.K.');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('61','2026-07-02','THURSDAY','7','HINDI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('62','2026-07-02','THURSDAY','8','HINDI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('63','2026-07-03','FRIDAY','1','MARATHI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('64','2026-07-03','FRIDAY','2','MARATHI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('65','2026-07-03','FRIDAY','3','SCIENCE');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('66','2026-07-03','FRIDAY','4','SANSKRIT');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('67','2026-07-03','FRIDAY','5','ENGLISH');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('68','2026-07-03','FRIDAY','6','SANSKRIT');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('69','2026-07-03','FRIDAY','7','SCIENCE');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('70','2026-07-03','FRIDAY','8','SANSKRIT');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('71','2026-07-03','FRIDAY','4','COMPUTER');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('72','2026-07-03','FRIDAY','5','G.K.');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('73','2026-07-03','FRIDAY','6','MARATHI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('74','2026-07-03','FRIDAY','7','MARATHI');
INSERT INTO `periodic_test_schedule` (`id`,`test_date`,`day`,`classesID`,`subject`) VALUES ('75','2026-07-03','FRIDAY','8','COMPUTER');

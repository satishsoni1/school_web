-- Admin sidebar: Certificates (studentcertificate) — generate, save, edit, print and delete
-- certificates / letters for a student. First type: "Request for Leaving Certificate" (letter to
-- the student's previous school). Each certificate's fields are stored as JSON in `data`, so new
-- types need no schema change (add them to Studentcertificate_m::$types + a print view).
-- Upload controllers/Studentcertificate.php, models/Studentcertificate_m.php and
-- views/studentcertificate/ FIRST, then run once. Safe to re-run. Admins log out and back in to see the menu.

CREATE TABLE IF NOT EXISTS `student_certificate` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `type` VARCHAR(40) NOT NULL,
  `ref_no` INT NOT NULL,
  `ref_year` VARCHAR(20) NOT NULL,
  `cert_date` DATE NOT NULL,
  `studentID` INT NULL,
  `student_name` VARCHAR(150) NOT NULL DEFAULT '',
  `data` TEXT NOT NULL,
  `create_userID` INT NULL,
  `create_username` VARCHAR(60) NULL,
  `create_date` DATETIME NOT NULL,
  `modify_date` DATETIME NOT NULL,
  UNIQUE KEY `uniq_ref` (`ref_year`, `ref_no`),
  KEY `idx_student` (`studentID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `permissions` (`name`, `description`, `active`)
SELECT 'studentcertificate', 'Certificates', 'yes' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'studentcertificate');

INSERT INTO `permission_relationships` (`permission_id`, `usertype_id`)
SELECT p.`permissionID`, 1 FROM `permissions` p
WHERE p.`name` = 'studentcertificate'
  AND NOT EXISTS (SELECT 1 FROM `permission_relationships` r WHERE r.`permission_id` = p.`permissionID` AND r.`usertype_id` = 1);

INSERT INTO `menu` (`menuName`, `link`, `icon`, `pullRight`, `status`, `parentID`, `priority`)
-- Top level, just below Student (sidebar is ordered by priority DESC).
SELECT 'Certificates', 'studentcertificate', 'fa-certificate', '', 1, 0,
       (SELECT t.p FROM (SELECT COALESCE(MAX(CASE WHEN `link` = 'student' THEN `priority` END), MIN(`priority`)) - 1 AS p FROM `menu`) AS t)
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM (SELECT `link` FROM `menu`) m WHERE m.`link` = 'studentcertificate');

-- Holistic report card snapshots: freezes the student info, photo, class and class-teacher
-- name/sign used on a student's report for a school year, so opening an old year's report
-- shows the data as it was then instead of today's master data.
-- Photo/sign files are copied to uploads/holistic_snapshots/<schoolyearID>/ (must be writable).
-- Run once on the application DB.

CREATE TABLE IF NOT EXISTS `holistic_report_snapshot` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `studentID` INT UNSIGNED NOT NULL,
    `schoolyearID` INT UNSIGNED NOT NULL,
    `classesID` INT UNSIGNED NOT NULL,
    `student_data` MEDIUMTEXT NOT NULL,       -- JSON of the student + studentrelation row (no credentials)
    `classes_data` TEXT NULL,                 -- JSON of the classes row
    `section_data` TEXT NULL,                 -- JSON of the section row
    `schoolyear_data` TEXT NULL,              -- JSON of the schoolyear row
    `photo` VARCHAR(255) NULL,                -- file name inside uploads/holistic_snapshots/<schoolyearID>/
    `teacher_name` VARCHAR(255) NULL,
    `teacher_sign` VARCHAR(255) NULL,         -- path relative to the web root
    `created_at` DATETIME NOT NULL,
    `updated_at` DATETIME NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uniq_student_year` (`studentID`, `schoolyearID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

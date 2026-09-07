-- PPGMIS: admin management of the Academic Planner + Periodic Test schedule/syllabus.
-- Adds a "Planner & Test Manager" page to the web admin sidebar (admin usertype only)
-- and makes sure the tables it edits exist. The mobile app already reads these tables
-- live (api/v10/academicplanner, api/v10/periodictest), so admin edits show up for
-- students on their next refresh / login.
--
-- Run once against your application database, then reload any admin page.

-- ---------------------------------------------------------------------------
-- 1. Tables (no-ops if you already created them from the *_inserts.sql dumps).
-- ---------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `academic_planner` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `event_date` DATE NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `type` VARCHAR(50) NOT NULL DEFAULT 'activity',
  `description` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- If `academic_planner` already existed WITHOUT a primary key column, run this once
-- (it will error harmlessly if `id` already exists):
--   ALTER TABLE `academic_planner` ADD COLUMN `id` INT AUTO_INCREMENT PRIMARY KEY FIRST;

CREATE TABLE IF NOT EXISTS `periodic_test_schedule` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `test_date` DATE NOT NULL,
  `day` VARCHAR(20) NOT NULL,
  `classesID` INT NOT NULL,
  `subject` VARCHAR(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `periodic_test_syllabus` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `classesID` INT NOT NULL,
  `subject` VARCHAR(50) NOT NULL,
  `syllabus` TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------------------------------------------------------------
-- 2. Permission + sidebar entry (mirrors db_migration_sendnotification_menu.sql).
--    The sidebar row only shows when its `link` resolves to a granted permission.
-- ---------------------------------------------------------------------------
INSERT INTO `permissions` (`name`, `description`, `active`)
VALUES ('plannermanager', 'Planner & Test Manager', 'yes');

INSERT INTO `permission_relationships` (`permission_id`, `usertype_id`)
VALUES (LAST_INSERT_ID(), 1);

INSERT INTO `menu` (`menuName`, `parentID`, `link`, `icon`, `status`, `priority`, `pullRight`)
VALUES ('Planner & Test Manager', 0, 'plannermanager', 'fa fa-calendar', 1,
    (SELECT t.p FROM (SELECT COALESCE(MIN(priority), 0) - 1 AS p FROM `menu`) AS t), '');

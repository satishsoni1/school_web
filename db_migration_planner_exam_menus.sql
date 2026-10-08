-- Admin sidebar: split "Planner & Test Manager" into three proper modules.
--   Academic → Academic Planner   (academicplanner)
--   Exam     → Exam Timetable     (examtimetable)
--   Exam     → Exam Portion       (examportion)
-- Upload the new controllers/views FIRST, then run this once. Safe to re-run.
-- Admins must log out and back in to see the new menu (permissions are cached in the session).

-- 1. Permissions, granted to Admin (usertype 1). A menu item only shows when its link is a granted permission.
INSERT INTO `permissions` (`name`, `description`, `active`)
SELECT 'academicplanner', 'Academic Planner', 'yes' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'academicplanner');

INSERT INTO `permissions` (`name`, `description`, `active`)
SELECT 'examtimetable', 'Exam Timetable', 'yes' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'examtimetable');

INSERT INTO `permissions` (`name`, `description`, `active`)
SELECT 'examportion', 'Exam Portion', 'yes' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'examportion');

INSERT INTO `permission_relationships` (`permission_id`, `usertype_id`)
SELECT p.`permissionID`, 1 FROM `permissions` p
WHERE p.`name` IN ('academicplanner', 'examtimetable', 'examportion')
  AND NOT EXISTS (SELECT 1 FROM `permission_relationships` r WHERE r.`permission_id` = p.`permissionID` AND r.`usertype_id` = 1);

-- 2. Menu items inside the existing Academic and Exam groups.
INSERT INTO `menu` (`menuName`, `link`, `icon`, `pullRight`, `status`, `parentID`, `priority`)
SELECT 'Academic Planner', 'academicplanner', 'fa-calendar', '', 1, g.`menuID`, 900
FROM `menu` g
WHERE g.`menuName` = 'main_academic' AND g.`link` = '#'
  AND NOT EXISTS (SELECT 1 FROM (SELECT `link` FROM `menu`) m WHERE m.`link` = 'academicplanner');

INSERT INTO `menu` (`menuName`, `link`, `icon`, `pullRight`, `status`, `parentID`, `priority`)
SELECT 'Exam Timetable', 'examtimetable', 'fa-clock-o', '', 1, g.`menuID`, 900
FROM `menu` g
WHERE g.`menuName` = 'main_exam' AND g.`link` = '#'
  AND NOT EXISTS (SELECT 1 FROM (SELECT `link` FROM `menu`) m WHERE m.`link` = 'examtimetable');

INSERT INTO `menu` (`menuName`, `link`, `icon`, `pullRight`, `status`, `parentID`, `priority`)
SELECT 'Exam Portion', 'examportion', 'fa-book', '', 1, g.`menuID`, 890
FROM `menu` g
WHERE g.`menuName` = 'main_exam' AND g.`link` = '#'
  AND NOT EXISTS (SELECT 1 FROM (SELECT `link` FROM `menu`) m WHERE m.`link` = 'examportion');

-- 3. Remove the old single "Planner & Test Manager" entry (its URL now redirects to the new pages).
DELETE FROM `menu` WHERE `link` = 'plannermanager';

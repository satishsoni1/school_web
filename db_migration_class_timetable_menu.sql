-- Admin sidebar: Academic → Class Timetable (classtimetable) — edits the daily timetable the
-- mobile app shows on its "Timetable" page (classwise_timetable), incl. Nursery / Prep classes.
-- Upload controllers/Classtimetable.php, models/Classtimetable_m.php and views/classtimetable/ FIRST,
-- then run once. Safe to re-run. Admins log out and back in to see the menu.

INSERT INTO `permissions` (`name`, `description`, `active`)
SELECT 'classtimetable', 'Class Timetable', 'yes' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'classtimetable');

INSERT INTO `permission_relationships` (`permission_id`, `usertype_id`)
SELECT p.`permissionID`, 1 FROM `permissions` p
WHERE p.`name` = 'classtimetable'
  AND NOT EXISTS (SELECT 1 FROM `permission_relationships` r WHERE r.`permission_id` = p.`permissionID` AND r.`usertype_id` = 1);

INSERT INTO `menu` (`menuName`, `link`, `icon`, `pullRight`, `status`, `parentID`, `priority`)
SELECT 'Class Timetable', 'classtimetable', 'fa-table', '', 1, g.`menuID`, 950
FROM `menu` g
WHERE g.`menuName` = 'main_academic' AND g.`link` = '#'
  AND NOT EXISTS (SELECT 1 FROM (SELECT `link` FROM `menu`) m WHERE m.`link` = 'classtimetable');

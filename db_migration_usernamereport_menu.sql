-- Admin sidebar: Report → Username List (usernamereport) — class-wise student and parent
-- login usernames with Print / CSV.
-- Upload controllers/Usernamereport.php and views/report/username/ FIRST, then run once.
-- Safe to re-run. Admins log out and back in to see the menu.

INSERT INTO `permissions` (`name`, `description`, `active`)
SELECT 'usernamereport', 'Username List Report', 'yes' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'usernamereport');

INSERT INTO `permission_relationships` (`permission_id`, `usertype_id`)
SELECT p.`permissionID`, 1 FROM `permissions` p
WHERE p.`name` = 'usernamereport'
  AND NOT EXISTS (SELECT 1 FROM `permission_relationships` r WHERE r.`permission_id` = p.`permissionID` AND r.`usertype_id` = 1);

INSERT INTO `menu` (`menuName`, `link`, `icon`, `pullRight`, `status`, `parentID`, `priority`)
SELECT 'Username List', 'usernamereport', 'fa-user-secret', '', 1, g.`menuID`, 985
FROM `menu` g
WHERE g.`menuName` = 'main_report' AND g.`link` = '#'
  AND NOT EXISTS (SELECT 1 FROM (SELECT `link` FROM `menu`) m WHERE m.`link` = 'usernamereport');

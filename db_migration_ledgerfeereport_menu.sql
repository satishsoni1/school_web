-- Admin sidebar: Report → Ledger-wise Fee Received (ledgerfeereport) — fees received per fee type
-- for a date range, split by Cash / Cheque / Online, with Print / CSV.
-- Upload controllers/Ledgerfeereport.php and views/report/ledgerfee/ FIRST, then run once.
-- Safe to re-run. Admins log out and back in to see the menu.

INSERT INTO `permissions` (`name`, `description`, `active`)
SELECT 'ledgerfeereport', 'Ledger-wise Fee Received', 'yes' FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `permissions` WHERE `name` = 'ledgerfeereport');

INSERT INTO `permission_relationships` (`permission_id`, `usertype_id`)
SELECT p.`permissionID`, 1 FROM `permissions` p
WHERE p.`name` = 'ledgerfeereport'
  AND NOT EXISTS (SELECT 1 FROM `permission_relationships` r WHERE r.`permission_id` = p.`permissionID` AND r.`usertype_id` = 1);

INSERT INTO `menu` (`menuName`, `link`, `icon`, `pullRight`, `status`, `parentID`, `priority`)
SELECT 'Ledger-wise Fee Received', 'ledgerfeereport', 'fa-book', '', 1, g.`menuID`, 845
FROM `menu` g
WHERE g.`menuName` = 'main_report' AND g.`link` = '#'
  AND NOT EXISTS (SELECT 1 FROM (SELECT `link` FROM `menu`) m WHERE m.`link` = 'ledgerfeereport');

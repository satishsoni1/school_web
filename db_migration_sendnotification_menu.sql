-- Adds "Send Notification" to the web admin sidebar (Academic year > Admin usertype).
-- The sidebar is DB-driven (mvc/views/components/page_menu.php -> Admin_Controller::menuTree()),
-- and a menu row is only shown if its `link` value resolves to a granted permission, so this
-- also creates that permission and grants it to usertype 1 (Admin).
-- Run once against your application database, then reload any admin page.

INSERT INTO `permissions` (`name`, `description`, `active`)
VALUES ('sendnotification', 'Send Notification (test tool)', 'yes');

INSERT INTO `permission_relationships` (`permission_id`, `usertype_id`)
VALUES (LAST_INSERT_ID(), 1);

INSERT INTO `menu` (`menuName`, `parentID`, `link`, `icon`, `status`, `priority`, `pullRight`)
VALUES ('Send Notification', 0, 'sendnotification', 'fa fa-bell', 1,
    (SELECT t.p FROM (SELECT COALESCE(MIN(priority), 0) - 1 AS p FROM `menu`) AS t), '');

-- Allow emoji and all Unicode symbols in notices (and their push-notification history).
-- MySQL's legacy `utf8` charset only stores 3-byte characters, so emoji / many symbols were
-- dropped or cut the text off when a notice was saved. Run once on the application DB,
-- together with deploying the config change to char_set = 'utf8mb4' in mvc/config/*/database.php.
-- Requires MySQL 5.5.3+ / MariaDB 5.5+.
-- NOTE: notices saved before this migration already lost those characters; re-save them on the portal.

ALTER TABLE `notice` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
ALTER TABLE `leaveapplications` MODIFY `reason` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

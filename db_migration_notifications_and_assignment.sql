-- PPGMIS: migration for assignment "assigned date" + in-app notification history.
-- Run this once against your application database before deploying the related
-- backend/app changes (Assignment_m::join_get_assignment, Notification_lib, api/v10/Notification).

-- A2: Assignment list needs a real "assigned date" column (previously only deadlinedate existed).
ALTER TABLE `assignment`
    ADD COLUMN `createddate` DATETIME NULL DEFAULT NULL AFTER `deadlinedate`;

-- Backfill existing rows so old assignments don't show a blank "Assigned" date.
-- Falls back to deadlinedate minus 7 days as a reasonable estimate; adjust if you'd rather leave them NULL.
UPDATE `assignment` SET `createddate` = DATE_SUB(`deadlinedate`, INTERVAL 7 DAY) WHERE `createddate` IS NULL;

-- B1: notification history tables (one row per activity, fanned out to each recipient).
CREATE TABLE IF NOT EXISTS `notifications` (
    `notificationID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(255) NOT NULL,
    `message` TEXT NOT NULL,
    `type` VARCHAR(50) NOT NULL,          -- e.g. notice, assignment, attendance, event, holiday, leaveapplication, exam, hpc
    `referenceID` INT UNSIGNED NULL,      -- ID of the related record (assignmentID, noticeID, ...), nullable for broadcasts
    `created_at` DATETIME NOT NULL,
    PRIMARY KEY (`notificationID`),
    KEY `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `notification_recipients` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `notificationID` INT UNSIGNED NOT NULL,
    `userID` INT UNSIGNED NOT NULL,
    `usertypeID` TINYINT UNSIGNED NOT NULL,
    `is_read` TINYINT(1) NOT NULL DEFAULT 0,
    `read_at` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `idx_recipient` (`userID`, `usertypeID`, `is_read`),
    KEY `idx_notification` (`notificationID`),
    CONSTRAINT `fk_notification_recipients_notification`
        FOREIGN KEY (`notificationID`) REFERENCES `notifications` (`notificationID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

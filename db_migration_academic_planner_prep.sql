-- Academic Planner 2026-27 for both planners, exactly as the PDFs:
--   'grade' = ACADEMIC PLANNER 26-27 (Grades 1-10)   -> already in the table; corrected below
--   'prep'  = ANNUAL PLANNER PREP 26-27 (Nursery, Prep 1, Prep 2) -> inserted below
-- The app shows each student/parent the planner of their class (Grade N -> 'grade',
-- Nursery/Prep -> 'prep'). Safe to re-run.

ALTER TABLE `academic_planner`
    ADD COLUMN IF NOT EXISTS `audience` VARCHAR(10) NOT NULL DEFAULT 'grade' COMMENT 'grade = Grades 1-10, prep = Nursery/Prep';
ALTER TABLE `academic_planner` ADD INDEX IF NOT EXISTS `idx_audience_date` (`audience`, `event_date`);

-- ---- Grades 1-10: corrections to match the PDF ------------------------------------
-- Handwriting is only on 24 Apr.
DELETE FROM `academic_planner` WHERE `audience` = 'grade' AND `event_date` = '2026-04-25' AND `title` LIKE '%Handwriting%';
-- 29 Jul: Guru Purnima / Shlok Recitation / Ludo Gr. 4 (nothing on 30 Jul).
DELETE FROM `academic_planner` WHERE `audience` = 'grade' AND `event_date` = '2026-07-30' AND `title` LIKE 'Guru Purnima%';
UPDATE `academic_planner` SET `title` = 'Guru Purnima / Shlok Recitation / Ludo Tournament (Gr. 4)'
    WHERE `audience` = 'grade' AND `event_date` = '2026-07-29' AND `title` LIKE 'Guru Purnima%';
-- 15 Aug: Independence Day / Parsi New Year (same day).
DELETE FROM `academic_planner` WHERE `audience` = 'grade' AND `event_date` = '2026-08-16' AND `title` LIKE 'Parsi New Year%';
UPDATE `academic_planner` SET `title` = 'Independence Day / Parsi New Year'
    WHERE `audience` = 'grade' AND `event_date` = '2026-08-15' AND `title` LIKE 'Independence Day%';
-- "School Reopens" is an event, not a holiday.
UPDATE `academic_planner` SET `type` = 'activity' WHERE `audience` = 'grade' AND `title` = 'School Reopens';
-- Holidays shown in red on the PDF but missing here (Ganesh festival, Diwali break, winter break).
DELETE FROM `academic_planner` WHERE `audience` = 'grade' AND `title` IN ('Ganesh Festival Vacation', 'Diwali Vacation', 'Winter Vacation');
INSERT INTO `academic_planner` (`event_date`, `title`, `type`, `description`, `audience`) VALUES
('2026-09-15', 'Ganesh Festival Vacation', 'holiday', '', 'grade'),
('2026-09-16', 'Ganesh Festival Vacation', 'holiday', '', 'grade'),
('2026-09-17', 'Ganesh Festival Vacation', 'holiday', '', 'grade'),
('2026-09-18', 'Ganesh Festival Vacation', 'holiday', '', 'grade'),
('2026-11-09', 'Diwali Vacation', 'holiday', '', 'grade'),
('2026-11-11', 'Diwali Vacation', 'holiday', '', 'grade'),
('2026-11-12', 'Diwali Vacation', 'holiday', '', 'grade'),
('2026-11-13', 'Diwali Vacation', 'holiday', '', 'grade'),
('2026-12-28', 'Winter Vacation', 'holiday', '', 'grade'),
('2026-12-29', 'Winter Vacation', 'holiday', '', 'grade'),
('2026-12-30', 'Winter Vacation', 'holiday', '', 'grade'),
('2026-12-31', 'Winter Vacation', 'holiday', '', 'grade');

-- ---- Pre-Primary (Nursery, Prep 1, Prep 2) -----------------------------------------
DELETE FROM `academic_planner` WHERE `audience` = 'prep';
INSERT INTO `academic_planner` (`event_date`, `title`, `type`, `description`, `audience`) VALUES
('2026-04-03', 'Good Friday', 'holiday', '', 'prep'),
('2026-04-05', 'Easter', 'holiday', '', 'prep'),
('2026-05-01', 'Maharashtra Day & Buddha Purnima', 'holiday', '', 'prep'),
('2026-06-03', 'School Reopens', 'activity', '', 'prep'),
('2026-06-05', 'World Environment / Green Day', 'activity', '', 'prep'),
('2026-06-17', 'Thumb Painting', 'activity', '', 'prep'),
('2026-06-19', 'World Environment Day / Father\'s Day', 'activity', '', 'prep'),
('2026-06-26', 'Moharram', 'holiday', '', 'prep'),
('2026-07-15', 'Rainy & Blue Day', 'activity', '', 'prep'),
('2026-07-24', 'Ashadi Ekadashi', 'activity', '', 'prep'),
('2026-07-29', 'Guru Purnima', 'activity', '', 'prep'),
('2026-07-31', 'Solo Dance', 'activity', '', 'prep'),
('2026-08-03', 'Friendship Day', 'activity', '', 'prep'),
('2026-08-05', 'Market Day', 'activity', '', 'prep'),
('2026-08-14', 'Independence Day Activity', 'activity', '', 'prep'),
('2026-08-15', 'Independence Day / Parsi New Year', 'holiday', '', 'prep'),
('2026-08-17', 'Evaluation 1 (Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2026-08-18', 'Evaluation 1 (Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2026-08-19', 'Evaluation 1 (Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2026-08-20', 'Evaluation 1 (Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2026-08-21', 'Evaluation 1 (Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2026-08-24', 'Handwriting', 'activity', '', 'prep'),
('2026-08-26', 'Eid-e-Milad', 'holiday', '', 'prep'),
('2026-08-27', 'Rakhi Making Activity / Open Day', 'activity', '', 'prep'),
('2026-08-28', 'Raksha Bandhan', 'holiday', '', 'prep'),
('2026-09-05', 'Gopal Kala / Teacher\'s Day', 'holiday', '', 'prep'),
('2026-09-09', 'Drawing Competition', 'activity', '', 'prep'),
('2026-09-11', 'English Recitation', 'activity', '', 'prep'),
('2026-09-14', 'Ganesh Chaturthi', 'holiday', '', 'prep'),
('2026-09-15', 'Ganesh Festival Vacation', 'holiday', '', 'prep'),
('2026-09-16', 'Ganesh Festival Vacation', 'holiday', '', 'prep'),
('2026-09-17', 'Ganesh Festival Vacation', 'holiday', '', 'prep'),
('2026-09-18', 'Ganesh Festival Vacation', 'holiday', '', 'prep'),
('2026-09-21', 'School Reopens', 'activity', '', 'prep'),
('2026-09-22', 'Hindi Recitation', 'activity', '', 'prep'),
('2026-09-25', 'Fancy Dress', 'activity', '', 'prep'),
('2026-09-30', 'English Elocution (Prep 2)', 'activity', '', 'prep'),
('2026-10-01', 'Gandhi Jayanti / White Day', 'activity', '', 'prep'),
('2026-10-02', 'Mahatma Gandhi Jayanti', 'holiday', '', 'prep'),
('2026-10-05', 'Hindi Elocution (Prep 2)', 'activity', '', 'prep'),
('2026-10-09', 'Best out of Waste', 'activity', '', 'prep'),
('2026-10-12', 'Show & Tell', 'activity', '', 'prep'),
('2026-10-19', 'Bhondala', 'activity', '', 'prep'),
('2026-10-20', 'Dasara', 'holiday', '', 'prep'),
('2026-10-26', 'Evaluation 2 (Nursery - Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2026-10-27', 'Evaluation 2 (Nursery - Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2026-10-28', 'Evaluation 2 (Nursery - Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2026-10-29', 'Evaluation 2 (Nursery - Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2026-10-30', 'Evaluation 2 (Nursery - Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2026-11-05', 'Science Exhibition', 'activity', '', 'prep'),
('2026-11-06', 'Open Day', 'activity', '', 'prep'),
('2026-11-08', 'Lakshmi Poojan', 'holiday', '', 'prep'),
('2026-11-09', 'Diwali Vacation', 'holiday', '', 'prep'),
('2026-11-10', 'Bali Pratipada', 'holiday', '', 'prep'),
('2026-11-11', 'Diwali Vacation', 'holiday', '', 'prep'),
('2026-11-12', 'Diwali Vacation', 'holiday', '', 'prep'),
('2026-11-13', 'Diwali Vacation', 'holiday', '', 'prep'),
('2026-11-16', 'School Reopens', 'activity', '', 'prep'),
('2026-11-24', 'Guru Nanak Jayanti', 'holiday', '', 'prep'),
('2026-11-30', 'Sports', 'sports', '', 'prep'),
('2026-12-01', 'Sports', 'sports', '', 'prep'),
('2026-12-02', 'Sports', 'sports', '', 'prep'),
('2026-12-22', 'Annual Day', 'activity', '', 'prep'),
('2026-12-25', 'Christmas', 'holiday', '', 'prep'),
('2026-12-28', 'Winter Vacation', 'holiday', '', 'prep'),
('2026-12-29', 'Winter Vacation', 'holiday', '', 'prep'),
('2026-12-30', 'Winter Vacation', 'holiday', '', 'prep'),
('2026-12-31', 'Winter Vacation', 'holiday', '', 'prep'),
('2027-01-01', 'Winter Vacation', 'holiday', '', 'prep'),
('2027-01-04', 'School Reopens', 'activity', '', 'prep'),
('2027-01-13', 'Kite Making Activity', 'activity', '', 'prep'),
('2027-01-15', 'Makar Sankranti', 'holiday', '', 'prep'),
('2027-01-26', 'Republic Day', 'holiday', '', 'prep'),
('2027-02-03', 'Orange Day', 'activity', '', 'prep'),
('2027-02-19', 'Chhatrapati Shivaji Maharaj Jayanti', 'holiday', '', 'prep'),
('2027-02-22', 'Evaluation 3 (Nursery - Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2027-02-23', 'Evaluation 3 (Nursery - Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2027-02-24', 'Evaluation 3 (Nursery - Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2027-02-25', 'Evaluation 3 (Nursery - Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2027-02-26', 'Evaluation 3 (Nursery - Prep 1 - Prep 2)', 'exam', '', 'prep'),
('2027-03-06', 'Maha Shivratri', 'holiday', '', 'prep'),
('2027-03-10', 'Ramzan Eid', 'holiday', '', 'prep'),
('2027-03-18', 'Annual Result', 'activity', '', 'prep'),
('2027-03-22', 'Holi', 'holiday', '', 'prep'),
('2027-03-23', 'Books Distribution', 'activity', '', 'prep'),
('2027-03-24', 'Books Distribution', 'activity', '', 'prep'),
('2027-03-25', 'Books Distribution', 'activity', '', 'prep'),
('2027-03-26', 'Good Friday', 'holiday', '', 'prep');

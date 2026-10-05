-- RTE (Right to Education quota) flag on students. The app tags these students with "RTE"
-- on the invoice, payment and fee-type pages; admins set it on the student add/edit form.
-- IDs below are matched from "RTE Student List.xlsx" (2026-27, Grade 1 + Grade 2, 46 students).
-- Run once on the application DB.

ALTER TABLE `student` ADD COLUMN `rte` TINYINT(1) NOT NULL DEFAULT 0;

-- Grade 1 (24)
-- Notes: 649 = "AIHAAN KHARBE LATIF" (sheet: Aihaan Abdullatif Kharbe);
--        2839 = "Zeeshan Shah" (sheet: Zeeshan Rahim Shaha, sibling) — probable match, please verify;
--        2806 = "BUDHISHA UTTAM OVHAL" is currently in class "REMOVED".
UPDATE `student` SET `rte` = 1 WHERE `studentID` IN (
    5, 649, 2795, 2815, 2806, 496, 2801, 2802, 2804, 12, 2803, 2798,
    2796, 2799, 2797, 489, 553, 2812, 27, 2800, 2484, 13, 2839, 6
);

-- Grade 2 (22)
-- Note: 2446 = "Ali Sahil Pendari" (sheet: Ali Pendhari).
UPDATE `student` SET `rte` = 1 WHERE `studentID` IN (
    2451, 34, 2442, 2432, 40, 2448, 2437, 2452, 38, 2546, 2390,
    2446, 2450, 68, 2443, 2547, 2622, 33, 2438, 2449, 99, 93
);

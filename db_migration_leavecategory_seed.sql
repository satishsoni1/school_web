-- Seed student leave types so the mobile "Apply for Leave" form has categories to choose from
-- (the leavecategory table was empty). Only inserts a category whose name doesn't exist yet.
-- Rename/add more under Leave Category; optionally set day quotas under Leave Assign (Student).

INSERT INTO `leavecategory` (`leavecategory`, `leavegender`, `create_date`, `modify_date`, `create_userID`, `create_usertypeID`)
SELECT * FROM (
    SELECT 'Sick Leave' AS leavecategory, 1 AS leavegender, NOW() AS create_date, NOW() AS modify_date, 1 AS create_userID, 1 AS create_usertypeID
    UNION ALL SELECT 'Casual Leave', 1, NOW(), NOW(), 1, 1
    UNION ALL SELECT 'Family Function', 1, NOW(), NOW(), 1, 1
    UNION ALL SELECT 'Medical / Emergency', 1, NOW(), NOW(), 1, 1
    UNION ALL SELECT 'Other', 1, NOW(), NOW(), 1, 1
) AS seed
WHERE NOT EXISTS (SELECT 1 FROM `leavecategory` lc WHERE lc.leavecategory = seed.leavecategory);

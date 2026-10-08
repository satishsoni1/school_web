<?php
/**
 * One-off clean-up (2026-10-08), as requested by the school:
 *   - Assignments (+ student answers), Syllabus files, Exam Schedule, Notices from BEFORE the running
 *     school year (setting.school_year), with their in-app notification history and notice read-markers.
 *   - All leave applications dated up to 2026-10-08 (test entries).
 *   - Exam timetable + exam portion (periodic_test_schedule / periodic_test_syllabus): emptied, to be
 *     re-entered from Admin → Exam → Exam Timetable / Exam Portion. Their `exam`/`grade` columns are
 *     added first (schema part of db_migration_exam_portion.sql) so manual entries keep the exam.
 *
 *   php deploy/clear_old_data.php           # DRY RUN: counts only, nothing changed
 *   php deploy/clear_old_data.php --apply   # back up every row to deploy/backups/clear_old_data_<ts>/, then delete
 *
 * Restore: each backup file is plain SQL (CREATE TABLE IF NOT EXISTS + INSERTs) — run it to put rows back.
 * Uploaded files (assignment/syllabus attachments) on the server are not touched.
 */

if (PHP_SAPI !== 'cli') {
    exit("CLI only\n");
}
$apply = in_array('--apply', $argv, true);

$webRoot = dirname(__DIR__);
define('BASEPATH', $webRoot . '/main/');
define('ENVIRONMENT', 'development');
$db = [];
include $webRoot . '/mvc/config/development/database.php';
$c = $db['default'];
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$m = new mysqli($c['hostname'], $c['username'], $c['password'], $c['database']);
$m->set_charset('utf8mb4');

$year = (int) $m->query("SELECT value FROM setting WHERE fieldoption = 'school_year'")->fetch_row()[0];
echo "Connected to {$c['database']} on {$c['hostname']} — running school year ID $year\n\n";

$ids = function ($sql) use ($m) {
    return array_map('intval', array_column($m->query($sql)->fetch_all(), 0));
};
$in = function (array $list) {
    return $list ? implode(',', $list) : '0';
};

// ── What will be deleted ─────────────────────────────────────────────────────
$assignmentIDs = $ids("SELECT assignmentID FROM assignment WHERE schoolyearID < $year");
$noticeIDs     = $ids("SELECT noticeID FROM notice WHERE schoolyearID < $year");
$leaveIDs      = $ids("SELECT leaveapplicationID FROM leaveapplications WHERE DATE(apply_date) <= '2026-10-08'");
$hasAlert      = (bool) $m->query("SHOW TABLES LIKE 'alert'")->num_rows;

// [label, table, WHERE]
$plan = [
    ['Assignment answers',  'assignmentanswer',      "schoolyearID < $year OR assignmentID IN (" . $in($assignmentIDs) . ")"],
    ['Assignments',         'assignment',            "assignmentID IN (" . $in($assignmentIDs) . ")"],
    ['Syllabus (files)',    'syllabus',              "schoolyearID < $year"],
    ['Exam schedule',       'examschedule',          "schoolyearID < $year"],
    ['Notices',             'notice',                "noticeID IN (" . $in($noticeIDs) . ")"],
    ['Leave applications',  'leaveapplications',     "leaveapplicationID IN (" . $in($leaveIDs) . ")"],
    ['Exam timetable',      'periodic_test_schedule', "1 = 1"],
    ['Exam portion',        'periodic_test_syllabus', "1 = 1"],
];
$notifWhere = "(type = 'notice' AND referenceID IN (" . $in($noticeIDs) . "))"
    . " OR (type = 'assignment' AND referenceID IN (" . $in($assignmentIDs) . "))"
    . " OR (type = 'leaveapplication' AND referenceID IN (" . $in($leaveIDs) . "))";
$notificationIDs = $ids("SELECT notificationID FROM notifications WHERE $notifWhere");
$plan[] = ['Notification recipients', 'notification_recipients', "notificationID IN (" . $in($notificationIDs) . ")"];
$plan[] = ['Notification history',    'notifications',           "notificationID IN (" . $in($notificationIDs) . ")"];
if ($hasAlert) {
    $plan[] = ['Notice read-markers', 'alert', "itemname = 'notice' AND itemID IN (" . $in($noticeIDs) . ")"];
}

foreach ($plan as &$p) {
    $p[3] = (int) $m->query("SELECT COUNT(*) FROM `{$p[1]}` WHERE {$p[2]}")->fetch_row()[0];
    printf("  %-26s %-26s %6d rows\n", $p[0], $p[1], $p[3]);
}
unset($p);

if (!$apply) {
    echo "\nDRY RUN — nothing changed. Re-run with --apply to back up and delete.\n";
    exit(0);
}

// ── Backup ───────────────────────────────────────────────────────────────────
$dir = __DIR__ . '/backups/clear_old_data_' . date('Ymd_His');
mkdir($dir, 0700, true);
foreach ($plan as $p) {
    if (!$p[3]) {
        continue;
    }
    $fh = fopen("$dir/{$p[1]}.sql", 'w');
    $create = $m->query("SHOW CREATE TABLE `{$p[1]}`")->fetch_row()[1];
    fwrite($fh, "-- Backup of {$p[3]} rows from `{$p[1]}` deleted " . date('c') . "\n"
        . preg_replace('/^CREATE TABLE/', 'CREATE TABLE IF NOT EXISTS', $create) . ";\n\n");
    $res = $m->query("SELECT * FROM `{$p[1]}` WHERE {$p[2]}", MYSQLI_USE_RESULT);
    while ($row = $res->fetch_assoc()) {
        $values = array_map(function ($v) use ($m) {
            return $v === null ? 'NULL' : "'" . $m->real_escape_string($v) . "'";
        }, $row);
        fwrite($fh, "INSERT INTO `{$p[1]}` (`" . implode('`,`', array_keys($row)) . "`) VALUES (" . implode(',', $values) . ");\n");
    }
    $res->free();
    fclose($fh);
}
echo "\nBackup written to $dir\n";

// ── Schema for manual exam entry (exam + grade columns) ─────────────────────
foreach (['periodic_test_schedule', 'periodic_test_syllabus'] as $table) {
    $m->query("ALTER TABLE `$table`
        ADD COLUMN IF NOT EXISTS `exam` VARCHAR(30) NOT NULL DEFAULT 'PT-1',
        ADD COLUMN IF NOT EXISTS `grade` TINYINT UNSIGNED NULL");
    $m->query("ALTER TABLE `$table` ADD INDEX IF NOT EXISTS `idx_grade_exam` (`grade`, `exam`)");
}
echo "Exam timetable/portion: exam + grade columns ensured.\n";

// ── Delete (one transaction) ─────────────────────────────────────────────────
$m->begin_transaction();
try {
    foreach ($plan as $p) {
        if ($p[3]) {
            $m->query("DELETE FROM `{$p[1]}` WHERE {$p[2]}");
            printf("  deleted %-26s %6d rows\n", $p[1], $m->affected_rows);
        }
    }
    $m->commit();
} catch (Throwable $e) {
    $m->rollback();
    fwrite(STDERR, "FAILED — rolled back, nothing deleted: {$e->getMessage()}\n");
    exit(1);
}
echo "Done.\n";

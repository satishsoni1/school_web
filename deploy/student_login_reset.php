<?php
/**
 * Set student logins to: username = mobile number, password = a common password.
 *
 *   php deploy/student_login_reset.php                      # DRY RUN: report only, nothing changes
 *   php deploy/student_login_reset.php --apply              # apply (backs up current logins first)
 *   php deploy/student_login_reset.php --restore=<backup.csv>   # undo using a backup file
 *
 * Options:
 *   --password=ppgmis   common password (default: ppgmis)
 *   --all               every student record, not only students enrolled in the running school year
 *   --env=development   DB config to use (mvc/config/<env>/database.php), same default as index.php
 *
 * Rules:
 *   - Mobile = student.phone, else the parent's phone; must be a valid 10-digit Indian mobile
 *     (+91 / leading 0 stripped). Students without one are left unchanged and listed.
 *   - Usernames stay unique across student/parents/teacher/user/systemadmin: siblings sharing
 *     a number get <mobile>, <mobile>-2, <mobile>-3 (oldest studentID first); a number already
 *     used as a NON-student login (e.g. a parent's username) gets the next free suffix too.
 *   - Passwords are stored as plain text because that is how this install's sign-in compares them
 *     (api/v10/Signin.php and Signin_m compare the raw value).
 *
 * Output: deploy/reports/student_login_<timestamp>.csv (always) and, on --apply,
 * deploy/backups/student_login_backup_<timestamp>.csv (old usernames + passwords, chmod 600 —
 * delete it once you're sure you won't need to undo).
 */

if (PHP_SAPI !== 'cli') {
    exit("CLI only\n");
}

$opts = ['apply' => false, 'all' => false, 'password' => 'ppgmis', 'env' => 'development', 'restore' => null];
foreach (array_slice($argv, 1) as $arg) {
    if ($arg === '--apply') {
        $opts['apply'] = true;
    } elseif ($arg === '--all') {
        $opts['all'] = true;
    } elseif (preg_match('/^--(password|env|restore)=(.*)$/', $arg, $m)) {
        $opts[$m[1]] = $m[2];
    } else {
        fwrite(STDERR, "Unknown option: $arg\n");
        exit(1);
    }
}
if ($opts['password'] === '') {
    fwrite(STDERR, "--password cannot be empty\n");
    exit(1);
}

// ── DB connection (same config the app uses) ────────────────────────────────
$webRoot = dirname(__DIR__);
define('BASEPATH', $webRoot . '/main/');
define('ENVIRONMENT', $opts['env']);
$db = [];
include $webRoot . "/mvc/config/{$opts['env']}/database.php";
$c = $db['default'];
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = new mysqli($c['hostname'], $c['username'], $c['password'], $c['database']);
$mysqli->set_charset('utf8mb4');
echo "Connected to {$c['database']} on {$c['hostname']}\n";

$stamp = date('Ymd_His');

// ── Restore mode ────────────────────────────────────────────────────────────
if ($opts['restore']) {
    $fh = fopen($opts['restore'], 'r');
    if (!$fh) {
        fwrite(STDERR, "Cannot open {$opts['restore']}\n");
        exit(1);
    }
    fgetcsv($fh, 0, ',', '"', '\\'); // header
    $stmt = $mysqli->prepare("UPDATE student SET username = ?, password = ? WHERE studentID = ?");
    $mysqli->begin_transaction();
    $n = 0;
    while (($row = fgetcsv($fh, 0, ',', '"', '\\')) !== false) {
        list($studentID, $username, $password) = $row;
        $stmt->bind_param('ssi', $username, $password, $studentID);
        $stmt->execute();
        $n++;
    }
    $mysqli->commit();
    echo "Restored $n student logins from {$opts['restore']}\n";
    exit(0);
}

// ── Load students ───────────────────────────────────────────────────────────
$schoolyearID = (int) $mysqli->query("SELECT value FROM setting WHERE fieldoption = 'school_year'")->fetch_row()[0];
$sql = "SELECT s.studentID, s.name, s.username, s.phone, s.parentID, p.phone AS parent_phone, c.classes
        FROM student s
        LEFT JOIN parents p ON p.parentsID = s.parentID
        LEFT JOIN studentrelation sr ON sr.srstudentID = s.studentID AND sr.srschoolyearID = $schoolyearID
        LEFT JOIN classes c ON c.classesID = sr.srclassesID";
if (!$opts['all']) {
    // Enrolled this year, excluding students parked in the "REMOVED" class (they have left).
    $sql .= " WHERE sr.srstudentID IS NOT NULL AND (c.classes IS NULL OR c.classes <> 'REMOVED')";
}
$sql .= " ORDER BY s.studentID";
$students = $mysqli->query($sql)->fetch_all(MYSQLI_ASSOC);
$scope = $opts['all'] ? 'all student records' : "students enrolled in school year $schoolyearID";
echo count($students) . " $scope\n";

/** First valid 10-digit Indian mobile in a free-text phone field ("98.../ 88..." allowed), or null. */
function mobile_of($phone)
{
    foreach (preg_split('/[\/,;|]+/', (string) $phone) as $part) {
        $digits = preg_replace('/\D/', '', $part);
        if (strlen($digits) === 12 && substr($digits, 0, 2) === '91') {
            $digits = substr($digits, 2);
        } elseif (strlen($digits) === 11 && $digits[0] === '0') {
            $digits = substr($digits, 1);
        }
        if (preg_match('/^[6-9]\d{9}$/', $digits)) {
            return $digits;
        }
    }
    return null;
}

// Usernames held by non-student accounts (must not be taken), and by students we are NOT changing.
$taken = [];
foreach (['parents', 'teacher', 'user', 'systemadmin'] as $table) {
    foreach ($mysqli->query("SELECT username FROM `$table` WHERE username <> ''")->fetch_all() as $r) {
        $taken[strtolower($r[0])] = $table;
    }
}
$changingIDs = array_flip(array_column($students, 'studentID'));
foreach ($mysqli->query("SELECT studentID, username FROM student WHERE username <> ''")->fetch_all() as $r) {
    if (!isset($changingIDs[$r[0]])) {
        $taken[strtolower($r[1])] = 'student (not in scope)';
    }
}

// ── Plan ────────────────────────────────────────────────────────────────────
$plan = [];
$stats = ['change' => 0, 'suffixed' => 0, 'no_mobile' => 0, 'from_parent' => 0];
foreach ($students as $s) {
    $mobile = mobile_of($s['phone']);
    $note = [];
    if (!$mobile && ($mobile = mobile_of($s['parent_phone']))) {
        $note[] = "mobile from parent";
        $stats['from_parent']++;
    }
    if (!$mobile) {
        $plan[] = $s + ['new_username' => '', 'status' => 'SKIPPED', 'note' => 'no valid mobile (student phone: ' . $s['phone'] . ')'];
        $stats['no_mobile']++;
        continue;
    }
    $username = $mobile;
    for ($i = 2; isset($taken[strtolower($username)]); $i++) {
        if ($i === 2) {
            $note[] = "number already used by " . $taken[strtolower($mobile)];
        }
        $username = "$mobile-$i";
    }
    if ($username !== $mobile) {
        $stats['suffixed']++;
    }
    $taken[strtolower($username)] = 'student';
    $plan[] = $s + ['new_username' => $username, 'status' => 'CHANGE', 'note' => implode('; ', $note)];
    $stats['change']++;
}

// ── Report ──────────────────────────────────────────────────────────────────
@mkdir(__DIR__ . '/reports', 0755, true);
$reportFile = __DIR__ . "/reports/student_login_$stamp.csv";
$fh = fopen($reportFile, 'w');
fputcsv($fh, ['studentID', 'name', 'class', 'old_username', 'new_username', 'new_password', 'status', 'note'], ',', '"', '\\');
foreach ($plan as $p) {
    fputcsv($fh, [$p['studentID'], $p['name'], $p['classes'], $p['username'], $p['new_username'],
        $p['status'] === 'CHANGE' ? $opts['password'] : '', $p['status'], $p['note']], ',', '"', '\\');
}
fclose($fh);

echo "\nPlan:\n";
echo "  will change          : {$stats['change']}\n";
echo "    using parent mobile: {$stats['from_parent']}\n";
echo "    with -2/-3 suffix  : {$stats['suffixed']}  (shared number / number already a login)\n";
echo "  skipped (no mobile)  : {$stats['no_mobile']}\n";
echo "  common password      : {$opts['password']}\n";
echo "Report: $reportFile\n";

if (!$opts['apply']) {
    echo "\nDRY RUN — nothing changed. Review the report, then re-run with --apply.\n";
    exit(0);
}

// ── Backup + apply ──────────────────────────────────────────────────────────
@mkdir(__DIR__ . '/backups', 0700, true);
$backupFile = __DIR__ . "/backups/student_login_backup_$stamp.csv";
$fh = fopen($backupFile, 'w');
fputcsv($fh, ['studentID', 'username', 'password'], ',', '"', '\\');
$toChange = array_filter($plan, function ($p) { return $p['status'] === 'CHANGE'; });
$ids = implode(',', array_map('intval', array_column($toChange, 'studentID')));
foreach ($mysqli->query("SELECT studentID, username, password FROM student WHERE studentID IN ($ids)")->fetch_all() as $r) {
    fputcsv($fh, $r, ',', '"', '\\');
}
fclose($fh);
chmod($backupFile, 0600);
echo "Backup of current logins: $backupFile\n";

$stmt = $mysqli->prepare("UPDATE student SET username = ?, password = ? WHERE studentID = ?");
$mysqli->begin_transaction();
try {
    foreach ($toChange as $p) {
        $stmt->bind_param('ssi', $p['new_username'], $opts['password'], $p['studentID']);
        $stmt->execute();
    }
    $mysqli->commit();
} catch (Throwable $e) {
    $mysqli->rollback();
    fwrite(STDERR, "FAILED, rolled back — nothing changed: {$e->getMessage()}\n");
    exit(1);
}
echo "Updated " . count($toChange) . " student logins.\n";
echo "To undo: php deploy/student_login_reset.php --restore=$backupFile\n";

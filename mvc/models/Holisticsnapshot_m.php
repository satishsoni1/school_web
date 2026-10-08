<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Holistic report card snapshots: one row per student per school year holding a frozen copy
 * of the master data the report shows (student info, class, section, school year, class
 * teacher name). The student photo and teacher sign are COPIED into
 * uploads/holistic_snapshots/<schoolyearID>/ so later changes/deletions don't alter old reports.
 *
 * The snapshot is written when a report is saved or generated in the admin portal
 * (Holisticreport). The public/mobile report (PublicHolisticReport) only ever reads it.
 */
class Holisticsnapshot_m extends MY_Model
{
    protected $_table_name    = 'holistic_report_snapshot';
    protected $_primary_key   = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by      = 'schoolyearID desc';

    const SNAPSHOT_DIR = 'uploads/holistic_snapshots/';
    const DEFAULT_SIGN = 'assets/sign/17.png';

    function __construct()
    {
        parent::__construct();
    }

    public function get_single_snapshot($array)
    {
        return $this->get_single($array);
    }

    public function get_order_by_snapshot($array = NULL)
    {
        return $this->get_order_by($array);
    }

    /** Snapshots (all school years) for a set of students, newest year first. */
    public function get_snapshots_for_students($studentIDs)
    {
        if (!customCompute($studentIDs)) {
            return [];
        }
        $this->db->where_in('studentID', $studentIDs);
        $this->db->order_by('schoolyearID desc, studentID asc');
        return $this->db->get($this->_table_name)->result();
    }

    /** Which report-card template (generate_report_N) a class uses. */
    public static function report_method($classesID)
    {
        $classesID = (int) $classesID;
        if (in_array($classesID, [1, 2, 12, 3, 17])) {
            return 'generate_report_1';
        } elseif (in_array($classesID, [4, 11, 23, 5, 13, 24])) {
            return 'generate_report_4';
        } elseif (in_array($classesID, [6, 15])) {
            return 'generate_report_5';
        } elseif (in_array($classesID, [7, 8, 16, 20])) {
            return 'generate_report_6';
        }
        return 'generate_report_4';
    }

    /**
     * Return the snapshot for this student/year, (re)building it from master data when it
     * does not exist yet or when $refresh is true (the running school year). A past year's
     * existing snapshot is returned untouched.
     */
    public function sync($studentID, $classesID, $schoolyearID, $refresh)
    {
        $this->load->model('studentrelation_m');
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('schoolyear_m');
        $this->load->model('teacherclasses_m');

        $where    = array('studentID' => $studentID, 'schoolyearID' => $schoolyearID);
        $snapshot = $this->get_single_snapshot($where);
        if (customCompute($snapshot) && !$refresh) {
            return $snapshot;
        }

        // Unscoped lookup of this exact student/year/class: the scoped get_single_student() limits
        // a student login to this year's class, which would hide last year's report.
        $student = $this->studentrelation_m->general_get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
            'srclassesID'    => $classesID,
        ));
        if (!customCompute($student)) {
            return $snapshot;
        }

        // Unscoped lookups: the scoped versions limit a student/parent login to this year's class
        // (and a teacher to their own sections), which left last year's class blank.
        $classes    = $this->classes_m->general_get_single_classes(array('classesID' => $classesID));
        $section    = $this->section_m->general_get_single_section(array('sectionID' => $student->srsectionID));
        $schoolyear = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID' => $schoolyearID));
        list($teacherSign, $teacherName) = $this->teacherclasses_m->get_single_teacher_name($classesID);

        $dir = self::SNAPSHOT_DIR . $schoolyearID;

        $photo = null;
        if (!empty($student->photo) && $student->photo != 'default.png') {
            $photo = $this->copy_file('uploads/images/' . $student->photo, $dir, $studentID . '_photo_' . basename($student->photo));
        }
        $yearTeachers = $this->year_teachers($schoolyearID, $classesID);
        if ($yearTeachers) {
            // That year's class teacher(s) from config/holistic_year_teachers.php — never today's master.
            $teacherName = $yearTeachers['name'];
            $teacherSign = $yearTeachers['sign'];
        } else {
            $teacherSign = $teacherSign ?: self::DEFAULT_SIGN;
            $teacherSign = $this->copy_file($teacherSign, $dir, 'class' . $classesID . '_sign_' . basename($teacherSign)) ?: $teacherSign;
        }

        // A replaced student photo is no longer referenced by this snapshot — remove the old copy.
        if (customCompute($snapshot) && !empty($snapshot->photo) && $snapshot->photo != $photo && strpos($snapshot->photo, $dir . '/') === 0) {
            @unlink(FCPATH . $snapshot->photo);
        }

        $studentData = (array) $student;
        unset($studentData['password'], $studentData['username']);

        $row = array(
            'studentID'       => $studentID,
            'schoolyearID'    => $schoolyearID,
            'classesID'       => $classesID,
            'student_data'    => json_encode($studentData),
            'classes_data'    => customCompute($classes) ? json_encode($classes) : null,
            'section_data'    => customCompute($section) ? json_encode($section) : null,
            'schoolyear_data' => customCompute($schoolyear) ? json_encode($schoolyear) : null,
            'photo'           => $photo,
            'teacher_name'    => $teacherName ?: 'Class Teacher',
            'teacher_sign'    => $teacherSign,
            'updated_at'      => date('Y-m-d H:i:s'),
        );

        if (customCompute($snapshot)) {
            $this->update($row, $snapshot->id);
        } else {
            $row['created_at'] = $row['updated_at'];
            $this->insert($row);
        }

        return $this->get_single_snapshot($where);
    }

    /**
     * Use the student's details for THAT school year: studentrelation has one row per student per
     * year (class, roll, register no, section, group, optional subject), while the student master
     * table only holds today's values. Everything studentrelation records is taken from it EXCEPT
     * the name, which stays from the master record (srname holds the short/uncorrected name entered
     * that year). Photo, DOB, parents, address and phone are not stored per year.
     */
    public static function apply_year_details($student)
    {
        $map = array(
            'classesID'         => 'srclassesID',
            'classes'           => 'srclasses',
            'roll'              => 'srroll',
            'registerNO'        => 'srregisterNO',
            'sectionID'         => 'srsectionID',
            'section'           => 'srsection',
            'studentgroupID'    => 'srstudentgroupID',
            'optionalsubjectID' => 'sroptionalsubjectID',
        );
        foreach ($map as $field => $yearField) {
            if (isset($student->$yearField) && $student->$yearField !== '' && $student->$yearField !== null) {
                $student->$field = $student->$yearField;
            }
        }
        return $student;
    }

    /**
     * Snapshots built from the app before the unscoped lookups were used can lack the class/section
     * row (blank class name). Fill the gap from the class/section tables — a class's name does not
     * change by year — and save it so it happens once. Nothing else in the snapshot is touched.
     */
    private function repair_class_section($snapshot, $student)
    {
        $update = array();
        if (empty($snapshot->classes_data)) {
            $this->load->model('classes_m');
            $classes = $this->classes_m->general_get_single_classes(array('classesID' => $snapshot->classesID));
            if (customCompute($classes)) {
                $snapshot->classes_data = $update['classes_data'] = json_encode($classes);
            }
        }
        if (empty($snapshot->section_data) && !empty($student->srsectionID)) {
            $this->load->model('section_m');
            $section = $this->section_m->general_get_single_section(array('sectionID' => $student->srsectionID));
            if (customCompute($section)) {
                $snapshot->section_data = $update['section_data'] = json_encode($section);
            }
        }
        if ($update) {
            $this->db->where('id', $snapshot->id)->update($this->_table_name, $update);
        }
    }

    /**
     * The class teacher(s) configured for a past year/class in config/holistic_year_teachers.php,
     * as ['name' => "A, B", 'sign' => "copyA,copyB"] with each signature COPIED from the teacher
     * master into that year's snapshot folder. Null when the year/class is not configured.
     */
    public function year_teachers($schoolyearID, $classesID)
    {
        $this->config->load('holistic_year_teachers', TRUE, TRUE);
        $all = $this->config->item('holistic_year_teachers', 'holistic_year_teachers');
        if (empty($all[$schoolyearID][$classesID])) {
            return null;
        }
        $dir   = self::SNAPSHOT_DIR . (int) $schoolyearID;
        $names = array();
        $signs = array();
        foreach ($all[$schoolyearID][$classesID] as $teacher) {
            list($teacherID, $printName) = $teacher;
            $names[] = $printName;
            $master  = $this->db->get_where('teacher', array('teacherID' => $teacherID))->row();
            $source  = ($master && $master->sign) ? $master->sign : self::DEFAULT_SIGN;
            $copy    = $this->copy_file($source, $dir, 'teacher' . (int) $teacherID . '_sign_' . basename($source));
            if ($copy) {
                $signs[] = $copy;
            }
        }
        return array(
            'name' => implode(', ', $names),
            'sign' => $signs ? implode(',', $signs) : self::DEFAULT_SIGN,
        );
    }

    /**
     * A past-year snapshot whose teacher differs from the configured class teacher(s) (e.g. it was
     * built from today's master teacher) is corrected and saved, once.
     */
    private function repair_year_teachers($snapshot)
    {
        $this->config->load('holistic_year_teachers', TRUE, TRUE);
        $all = $this->config->item('holistic_year_teachers', 'holistic_year_teachers');
        if (empty($all[$snapshot->schoolyearID][$snapshot->classesID])) {
            return;
        }
        $expected = implode(', ', array_column($all[$snapshot->schoolyearID][$snapshot->classesID], 1));
        if ($snapshot->teacher_name === $expected) {
            return;
        }
        $teachers = $this->year_teachers($snapshot->schoolyearID, $snapshot->classesID);
        $snapshot->teacher_name = $teachers['name'];
        $snapshot->teacher_sign = $teachers['sign'];
        $this->db->where('id', $snapshot->id)->update($this->_table_name, array(
            'teacher_name' => $teachers['name'],
            'teacher_sign' => $teachers['sign'],
        ));
    }

    /**
     * View variables for a report card built purely from a snapshot:
     * classesID, student, classes, section, schoolyear, student_photo_path, teacher_name, teacher_sign.
     * Returns null when there is no usable snapshot.
     */
    public function report_context($snapshot)
    {
        if (!customCompute($snapshot)) {
            return null;
        }
        $student = json_decode($snapshot->student_data);
        if (!is_object($student)) {
            return null;
        }
        // The views build the photo URL with pdfimagelink(photo, folder).
        $student->photo = !empty($snapshot->photo) ? basename($snapshot->photo) : null;
        self::apply_year_details($student);
        $this->repair_class_section($snapshot, $student);
        $this->repair_year_teachers($snapshot);

        return array(
            'classesID'          => (int) $snapshot->classesID,
            'student'            => $student,
            'student_photo_path' => !empty($snapshot->photo) ? dirname($snapshot->photo) : null,
            'classes'            => $snapshot->classes_data ? json_decode($snapshot->classes_data) : null,
            'section'            => $snapshot->section_data ? json_decode($snapshot->section_data) : null,
            'schoolyear'         => $snapshot->schoolyear_data ? json_decode($snapshot->schoolyear_data) : null,
            'teacher_name'       => $snapshot->teacher_name ?: 'Class Teacher',
            'teacher_sign'       => $snapshot->teacher_sign ?: self::DEFAULT_SIGN,
        );
    }

    /**
     * Put the given class teachers (names + a COPY of their signature from the teacher table) on
     * every saved report of $schoolyearID, per class. Missing snapshots are created first. Because
     * the names and copied files live in the snapshot, later teacher-master changes never alter
     * these report cards.
     *
     * @param array $mapping [classesID => [[teacherID, name to print], ...]]
     * @param bool  $apply   false = preview only (nothing written or copied)
     * @return array per-class plan/result rows
     */
    public function assign_year_teachers($schoolyearID, array $mapping, $apply)
    {
        $this->load->model('classes_m');
        $blankSign = is_file(FCPATH . self::DEFAULT_SIGN) ? md5_file(FCPATH . self::DEFAULT_SIGN) : null;
        $dir       = self::SNAPSHOT_DIR . (int) $schoolyearID;
        $result    = array();

        foreach ($mapping as $classesID => $teachers) {
            $class = $this->classes_m->general_get_single_classes(array('classesID' => $classesID));
            $row = array(
                'classesID' => $classesID,
                'class'     => customCompute($class) ? $class->classes : ('Class ' . $classesID),
                'teachers'  => array(),
                'reports'   => 0,
                'created'   => 0,
                'updated'   => 0,
            );

            $names = array();
            $signs = array();
            foreach ($teachers as $teacher) {
                list($teacherID, $printName) = $teacher;
                $master = $this->db->get_where('teacher', array('teacherID' => $teacherID))->row();
                $source = ($master && $master->sign) ? $master->sign : self::DEFAULT_SIGN;
                $exists = is_file(FCPATH . $source);
                $info = array(
                    'teacherID' => $teacherID,
                    'name'      => $printName,
                    'master'    => $master ? $master->name : '(not found)',
                    'source'    => $source,
                    'missing'   => !$exists,
                    'blank'     => $exists && $blankSign && md5_file(FCPATH . $source) === $blankSign,
                    'copy'      => '',
                );
                if ($apply && $exists) {
                    $info['copy'] = $this->copy_file($source, $dir, 'teacher' . (int) $teacherID . '_sign_' . basename($source)) ?: $source;
                    $signs[] = $info['copy'];
                } elseif ($exists) {
                    $signs[] = $source;
                }
                $names[] = $printName;
                $row['teachers'][] = $info;
            }

            $reports = $this->db->select('studentID')
                ->where(array('schoolyearID' => $schoolyearID, 'classesID' => $classesID))
                ->get('holisticprogress')->result();
            $row['reports'] = count($reports);

            if ($apply) {
                foreach ($reports as $report) {
                    $existing = $this->get_single_snapshot(array('studentID' => $report->studentID, 'schoolyearID' => $schoolyearID));
                    $snapshot = $existing ?: $this->sync((int) $report->studentID, (int) $classesID, (int) $schoolyearID, false);
                    if (!customCompute($snapshot)) {
                        continue;
                    }
                    if (!$existing) {
                        $row['created']++;
                    }
                    $this->update(array(
                        'teacher_name' => implode(', ', $names),
                        'teacher_sign' => implode(',', $signs) ?: self::DEFAULT_SIGN,
                        'updated_at'   => date('Y-m-d H:i:s'),
                    ), $snapshot->id);
                    $row['updated']++;
                }
            }
            $result[] = $row;
        }
        return $result;
    }

    /**
     * Copy a file (path relative to the web root) into the snapshot folder.
     * Returns the copy's path relative to the web root, the source path if the copy
     * could not be made, or null if the source does not exist.
     */
    private function copy_file($source, $dir, $fileName)
    {
        $sourceFull = FCPATH . $source;
        if (!is_file($sourceFull)) {
            return null;
        }
        $fileName   = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $fileName);
        $target     = $dir . '/' . $fileName;
        $targetFull = FCPATH . $target;

        if (is_file($targetFull) && md5_file($targetFull) === md5_file($sourceFull)) {
            return $target;
        }
        if (!is_dir(FCPATH . $dir) && !@mkdir(FCPATH . $dir, 0755, true)) {
            return $source;
        }
        return @copy($sourceFull, $targetFull) ? $target : $source;
    }
}

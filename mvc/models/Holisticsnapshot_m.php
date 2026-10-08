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

        $classes    = $this->classes_m->get_single_classes(array('classesID' => $classesID));
        $section    = $this->section_m->get_single_section(array('sectionID' => $student->srsectionID));
        $schoolyear = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID' => $schoolyearID));
        list($teacherSign, $teacherName) = $this->teacherclasses_m->get_single_teacher_name($classesID);

        $dir = self::SNAPSHOT_DIR . $schoolyearID;

        $photo = null;
        if (!empty($student->photo) && $student->photo != 'default.png') {
            $photo = $this->copy_file('uploads/images/' . $student->photo, $dir, $studentID . '_photo_' . basename($student->photo));
        }
        $teacherSign = $teacherSign ?: self::DEFAULT_SIGN;
        $teacherSign = $this->copy_file($teacherSign, $dir, 'class' . $classesID . '_sign_' . basename($teacherSign)) ?: $teacherSign;

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

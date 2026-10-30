<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Exam timetable + portion (syllabus) for the app's "Exams" page.
 *
 * Rows in periodic_test_schedule / periodic_test_syllabus are keyed by GRADE (1-10) and EXAM
 * ('PT-1', 'Half Yearly', 'PT-2', 'Annual'; Nursery / Prep: 'Evaluation 1'-'Evaluation 4', see
 * Periodictest_m::exams_for()); every section of a grade (Grade 1 A/B/C) shares them.
 * The grade is read from the class name ("Grade 1 A" -> 1); Nursery / Prep 1 / Prep 2 use 101 / 102 / 103.
 * Until db_migration_exam_portion.sql adds the `grade`/`exam` columns, the legacy `classesID`
 * column (which already held the grade number) is used and every row counts as PT-1.
 */
class Periodictest extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('periodictest_m');
    }

    /**
     * GET /api/v10/periodictest/schedule/$classesID
     */
    public function schedule_get($classID = null)
    {
        $this->respond('periodic_test_schedule', 'schedules', $classID, ['test_date' => 'ASC', 'id' => 'ASC']);
    }

    /**
     * GET /api/v10/periodictest/syllabus/$classesID
     */
    public function syllabus_get($classID = null)
    {
        $this->respond('periodic_test_syllabus', 'syllabuss', $classID, ['id' => 'ASC']);
    }

    private function respond($table, $key, $classID, array $orderBy)
    {
        $classes  = $this->db->get('classes')->result_array();
        $classMap = array_column($classes, 'classes', 'classesID');

        $classID = (int) $classID ?: $this->studentClassID();
        $grade   = $classID && isset($classMap[$classID]) ? $this->gradeOf($classMap[$classID]) : null;

        $rows = [];
        if ($grade !== null) {
            $hasGrade = $this->db->field_exists('grade', $table);
            $this->db->from($table);
            $this->db->where($hasGrade ? 'grade' : 'classesID', $grade);
            foreach ($orderBy as $column => $direction) {
                $this->db->order_by($column, $direction);
            }
            $rows = $this->db->get()->result_array();
        }

        $examOrder = array_flip(Periodictest_m::all_exams());
        foreach ($rows as &$row) {
            $row['exam']       = isset($row['exam']) && $row['exam'] !== '' ? $row['exam'] : 'PT-1';
            $row['grade']      = $grade;
            $row['class_name'] = Periodictest_m::label($grade);
        }
        unset($row);
        // Keep the original order within an exam; exams in calendar order.
        usort($rows, function ($a, $b) use ($examOrder) {
            $ea = isset($examOrder[$a['exam']]) ? $examOrder[$a['exam']] : 99;
            $eb = isset($examOrder[$b['exam']]) ? $examOrder[$b['exam']] : 99;
            return $ea - $eb;
        });

        $this->retdata['classes'] = $classes;
        $this->retdata['grade']   = $grade;
        $this->retdata['exams']   = array_values(array_unique(array_column($rows, 'exam')));
        $this->retdata[$key]      = $rows;

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    /** The logged-in student's class for the current school year (0 if not a student). */
    private function studentClassID()
    {
        if ($this->session->userdata('usertypeID') != 3) {
            return 0;
        }
        $relation = $this->db->get_where('studentrelation', array(
            'srstudentID'    => $this->session->userdata('loginuserID'),
            'srschoolyearID' => $this->session->userdata('defaultschoolyearID'),
        ))->row();
        if ($relation) {
            return (int) $relation->srclassesID;
        }
        $student = $this->db->get_where('student', array('studentID' => $this->session->userdata('loginuserID')))->row();
        return $student ? (int) $student->classesID : 0;
    }

    /** "Grade 1 A" -> 1, "PREP 1 A" -> 102, "NURSERY" -> 101; null for other classes. */
    private function gradeOf($className)
    {
        return Periodictest_m::grade_of_class($className);
    }
}

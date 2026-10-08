<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Exam timetable (periodic_test_schedule) and exam portion (periodic_test_syllabus) rows.
 * Both are keyed by GRADE (1-10) and EXAM; every section of a grade shares them and the app
 * matches a student by the grade in their class name ("Grade 1 A" -> 1). `classesID` is kept
 * equal to the grade for older rows/app versions. See db_migration_exam_portion.sql.
 */
class Periodictest_m extends CI_Model
{
    const SCHEDULE = 'periodic_test_schedule';
    const SYLLABUS = 'periodic_test_syllabus';

    public static $exams  = array('PT-1', 'Half Yearly', 'PT-2', 'Annual');
    public static $grades = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

    /** True once the grade/exam columns exist (db_migration_exam_portion.sql). */
    public function has_exam_columns($table)
    {
        return $this->db->field_exists('exam', $table) && $this->db->field_exists('grade', $table);
    }

    /** Rows of $table, optionally for one grade and/or exam, decorated with grade/exam/class_name. */
    public function get_rows($table, $grade = 0, $exam = '', array $orderBy = array('id' => 'ASC'))
    {
        $hasColumns = $this->has_exam_columns($table);
        if ($grade) {
            $this->db->where($hasColumns ? 'grade' : 'classesID', (int) $grade);
        }
        if ($exam !== '' && $hasColumns) {
            $this->db->where('exam', $exam);
        }
        foreach ($orderBy as $column => $direction) {
            $this->db->order_by($column, $direction);
        }
        return $this->decorate($this->db->get($table)->result());
    }

    public function get_row($table, $id)
    {
        $row = $this->db->get_where($table, array('id' => (int) $id))->row();
        return $row ? current($this->decorate(array($row))) : null;
    }

    /** Insert ($id null) or update a row; $data holds the table's own fields. */
    public function save($table, array $data, $grade, $exam, $id = null)
    {
        $data['classesID'] = (int) $grade;
        if ($this->has_exam_columns($table)) {
            $data['grade'] = (int) $grade;
            $data['exam']  = $exam;
        }
        if ($id) {
            $this->db->where('id', (int) $id)->update($table, $data);
            return (int) $id;
        }
        $this->db->insert($table, $data);
        return (int) $this->db->insert_id();
    }

    public function delete_row($table, $id)
    {
        $this->db->where('id', (int) $id)->delete($table);
    }

    public static function valid_grade_exam($grade, $exam)
    {
        return in_array((int) $grade, self::$grades, true) && in_array($exam, self::$exams, true);
    }

    private function decorate(array $rows)
    {
        foreach ($rows as $row) {
            $row->grade      = isset($row->grade) && $row->grade ? (int) $row->grade : (int) $row->classesID;
            $row->exam       = isset($row->exam) && $row->exam !== '' ? $row->exam : 'PT-1';
            $row->class_name = 'Grade ' . $row->grade;
        }
        return $rows;
    }
}

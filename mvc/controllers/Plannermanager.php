<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Admin-only manager for the Academic Planner (academic_planner) and the
// Periodic Test schedule + syllabus (periodic_test_schedule / periodic_test_syllabus).
// The mobile app reads these same tables live via api/v10/academicplanner and
// api/v10/periodictest, so anything saved here shows up for students on their next
// pull-to-refresh / login.
// Reachable at: <site>/plannermanager/index
class Plannermanager extends Admin_Controller
{
    private $plannerTypes = array('holiday', 'activity', 'sports', 'exam', 'test', 'event');

    // Exam timetable/portion rows are keyed by GRADE (1-10) and EXAM; the app shows a student
    // the rows of their grade (from the class name "Grade 1 A"). `classesID` is kept = grade for
    // older rows. See db_migration_exam_portion.sql.
    private $exams  = array('PT-1', 'Half Yearly', 'PT-2', 'Annual');

    // Academic planner audiences: Grades 1-10 vs Nursery/Prep (academic_planner.audience).
    private $plannerAudiences = array('grade' => 'Grades 1-10', 'prep' => 'Pre-Primary (Nursery / Prep)');
    private $grades = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10);

    function __construct()
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->database();
    }

    private function requireAdmin()
    {
        if ($this->session->userdata('usertypeID') != 1) {
            $this->data['subview'] = 'error';
            $this->load->view('_layout_main', $this->data);
            return false;
        }
        return true;
    }

    /** True once db_migration_exam_portion.sql has added the grade/exam columns. */
    private function hasExamColumns($table)
    {
        return $this->db->field_exists('exam', $table) && $this->db->field_exists('grade', $table);
    }

    /** Grade + exam from the posted form, or null when invalid. */
    private function postedGradeExam()
    {
        $grade = (int) $this->input->post('grade');
        $exam  = (string) $this->input->post('exam');
        if (!in_array($grade, $this->grades) || !in_array($exam, $this->exams)) {
            return null;
        }
        return array($grade, $exam);
    }

    /** Columns identifying a row's grade/exam for insert/update. */
    private function gradeExamColumns($table, $grade, $exam)
    {
        $columns = array('classesID' => $grade);
        if ($this->hasExamColumns($table)) {
            $columns['grade'] = $grade;
            $columns['exam']  = $exam;
        }
        return $columns;
    }

    /** Apply the index page's ?grade= / ?exam= filters to a periodic_test_* query. */
    private function filterGradeExam($table, $grade, $exam)
    {
        $hasColumns = $this->hasExamColumns($table);
        if ($grade) {
            $this->db->where($hasColumns ? 'grade' : 'classesID', $grade);
        }
        if ($exam && $hasColumns) {
            $this->db->where('exam', $exam);
        }
    }

    private function decorate($rows)
    {
        foreach ($rows as $row) {
            $row->grade = isset($row->grade) && $row->grade ? $row->grade : $row->classesID;
            $row->exam  = isset($row->exam) && $row->exam !== '' ? $row->exam : 'PT-1';
            $row->class_name = 'Grade ' . $row->grade;
        }
        return $rows;
    }

    public function index()
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $hasAudience = $this->db->field_exists('audience', 'academic_planner');
        $filterPlanner = isset($this->plannerAudiences[$this->input->get('planner')]) ? $this->input->get('planner') : '';
        if ($hasAudience && $filterPlanner) {
            $this->db->where('audience', $filterPlanner);
        }
        $this->db->order_by('event_date', 'ASC');
        $this->data['planner_events'] = $this->db->get('academic_planner')->result();
        $this->data['hasAudience'] = $hasAudience;
        $this->data['filterPlanner'] = $filterPlanner;
        $this->data['plannerAudiences'] = $this->plannerAudiences;

        $filterGrade = (int) $this->input->get('grade');
        $filterExam  = in_array($this->input->get('exam'), $this->exams) ? $this->input->get('exam') : '';

        $this->filterGradeExam('periodic_test_schedule', $filterGrade, $filterExam);
        $this->db->order_by('test_date', 'ASC');
        $this->data['test_schedules'] = $this->decorate($this->db->get('periodic_test_schedule')->result());

        $this->filterGradeExam('periodic_test_syllabus', $filterGrade, $filterExam);
        $this->db->order_by('id', 'ASC');
        $this->data['test_syllabus'] = $this->decorate($this->db->get('periodic_test_syllabus')->result());

        $this->data['filterGrade'] = $filterGrade;
        $this->data['filterExam']  = $filterExam;
        $this->data['grades']      = $this->grades;
        $this->data['exams']       = $this->exams;
        $this->data['plannerTypes'] = $this->plannerTypes;
        $this->data['subview'] = 'plannermanager/index';
        $this->load->view('_layout_main', $this->data);
    }

    // ---- Academic Planner -------------------------------------------------

    public function planner_add()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $eventDate = $this->input->post('event_date');
        $title = trim((string) $this->input->post('title'));
        $type = $this->input->post('type');
        $description = trim((string) $this->input->post('description'));

        if ($this->validPlanner($eventDate, $title, $type)) {
            $row = array(
                'event_date'  => date('Y-m-d', strtotime($eventDate)),
                'title'       => $title,
                'type'        => $type,
                'description' => $description,
            );
            if ($this->db->field_exists('audience', 'academic_planner')) {
                // "both" adds the event to each planner.
                $audience = $this->input->post('audience');
                $targets = $audience === 'both' ? array_keys($this->plannerAudiences)
                    : array(isset($this->plannerAudiences[$audience]) ? $audience : 'grade');
                foreach ($targets as $target) {
                    $this->db->insert('academic_planner', $row + array('audience' => $target));
                }
            } else {
                $this->db->insert('academic_planner', $row);
            }
            $this->session->set_flashdata('success', 'Planner event added.');
        } else {
            $this->session->set_flashdata('error', 'Please provide a valid date, title and type.');
        }
        redirect(base_url('plannermanager/index'));
    }

    public function planner_edit($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $id = (int) $id;
        $row = $this->db->get_where('academic_planner', array('id' => $id))->row();
        if (!customCompute($row)) {
            $this->data['subview'] = 'error';
            $this->load->view('_layout_main', $this->data);
            return;
        }

        if ($this->input->post()) {
            $eventDate = $this->input->post('event_date');
            $title = trim((string) $this->input->post('title'));
            $type = $this->input->post('type');
            $description = trim((string) $this->input->post('description'));
            if ($this->validPlanner($eventDate, $title, $type)) {
                $update = array(
                    'event_date'  => date('Y-m-d', strtotime($eventDate)),
                    'title'       => $title,
                    'type'        => $type,
                    'description' => $description,
                );
                $audience = $this->input->post('audience');
                if (isset($this->plannerAudiences[$audience]) && $this->db->field_exists('audience', 'academic_planner')) {
                    $update['audience'] = $audience;
                }
                $this->db->where('id', $id)->update('academic_planner', $update);
                $this->session->set_flashdata('success', 'Planner event updated.');
                redirect(base_url('plannermanager/index'));
                return;
            }
            $this->session->set_flashdata('error', 'Please provide a valid date, title and type.');
        }

        $this->data['event'] = $row;
        $this->data['plannerTypes'] = $this->plannerTypes;
        $this->data['plannerAudiences'] = $this->plannerAudiences;
        $this->data['subview'] = 'plannermanager/planner_edit';
        $this->load->view('_layout_main', $this->data);
    }

    public function planner_delete($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $this->db->where('id', (int) $id)->delete('academic_planner');
        $this->session->set_flashdata('success', 'Planner event deleted.');
        redirect(base_url('plannermanager/index'));
    }

    private function validPlanner($eventDate, $title, $type)
    {
        return $eventDate && strtotime($eventDate) && $title !== '' && in_array($type, $this->plannerTypes, true);
    }

    // ---- Periodic Test schedule ----------------------------------------------

    public function test_add()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $testDate = $this->input->post('test_date');
        $gradeExam = $this->postedGradeExam();
        $subject = trim((string) $this->input->post('subject'));

        if ($testDate && strtotime($testDate) && $gradeExam && $subject !== '') {
            $this->db->insert('periodic_test_schedule', array(
                'test_date' => date('Y-m-d', strtotime($testDate)),
                'day'       => strtoupper(date('l', strtotime($testDate))),
                'subject'   => $subject,
            ) + $this->gradeExamColumns('periodic_test_schedule', $gradeExam[0], $gradeExam[1]));
            $this->session->set_flashdata('success', 'Exam timetable row added.');
        } else {
            $this->session->set_flashdata('error', 'Please provide a valid date, grade, exam and subject.');
        }
        redirect(base_url('plannermanager/index'));
    }

    public function test_edit($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $id = (int) $id;
        $row = $this->db->get_where('periodic_test_schedule', array('id' => $id))->row();
        if (!customCompute($row)) {
            $this->data['subview'] = 'error';
            $this->load->view('_layout_main', $this->data);
            return;
        }

        if ($this->input->post()) {
            $testDate = $this->input->post('test_date');
            $gradeExam = $this->postedGradeExam();
            $subject = trim((string) $this->input->post('subject'));
            if ($testDate && strtotime($testDate) && $gradeExam && $subject !== '') {
                $this->db->where('id', $id)->update('periodic_test_schedule', array(
                    'test_date' => date('Y-m-d', strtotime($testDate)),
                    'day'       => strtoupper(date('l', strtotime($testDate))),
                    'subject'   => $subject,
                ) + $this->gradeExamColumns('periodic_test_schedule', $gradeExam[0], $gradeExam[1]));
                $this->session->set_flashdata('success', 'Exam timetable row updated.');
                redirect(base_url('plannermanager/index'));
                return;
            }
            $this->session->set_flashdata('error', 'Please provide a valid date, grade, exam and subject.');
        }

        $this->data['schedule'] = current($this->decorate(array($row)));
        $this->data['grades'] = $this->grades;
        $this->data['exams'] = $this->exams;
        $this->data['subview'] = 'plannermanager/test_edit';
        $this->load->view('_layout_main', $this->data);
    }

    public function test_delete($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $this->db->where('id', (int) $id)->delete('periodic_test_schedule');
        $this->session->set_flashdata('success', 'Test schedule row deleted.');
        redirect(base_url('plannermanager/index'));
    }

    // ---- Periodic Test syllabus --------------------------------------------

    public function syllabus_add()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $gradeExam = $this->postedGradeExam();
        $subject = trim((string) $this->input->post('subject'));
        $syllabus = trim((string) $this->input->post('syllabus'));

        if ($gradeExam && $subject !== '' && $syllabus !== '') {
            $this->db->insert('periodic_test_syllabus', array(
                'subject'   => $subject,
                'syllabus'  => $syllabus,
            ) + $this->gradeExamColumns('periodic_test_syllabus', $gradeExam[0], $gradeExam[1]));
            $this->session->set_flashdata('success', 'Portion row added.');
        } else {
            $this->session->set_flashdata('error', 'Please provide a grade, exam, subject and portion text.');
        }
        redirect(base_url('plannermanager/index'));
    }

    public function syllabus_edit($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $id = (int) $id;
        $row = $this->db->get_where('periodic_test_syllabus', array('id' => $id))->row();
        if (!customCompute($row)) {
            $this->data['subview'] = 'error';
            $this->load->view('_layout_main', $this->data);
            return;
        }

        if ($this->input->post()) {
            $gradeExam = $this->postedGradeExam();
            $subject = trim((string) $this->input->post('subject'));
            $syllabus = trim((string) $this->input->post('syllabus'));
            if ($gradeExam && $subject !== '' && $syllabus !== '') {
                $this->db->where('id', $id)->update('periodic_test_syllabus', array(
                    'subject'   => $subject,
                    'syllabus'  => $syllabus,
                ) + $this->gradeExamColumns('periodic_test_syllabus', $gradeExam[0], $gradeExam[1]));
                $this->session->set_flashdata('success', 'Portion row updated.');
                redirect(base_url('plannermanager/index'));
                return;
            }
            $this->session->set_flashdata('error', 'Please provide a grade, exam, subject and portion text.');
        }

        $this->data['syllabus'] = current($this->decorate(array($row)));
        $this->data['grades'] = $this->grades;
        $this->data['exams'] = $this->exams;
        $this->data['subview'] = 'plannermanager/syllabus_edit';
        $this->load->view('_layout_main', $this->data);
    }

    public function syllabus_delete($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $this->db->where('id', (int) $id)->delete('periodic_test_syllabus');
        $this->session->set_flashdata('success', 'Syllabus row deleted.');
        redirect(base_url('plannermanager/index'));
    }
}

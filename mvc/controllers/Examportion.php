<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin: exam portion / syllabus (periodic_test_syllabus) — text per GRADE, EXAM and SUBJECT.
 * Shown in the app under "Exams & Portion → Portion".
 * Sidebar: Exam → Exam Portion (db_migration_planner_exam_menus.sql).
 */
class Examportion extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('periodictest_m');
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

    public function index()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        list($grade, $exam) = $this->filters();
        $this->data['rows']    = $this->periodictest_m->get_rows(Periodictest_m::SYLLABUS, $grade, $exam, array('id' => 'ASC'));
        $this->data['filter']  = array('grade' => $grade, 'exam' => $exam);
        $this->data['grades']  = Periodictest_m::$grades;
        $this->data['exams']   = Periodictest_m::$exams;
        $this->data['subview'] = 'examportion/index';
        $this->load->view('_layout_main', $this->data);
    }

    public function add()
    {
        $this->form(null);
    }

    public function edit($id = 0)
    {
        $row = $this->periodictest_m->get_row(Periodictest_m::SYLLABUS, $id);
        if (!$row) {
            $this->session->set_flashdata('error', 'Portion row not found.');
            redirect(base_url('examportion/index'));
            return;
        }
        $this->form($row);
    }

    public function delete($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $this->periodictest_m->delete_row(Periodictest_m::SYLLABUS, $id);
        $this->session->set_flashdata('success', 'Portion row deleted.');
        redirect($this->listUrl());
    }

    private function form($row)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        if ($this->input->method() === 'post') {
            $grade    = (int) $this->input->post('grade');
            $exam     = (string) $this->input->post('exam');
            $subject  = strtoupper(trim((string) $this->input->post('subject')));
            $syllabus = trim((string) $this->input->post('syllabus'));

            if (Periodictest_m::valid_grade_exam($grade, $exam) && $subject !== '' && $syllabus !== '') {
                $this->periodictest_m->save(Periodictest_m::SYLLABUS, array(
                    'subject'  => $subject,
                    'syllabus' => $syllabus,
                ), $grade, $exam, $row ? $row->id : null);
                $this->session->set_flashdata('success', $row ? 'Portion updated.' : 'Portion added.');
                if ($this->input->post('save_and_new')) {
                    redirect(base_url('examportion/add?grade=' . $grade . '&exam=' . urlencode($exam) . '&back=' . urlencode((string) $this->input->post('back'))));
                    return;
                }
                redirect($this->listUrl());
                return;
            }
            $this->session->set_flashdata('error', 'Please choose a grade and exam, and enter the subject and portion.');
        }

        $this->data['row']     = $row;
        $this->data['grades']  = Periodictest_m::$grades;
        $this->data['exams']   = Periodictest_m::$exams;
        $this->data['subview'] = 'examportion/form';
        $this->load->view('_layout_main', $this->data);
    }

    private function filters()
    {
        $grade = (int) $this->input->get('grade');
        $exam  = (string) $this->input->get('exam');
        return array(
            in_array($grade, Periodictest_m::$grades, true) ? $grade : 0,
            in_array($exam, Periodictest_m::$exams, true) ? $exam : '',
        );
    }

    private function listUrl()
    {
        $back = (string) $this->input->post_get('back');
        return ($back !== '' && strpos($back, base_url('examportion')) === 0) ? $back : base_url('examportion/index');
    }
}

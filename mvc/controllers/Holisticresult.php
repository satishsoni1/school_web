<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Holisticresult extends CI_Controller {

    public function __construct() {
        ini_set('display_startup_errors', 1);
        ini_set('display_errors', 1);
        error_reporting(-1);
        parent::__construct();
        $this->load->model('Student_model');
        $this->load->helper(['url']);
    }

    public function generate($id) {
        $data = [];
        if($id==2)
            $this->load->view('holistic_report/report_card_1', $data);
        else if($id==3)
            $this->load->view('holistic_report/report_card_2', $data);
        else{
            $data = $this->_get_full_data($id);
            $data['title'] = 'Progress Card - ' . $data['student']['student_name'];
            $this->load->view('holistic_report/progress_card', $data);
        }
            

    }

    public function print_card($id) {
        $data = $this->_get_full_data($id);
        $data['title'] = 'Print Progress Card';
        $data['print_mode'] = true;
        $this->load->view('holistic_report/progress_card', $data);
    }

    private function _get_full_data($id) {
        $rows_to_indexed = function($rows) {
            $indexed = [];
            foreach ($rows as $r) {
                $indexed[$r['term']] = $r;
            }
            return $indexed;
        };

        return [
            'student'         => $this->Student_model->get_student($id),
            'grades'          => $this->Student_model->get_competency_grades($id),
            'self_assessment' => $rows_to_indexed($this->Student_model->get_self_assessment($id)),
            'peer_assessment' => $rows_to_indexed($this->Student_model->get_peer_assessment($id)),
            'activities'      => $this->Student_model->get_activities($id),
            'teacher_profile' => $this->Student_model->get_teacher_profile($id),
            'parent_feedback' => $rows_to_indexed($this->Student_model->get_parent_feedback($id)),
            'signatures'      => $rows_to_indexed($this->Student_model->get_signatures($id)),
            'attendance'      => $this->Student_model->get_attendance($id),
        ];
    }
}

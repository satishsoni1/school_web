<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// ini_set('display_startup_errors', 1);
// ini_set('display_errors', 1);
// error_reporting(-1);

class Holisticstudent extends Admin_Controller {

    function __construct () {
		parent::__construct();
        $this->load->model('Student_model');
        $this->load->library(['form_validation', 'upload', 'session']);
        $this->load->helper(['form', 'url', 'file']);
        $language = $this->session->userdata('lang');
		$this->lang->load('student', $language);
    }

    public function index() {
        $this->data['headerassets'] = array(
			'css' => array(
				'assets/css/app.css'
			),
			'js' => array(
				'assets/js/app.js'
			)
		);
        $this->data['students'] = $this->Student_model->get_all_students();
        $this->data['title']    = 'Student List';
        $this->data["subview"] = "holistic_student/index";
		$this->load->view('_layout_main', $this->data);
        
    }

    public function add() {
        $this->data['headerassets'] = array(
			'css' => array(
				'assets/css/app.css'
			),
			'js' => array(
				'assets/js/app.js'
			)
		);
        $this->data['title']   = 'Add Student';
        $this->data['student'] = [];
        $this->data['grades']  = [];
        $this->data['mode']    = 'add';
        // $this->load->view('templates/header', $data);
        // $this->load->view('holistic_student/form', $data);
        // $this->load->view('templates/footer');
        $this->data["subview"] = "holistic_student/form";
		$this->load->view('_layout_main', $this->data);
    }

    // ── Save new student ──────────────────────────────────────────────────────
    public function save() {
        $post = $this->input->post(NULL, TRUE);

        $student_data = $this->_extract_student_data($post);

        // Handle photo uploads
        $student_data['photo_self']   = $this->_upload_file('photo_self', 'uploads/photos/');
        $student_data['photo_family'] = $this->_upload_file('photo_family', 'uploads/photos/');

        $student_id = $this->Student_model->insert_student($student_data);

        if ($student_id) {
            $this->_save_related_data($student_id, $post);
            $this->session->set_flashdata('success', 'Student added successfully!');
        } else {
            $this->session->set_flashdata('error', 'Error adding student.');
        }
        redirect('students');
    }

    // ── Edit form ─────────────────────────────────────────────────────────────
    public function edit($id) {
        $this->data['title']   = 'Edit Student';
        $this->data['student'] = $this->Student_model->get_student($id);
        $this->data['grades']  = $this->Student_model->get_competency_grades($id);
        $this->data['self_assessment']  = $this->_index_by_term($this->Student_model->get_self_assessment($id));
        $this->data['peer_assessment']  = $this->_index_by_term($this->Student_model->get_peer_assessment($id));
        $this->data['activities']       = $this->Student_model->get_activities($id);
        $this->data['teacher_profile']  = $this->Student_model->get_teacher_profile($id);
        $this->data['parent_feedback']  = $this->_index_by_term($this->Student_model->get_parent_feedback($id));
        $this->data['signatures']       = $this->_index_by_term($this->Student_model->get_signatures($id));
        $this->data['attendance']       = $this->Student_model->get_attendance($id);
        $this->data['mode']             = 'edit';

        $this->data['headerassets'] = array(
			'css' => array(
				'assets/css/app.css'
			),
			'js' => array(
				'assets/js/app.js'
			)
		);
        
        $this->data["subview"] = "holistic_student/form";
		$this->load->view('_layout_main', $this->data);
    }

    // ── Update student ────────────────────────────────────────────────────────
    public function update($id) {
        $post = $this->input->post(NULL, TRUE);

        $student_data = $this->_extract_student_data($post);

        // Handle photo uploads (only if new file uploaded)
        $new_self   = $this->_upload_file('photo_self', 'uploads/photos/');
        $new_family = $this->_upload_file('photo_family', 'uploads/photos/');
        if ($new_self)   $student_data['photo_self']   = $new_self;
        if ($new_family) $student_data['photo_family'] = $new_family;

        $this->Student_model->update_student($id, $student_data);
        $this->_save_related_data($id, $post);

        $this->session->set_flashdata('success', 'Student updated successfully!');
        redirect('students');
    }

    // ── Delete student ────────────────────────────────────────────────────────
    public function delete($id) {
        $this->Student_model->delete_student($id);
        $this->session->set_flashdata('success', 'Student deleted.');
        redirect('students');
    }

    // ── View student details ──────────────────────────────────────────────────
    public function view($id) {
        $data['student']         = $this->Student_model->get_student($id);
        $data['grades']          = $this->Student_model->get_competency_grades($id);
        $data['self_assessment'] = $this->_index_by_term($this->Student_model->get_self_assessment($id));
        $data['peer_assessment'] = $this->_index_by_term($this->Student_model->get_peer_assessment($id));
        $data['activities']      = $this->Student_model->get_activities($id);
        $data['teacher_profile'] = $this->Student_model->get_teacher_profile($id);
        $data['parent_feedback'] = $this->_index_by_term($this->Student_model->get_parent_feedback($id));
        $data['signatures']      = $this->_index_by_term($this->Student_model->get_signatures($id));
        $data['attendance']      = $this->Student_model->get_attendance($id);
        $data['title']           = 'Student Profile';

        $this->load->view('templates/header', $data);
        $this->load->view('holistic_student/view', $data);
        $this->load->view('templates/footer');
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    private function _extract_student_data($post) {
        $interests = ['dancing','reading','singing','writing','paper_craft','number_games','gardening','drawing','listening'];
        $data = [
            'student_name'  => $post['student_name'] ?? '',
            'class'         => $post['class'] ?? '',
            'section'       => $post['section'] ?? '',
            'address'       => $post['address'] ?? '',
            'contact_number'=> $post['contact_number'] ?? '',
            'birthday'      => $post['birthday'] ?? NULL,
            'age'           => $post['age'] ?? NULL,
            'mother_tongue' => $post['mother_tongue'] ?? '',
            'aspiration'    => $post['aspiration'] ?? '',
            'interest_other'=> $post['interest_other'] ?? '',
            'fav_colour'    => $post['fav_colour'] ?? '',
            'fav_foods'     => $post['fav_foods'] ?? '',
            'fav_games'     => $post['fav_games'] ?? '',
            'fav_animal'    => $post['fav_animal'] ?? '',
            'fav_flower'    => $post['fav_flower'] ?? '',
            'fav_festival'  => $post['fav_festival'] ?? '',
            'father_name'   => $post['father_name'] ?? '',
            'mother_name'   => $post['mother_name'] ?? '',
            't1_height_handspan' => $post['t1_height_handspan'] ?? '',
            't1_height_ft'       => $post['t1_height_ft'] ?? '',
            't1_weight_kg'       => $post['t1_weight_kg'] ?? '',
            't2_height_handspan' => $post['t2_height_handspan'] ?? '',
            't2_height_ft'       => $post['t2_height_ft'] ?? '',
            't2_weight_kg'       => $post['t2_weight_kg'] ?? '',
        ];
        foreach ($interests as $i) {
            $data["interest_{$i}"] = isset($post["interest_{$i}"]) ? 1 : 0;
        }
        return $data;
    }

    private function _save_related_data($student_id, $post) {
        // Competency grades
        if (!empty($post['grades'])) {
            $this->Student_model->save_competency_grades($student_id, $post['grades']);
        }

        // Self assessment
        if (!empty($post['self_assessment'])) {
            $this->Student_model->save_self_assessment($student_id, $post['self_assessment']);
        }

        // Peer assessment
        if (!empty($post['peer_assessment'])) {
            $this->Student_model->save_peer_assessment($student_id, $post['peer_assessment']);
        }

        // Activities
        if (!empty($post['activities'])) {
            $activities = [];
            foreach (['Term1', 'Term2'] as $term) {
                if (!empty($post['activities'][$term])) {
                    foreach ($post['activities'][$term] as $act) {
                        $activities[] = ['term' => $term, 'activity_name' => $act];
                    }
                }
            }
            $this->Student_model->save_activities($student_id, $activities);
        }

        // Teacher profile
        if (isset($post['teacher_profile'])) {
            $this->Student_model->save_teacher_profile($student_id, $post['teacher_profile']);
        }

        // Parent feedback
        if (!empty($post['parent_feedback'])) {
            $this->Student_model->save_parent_feedback($student_id, $post['parent_feedback']);
        }

        // Signatures
        if (!empty($post['signatures'])) {
            $this->Student_model->save_signatures($student_id, $post['signatures']);
        }

        // Attendance
        if (!empty($post['attendance'])) {
            $this->Student_model->save_attendance($student_id, $post['attendance']);
        }
    }

    private function _upload_file($field, $path) {
        if (!isset($_FILES[$field]) || empty($_FILES[$field]['name'])) {
            return NULL;
        }
        if (!is_dir(FCPATH . $path)) {
            mkdir(FCPATH . $path, 0777, true);
        }
        $config = [
            'upload_path'   => FCPATH . $path,
            'allowed_types' => 'jpg|jpeg|png|gif',
            'max_size'      => 2048,
            'encrypt_name'  => TRUE,
        ];
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        if ($this->upload->do_upload($field)) {
            return $path . $this->upload->data('file_name');
        }
        return NULL;
    }

    private function _index_by_term($rows) {
        $indexed = [];
        foreach ($rows as $r) {
            $indexed[$r['term']] = $r;
        }
        return $indexed;
    }
}

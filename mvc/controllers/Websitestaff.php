<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Manages the public website's "Staff" page — a directory independent of the
 * operational `teacher` HR table, so it can carry a photo, custom display
 * order, and non-teaching staff (support/admin staff with no teacher login),
 * grouped into "Staff" and "Non-Teaching Staff" on the public site.
 *
 * New existing entries can be started from a real teacher record (name /
 * designation / photo copied in once) or added from scratch — either way the
 * result is an independent record you can freely edit and reorder here
 * without touching the real teacher's HR data.
 */
class Websitestaff extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('website_staff_m');
        $this->load->model('teacher_m');
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    protected function rules() {
        return [
            [
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'trim|required|max_length[128]|xss_clean'
            ],
            [
                'field' => 'group_type',
                'label' => 'Group',
                'rules' => 'trim|required|in_list[teaching,non_teaching]'
            ],
        ];
    }

    public function index() {
        $this->data['staffList'] = $this->website_staff_m->get_order_by_website_staff();
        $this->data['subview']   = 'websitestaff/index';
        $this->load->view('_layout_main', $this->data);
    }

    public function add() {
        $this->data['teachers'] = $this->teacher_m->get_teacher();
        $this->data['nextOrderTeaching']    = $this->website_staff_m->next_sort_order('teaching');
        $this->data['nextOrderNonTeaching'] = $this->website_staff_m->next_sort_order('non_teaching');

        if ($_POST) {
            $this->form_validation->set_rules($this->rules());
            if ($this->form_validation->run() == FALSE) {
                $this->data['subview'] = 'websitestaff/add';
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = [
                    'name'             => $this->input->post('name'),
                    'designation'      => $this->input->post('designation'),
                    'group_type'       => $this->input->post('group_type'),
                    'email'            => $this->input->post('email'),
                    'phone'            => $this->input->post('phone'),
                    'sort_order'       => (int) $this->input->post('sort_order'),
                    'status'           => $this->input->post('status') ? 1 : 0,
                    'source_teacherID' => (int) $this->input->post('source_teacherID') ?: null,
                    'create_date'      => date('Y-m-d H:i:s'),
                    'modify_date'      => date('Y-m-d H:i:s'),
                ];

                $photo = $this->_handlePhotoUpload();
                if ($photo !== null) { $array['photo'] = $photo; }

                $this->website_staff_m->insert_website_staff($array);
                $this->session->set_flashdata('success', 'Staff member added.');
                redirect(base_url('websitestaff/index'));
            }
        } else {
            $this->data['subview'] = 'websitestaff/add';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function edit() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if (!(int) $id) { redirect(base_url('websitestaff/index')); }

        $this->data['staff'] = $this->website_staff_m->get_single_website_staff(['website_staffID' => $id]);
        if (!customCompute($this->data['staff'])) { redirect(base_url('websitestaff/index')); }

        if ($_POST) {
            $this->form_validation->set_rules($this->rules());
            if ($this->form_validation->run() == FALSE) {
                $this->data['subview'] = 'websitestaff/edit';
                $this->load->view('_layout_main', $this->data);
            } else {
                $array = [
                    'name'        => $this->input->post('name'),
                    'designation' => $this->input->post('designation'),
                    'group_type'  => $this->input->post('group_type'),
                    'email'       => $this->input->post('email'),
                    'phone'       => $this->input->post('phone'),
                    'sort_order'  => (int) $this->input->post('sort_order'),
                    'status'      => $this->input->post('status') ? 1 : 0,
                    'modify_date' => date('Y-m-d H:i:s'),
                ];

                if ($this->input->post('photo_remove')) {
                    $array['photo'] = '';
                }

                $photo = $this->_handlePhotoUpload();
                if ($photo !== null) { $array['photo'] = $photo; }

                $this->website_staff_m->update_website_staff($array, $id);
                $this->session->set_flashdata('success', 'Staff member updated.');
                redirect(base_url('websitestaff/index'));
            }
        } else {
            $this->data['subview'] = 'websitestaff/edit';
            $this->load->view('_layout_main', $this->data);
        }
    }

    public function delete() {
        $id = htmlentities(escapeString($this->uri->segment(3)));
        if ((int) $id) {
            $staff = $this->website_staff_m->get_single_website_staff(['website_staffID' => $id]);
            if (customCompute($staff)) {
                if (!empty($staff->photo) && file_exists(FCPATH . 'uploads/gallery/' . $staff->photo)) {
                    @unlink(FCPATH . 'uploads/gallery/' . $staff->photo);
                }
                $this->website_staff_m->delete_website_staff($id);
                $this->session->set_flashdata('success', 'Staff member removed.');
            }
        }
        redirect(base_url('websitestaff/index'));
    }

    private function _handlePhotoUpload() {
        if (empty($_FILES['photo']['name'])) { return null; }

        $fileName = $_FILES['photo']['name'];
        $explode  = explode('.', $fileName);
        if (customCompute($explode) < 2) { return null; }

        $newFile = hash('sha512', random19() . $fileName . config_item('encryption_key')) . '.' . end($explode);
        $config['upload_path']   = './uploads/gallery';
        $config['allowed_types'] = 'gif|jpg|jpeg|png|webp';
        $config['file_name']     = $newFile;
        $config['max_size']      = '2048';
        $config['max_width']     = '4000';
        $config['max_height']    = '4000';
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('photo')) {
            return $newFile;
        }
        $this->session->set_flashdata('error', $this->upload->display_errors());
        return null;
    }
}

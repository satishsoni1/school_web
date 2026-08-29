<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Notice extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('notice_m');
        $this->load->model("alert_m");
    }

    public function index_get()
    {
        $schoolyearID = $this->session->userdata("defaultschoolyearID");
        $this->retdata['notices'] = $this->notice_m->get_order_by_notice(array('schoolyearID' => $schoolyearID));

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = null)
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        if ((int)$id) {
            $this->retdata['notice'] = $this->notice_m->get_single_notice(array('noticeID' => $id, 'schoolyearID' => $schoolyearID));
            if (customCompute($this->retdata['notice'])) {
                $alert = $this->alert_m->get_single_alert(array('itemID' => $id, "userID" => $this->session->userdata("loginuserID"), 'usertypeID' => $this->session->userdata('usertypeID'), 'itemname' => 'notice'));
                if (!customCompute($alert)) {
                    $array = array(
                        "itemID" => $id,
                        "userID" => $this->session->userdata("loginuserID"),
                        "usertypeID" => $this->session->userdata("usertypeID"),
                        "itemname" => 'notice',
                    );
                    $this->alert_m->insert_alert($array);
                }

                $this->response([
                    'status'    => true,
                    'message'   => 'Success',
                    'data'      => $this->retdata
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Error 404',
                    'data' => []
                ], REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error 404',
                'data' => []
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
    protected function rules() {
		$rules = array(
				 array(
					'field' => 'title',
					'label' => $this->lang->line("notice_title"),
					'rules' => 'trim|required|xss_clean|max_length[128]'
				),
				array(
					'field' => 'date',
					'label' => $this->lang->line("notice_date"),
					'rules' => 'trim|required|xss_clean'
				),
				array(
					'field' => 'description',
					'label' => $this->lang->line("notice_notice"),
					'rules' => 'trim|required|xss_clean'
				)
			);
		return $rules;
	}
    public function add_post()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID'))) {
            $date = inputCall('date');
			$classesID = inputCall('classesID');
			$sectionID = inputCall('sectionID');
			$title = inputCall('title');
			$description = inputCall('description');

			$_POST = inputCall();

            $rules = $this->rules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $this->retdata2['validation'] = $this->form_validation->error_array();
                $this->response([
                    'status' => false,
                    'message' => 'Error 404',
                    'data' => $this->retdata2,
                ], REST_Controller::HTTP_NOT_FOUND);
            } else {
                $array = array(
                    "title" => inputCall("title"),
                    "notice" => inputCall("description"),
                    "classesID" => inputCall("classesID"),
                    "sectionID" => inputCall("sectionID"),
                    'schoolyearID' =>  $this->session->userdata('defaultschoolyearID'),
                    "date" => date("Y-m-d", strtotime(inputCall("date"))),
                    "create_date" => date("Y-m-d H:i:s"),
                    "create_userID" => 1,
                    "create_usertypeID" => 1
                );
                $this->notice_m->insert_notice($array);

                $noticeID = $this->db->insert_id();
                $this->response([
                    'status' => true,
                    'message' => 'Success',
                    'data' => $noticeID
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Error 404',
                'data' => []
            ], REST_Controller::HTTP_NOT_FOUND);
        }
    }
}

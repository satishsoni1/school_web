<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Feetypes extends Api_Controller 
{
    public function __construct() 
    {
        parent::__construct();
        $this->load->model('feetypes_m');
    }

    public function index_get() 
    {
        $this->retdata['feetypes'] = $this->feetypes_m->get_order_by_feetypes();

        // Names of the logged-in student / parent's children admitted under RTE, for the app's tag.
        $this->load->model('student_m');
        $rteIDs = $this->student_m->get_rte_student_ids();
        $rteStudents = [];
        $usertypeID  = $this->session->userdata('usertypeID');
        $loginuserID = $this->session->userdata('loginuserID');
        if ($rteIDs && ($usertypeID == 3 || $usertypeID == 4)) {
            $field = ($usertypeID == 3) ? 'studentID' : 'parentID';
            foreach ($this->db->select('studentID, name')->where($field, $loginuserID)->get('student')->result() as $student) {
                if (isset($rteIDs[$student->studentID])) {
                    $rteStudents[] = $student->name;
                }
            }
        }
        $this->retdata['rte_students'] = $rteStudents;

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

}

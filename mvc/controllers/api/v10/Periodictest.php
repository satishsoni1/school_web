<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Periodictest extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * GET /api/v10/periodictest/schedule/$classesID
     */
    public function schedule_get($classID = null)
    {
        $loginuserID = $this->session->userdata("loginuserID");
        $usertypeID = $this->session->userdata("usertypeID");

        // Automatically find class ID if not supplied for student logins
        if (empty($classID) && $usertypeID == 3) {
            $student = $this->db->get_where('student', array('studentID' => $loginuserID))->row();
            if (!empty($student)) {
                $classID = isset($student->classesID) ? $student->classesID : 0;
            }
        }

        $classes = $this->db->get('classes')->result_array();
        
        $this->db->select('*');
        $this->db->from('periodic_test_schedule');
        if (!empty($classID)) {
            $this->db->where('classesID', $classID);
        }
        $this->db->order_by('test_date', 'ASC');
        $query = $this->db->get();
        $schedules = $query->result_array();

        // Map class names
        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['classesID']] = $c['classes'];
        }

        foreach ($schedules as &$sch) {
            $cid = $sch['classesID'];
            $sch['class_name'] = isset($classMap[$cid]) ? $classMap[$cid] : 'Grade ' . $cid;
        }

        $this->retdata['classes'] = $classes;
        $this->retdata['schedules'] = $schedules;

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    /**
     * GET /api/v10/periodictest/syllabus/$classesID
     */
    public function syllabus_get($classID = null)
    {
        $loginuserID = $this->session->userdata("loginuserID");
        $usertypeID = $this->session->userdata("usertypeID");

        // Automatically find class ID if not supplied for student logins
        if (empty($classID) && $usertypeID == 3) {
            $student = $this->db->get_where('student', array('studentID' => $loginuserID))->row();
            if (!empty($student)) {
                $classID = isset($student->classesID) ? $student->classesID : 0;
            }
        }

        $classes = $this->db->get('classes')->result_array();
        
        $this->db->select('*');
        $this->db->from('periodic_test_syllabus');
        if (!empty($classID)) {
            $this->db->where('classesID', $classID);
        }
        $query = $this->db->get();
        $syllabusList = $query->result_array();

        // Map class names
        $classMap = [];
        foreach ($classes as $c) {
            $classMap[$c['classesID']] = $c['classes'];
        }

        foreach ($syllabusList as &$syl) {
            $cid = $syl['classesID'];
            $syl['class_name'] = isset($classMap[$cid]) ? $classMap[$cid] : 'Grade ' . $cid;
        }

        $this->retdata['classes'] = $classes;
        $this->retdata['syllabuss'] = $syllabusList;

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}

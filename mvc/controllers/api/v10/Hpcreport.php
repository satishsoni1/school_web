<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * HPC (holistic) report cards for the mobile app: every report generated in the admin
 * portal for the logged-in student, or for all of a parent's children, across all school
 * years. Built only from the saved report snapshots (Holisticsnapshot_m), never master data.
 */
class Hpcreport extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('holisticsnapshot_m');
    }

    public function index_get()
    {
        $usertypeID  = $this->session->userdata('usertypeID');
        $loginuserID = (int) $this->session->userdata('loginuserID');

        $studentIDs = [];
        if ($usertypeID == 3) {
            $studentIDs = [$loginuserID];
        } elseif ($usertypeID == 4) {
            // All of the parent's children, in any school year.
            $studentIDs = array_column($this->db->select('studentID')->where('parentID', $loginuserID)->get('student')->result_array(), 'studentID');
        }

        $reports = [];
        foreach ($this->holisticsnapshot_m->get_snapshots_for_students($studentIDs) as $snapshot) {
            $context = $this->holisticsnapshot_m->report_context($snapshot);
            if ($context === null) {
                continue;
            }
            $student = $context['student'];
            $reports[] = [
                'studentID'    => (int) $snapshot->studentID,
                'schoolyearID' => (int) $snapshot->schoolyearID,
                'classesID'    => (int) $snapshot->classesID,
                'name'         => isset($student->name) ? $student->name : '',
                'roll'         => isset($student->roll) ? $student->roll : '',
                'classes'      => isset($context['classes']->classes) ? $context['classes']->classes : '',
                'section'      => isset($context['section']->section) ? $context['section']->section : '',
                'schoolyear'   => isset($context['schoolyear']->schoolyear) ? $context['schoolyear']->schoolyear : '',
                'photo'        => pdfimagelink($student->photo, $context['student_photo_path']),
                'generated_at' => $snapshot->updated_at,
                'url'          => base_url('PublicHolisticReport/' . Holisticsnapshot_m::report_method($snapshot->classesID) . '/' . $snapshot->studentID . '/' . $snapshot->classesID . '/' . $snapshot->schoolyearID),
            ];
        }

        $this->response([
            'status'  => true,
            'message' => 'Success',
            'data'    => ['reports' => $reports]
        ], REST_Controller::HTTP_OK);
    }
}

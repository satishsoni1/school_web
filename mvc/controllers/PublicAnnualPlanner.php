<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PublicAnnualPlanner extends Frontend_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('studentrelation_m');
        $this->load->model('setting_m');
        $this->load->helper('url');
    }

    public function index($classesID = null)
    {
        $classesID = (int) $classesID;
        
        // 1. If not passed in URL, try getting from session
        if ($classesID <= 0) {
            $classesID = (int) $this->session->userdata('classesID');
        }

        // 2. If still not found, try resolving student's class from database
        if ($classesID <= 0 && $this->session->userdata('loginuserID')) {
            $studentID = (int) $this->session->userdata('loginuserID');
            $setting = $this->setting_m->get_setting();
            $schoolyearID = $setting ? (int) $setting->school_year : 0;
            
            if ($studentID > 0 && $schoolyearID > 0) {
                $student = $this->studentrelation_m->get_single_student(array(
                    'srstudentID'    => $studentID,
                    'srschoolyearID' => $schoolyearID
                ));
                if ($student) {
                    $classesID = (int) $student->srclassesID;
                }
            }
        }

        // 3. Fallback to default if no classesID could be resolved or file doesn't exist
        $dir = FCPATH . 'uploads/annual_planners/';
        $filepath = $dir . 'planner_' . $classesID . '.pdf';

        // A class-specific PDF wins; otherwise Nursery/Prep get the pre-primary planner and
        // everyone else the Grades 1-10 planner. Empty (0-byte) files are ignored.
        if ($classesID <= 0 || !is_file($filepath) || filesize($filepath) == 0) {
            $class = $classesID > 0 ? $this->db->get_where('classes', array('classesID' => $classesID))->row() : null;
            $isPrep = $class && preg_match('/nursery|prep|pre[\s-]*primary|kg/i', $class->classes);
            $filepath = $dir . ($isPrep ? 'planner_prep.pdf' : 'annualplaner.pdf');
        }

        if (is_file($filepath) && filesize($filepath) > 0) {
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . basename($filepath) . '"');
            header('Content-Transfer-Encoding: binary');
            header('Accept-Ranges: bytes');
            readfile($filepath);
            exit;
        } else {
            show_404();
        }
    }
}

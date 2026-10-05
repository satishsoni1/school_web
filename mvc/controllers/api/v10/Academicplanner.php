<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Academic planner events. There are two planners (academic_planner.audience):
 *   'grade' = Grades 1-10, 'prep' = Nursery / Prep 1 / Prep 2.
 * A student gets the planner of their class; a parent gets those of their children;
 * staff get both. GET /academicplanner/index/<classesID> narrows to that class's planner.
 * Every row carries `audience`, and `audiences` lists which planners were returned.
 */
class Academicplanner extends Api_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('academic_planner_m');
        $this->load->model('studentrelation_m');
    }

    public function index_get($classesID = null) {
        $audiences = null; // null = every planner
        if ($this->academic_planner_m->has_audience()) {
            if ((int) $classesID > 0) {
                $audiences = array($this->audienceOfClass((int) $classesID));
            } else {
                $audiences = $this->viewerAudiences();
            }
        }

        $events = $this->academic_planner_m->get_planner_events($audiences);

        // Kept as a plain list of events (what older app versions expect); the extra
        // fields ride along on each row.
        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $events,
            'audiences' => $audiences ?: array('grade', 'prep'),
        ], REST_Controller::HTTP_OK);
    }

    /** Planners relevant to the logged-in user, from their (children's) classes. */
    private function viewerAudiences() {
        $usertypeID = $this->session->userdata('usertypeID');
        if ($usertypeID != 3 && $usertypeID != 4) {
            return null;
        }
        // Scoped to the student's own class / the parent's children by Studentrelation_m.
        $students = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $this->session->userdata('defaultschoolyearID')));
        $audiences = array();
        foreach ((array) $students as $student) {
            if ($usertypeID == 3 && $student->srstudentID != $this->session->userdata('loginuserID')) {
                continue;
            }
            $audiences[$this->audienceOfClass($student->srclassesID)] = true;
        }
        return $audiences ? array_keys($audiences) : null;
    }

    /** Nursery / Prep classes follow the pre-primary planner; everything else the grades one. */
    private function audienceOfClass($classesID) {
        $class = $this->db->get_where('classes', array('classesID' => $classesID))->row();
        return ($class && preg_match('/nursery|prep|pre[\s-]*primary|kg/i', $class->classes)) ? 'prep' : 'grade';
    }
}

<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Academicplanner extends Api_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('academic_planner_m');
        
    }

    public function index_get() {
        $this->retdata = $this->academic_planner_m->get_planner_events();
        
            $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }
}

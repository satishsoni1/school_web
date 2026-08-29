<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Academic_planner_m extends CI_Model {
    protected $_table_name = 'academic_planner';

    public function __construct() {
        parent::__construct();
    }

    public function get_planner_events() {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        $this->db->order_by('event_date', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}

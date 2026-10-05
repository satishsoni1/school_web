<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Academic_planner_m extends CI_Model {
    protected $_table_name = 'academic_planner';

    public function __construct() {
        parent::__construct();
    }

    /** True once db_migration_academic_planner_prep.sql has added the audience column. */
    public function has_audience() {
        return $this->db->field_exists('audience', $this->_table_name);
    }

    /**
     * Planner events ordered by date. $audiences = array('grade'|'prep', ...) limits to those
     * planners; null returns every event.
     */
    public function get_planner_events($audiences = null) {
        $this->db->select('*');
        $this->db->from($this->_table_name);
        if ($audiences && $this->has_audience()) {
            $this->db->where_in('audience', $audiences);
        }
        $this->db->order_by('event_date', 'ASC');
        $this->db->order_by('id', 'ASC');
        $query = $this->db->get();
        return $query->result_array();
    }
}

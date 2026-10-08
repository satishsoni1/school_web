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
    // ---- Admin (Academic Planner module) ----------------------------------

    public static $types     = array('holiday', 'activity', 'sports', 'exam', 'test', 'event');
    public static $audiences = array('grade' => 'Grades 1 - 10', 'prep' => 'Pre-Primary (Nursery / Prep)');

    /** Events for the admin list, optionally filtered by planner / type / month (YYYY-MM). */
    public function get_events_filtered($audience = '', $type = '', $month = '') {
        if ($audience !== '' && $this->has_audience()) {
            $this->db->where('audience', $audience);
        }
        if ($type !== '') {
            $this->db->where('type', $type);
        }
        if (preg_match('/^\d{4}-\d{2}$/', $month)) {
            $this->db->where('event_date >=', $month . '-01');
            $this->db->where('event_date <=', date('Y-m-t', strtotime($month . '-01')));
        }
        $this->db->order_by('event_date', 'ASC');
        $this->db->order_by('id', 'ASC');
        return $this->db->get($this->_table_name)->result();
    }

    public function get_event($id) {
        return $this->db->get_where($this->_table_name, array('id' => (int) $id))->row();
    }

    /** Insert ($id null) or update. A new event for audience 'both' is added to each planner. */
    public function save_event(array $data, $audience, $id = null) {
        if ($this->has_audience()) {
            if ($id) {
                if (isset(self::$audiences[$audience])) {
                    $data['audience'] = $audience;
                }
            } else {
                $targets = $audience === 'both' ? array_keys(self::$audiences)
                    : array(isset(self::$audiences[$audience]) ? $audience : 'grade');
                foreach ($targets as $target) {
                    $this->db->insert($this->_table_name, $data + array('audience' => $target));
                }
                return;
            }
        }
        if ($id) {
            $this->db->where('id', (int) $id)->update($this->_table_name, $data);
        } else {
            $this->db->insert($this->_table_name, $data);
        }
    }

    public function delete_event($id) {
        $this->db->where('id', (int) $id)->delete($this->_table_name);
    }

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

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sattendance_m extends MY_Model {

	protected $_table_name = 'attendance';
	protected $_primary_key = 'attendanceID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "monthyear asc";

	function __construct() {
		parent::__construct();
	}

	public function get_attendance($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		if(is_object($query)) {
			$this->blank_before_admission(array($query));
		} elseif(is_array($query)) {
			$this->blank_before_admission($query);
		}
		return $query;
	}

	public function get_order_by_attendance($array=NULL) {
		$query = parent::get_order_by($array);
		if(is_array($query)) {
			$this->blank_before_admission($query);
		}
		return $query;
	}

	/**
	 * Attendance counts from the student's date of admission: marks on days before it
	 * (a1..a31 of the row's monthyear) are blanked so every report, page and the app ignore them.
	 */
	private function blank_before_admission($rows) {
		$studentIDs = array();
		foreach($rows as $row) {
			if(isset($row->studentID, $row->monthyear)) {
				$studentIDs[(int) $row->studentID] = TRUE;
			}
		}
		if(!$studentIDs) {
			return;
		}
		$admissions = array();
		$result = $this->db->select('studentID, admission_date')->from('student')
			->where_in('studentID', array_keys($studentIDs))
			->where('admission_date IS NOT NULL', NULL, FALSE)
			->where('admission_date !=', '0000-00-00')
			->get();
		if($result) {
			foreach($result->result() as $s) {
				$admissions[(int) $s->studentID] = $s->admission_date;
			}
		}
		foreach($rows as $row) {
			if(!isset($row->studentID, $row->monthyear, $admissions[(int) $row->studentID])) {
				continue;
			}
			$parts = explode('-', $row->monthyear);
			if(count($parts) != 2) {
				continue;
			}
			$monthStart = sprintf('%04d-%02d-01', $parts[1], $parts[0]);
			$admission  = $admissions[(int) $row->studentID];
			if($admission <= $monthStart) {
				continue;
			}
			for($d = 1; $d <= 31; $d++) {
				if(sprintf('%04d-%02d-%02d', $parts[1], $parts[0], $d) < $admission && property_exists($row, 'a'.$d)) {
					$row->{'a'.$d} = NULL;
				}
			}
		}
	}

	public function insert_attendance($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_attendance($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_attendance($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_batch_attendance($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;
    }

	public function delete_attendance($id){
		parent::delete($id);
	}
	public function get_student_attendance_master($studentID)
	{
		$this->db->where('studentID', $studentID);
		return $this->db->get('temp_attendance_master')->row_array();
	}

	public function get_working_days()
	{
		return $this->db->order_by('month_year','ASC')
						->get('temp_working_days')
						->result_array();
	}
}
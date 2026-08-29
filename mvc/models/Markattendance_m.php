<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Markattendance_m extends MY_Model {

	protected $_table_name = 'mark_attendance';
	protected $_primary_key = 'markID';
	protected $_primary_filter = 'intval';

	function __construct() {
		parent::__construct();
	}

	public function get_mark($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	public function get_single_mark($array) {
		$query = parent::get_single($array);
		return $query;
	}

	public function get_order_by_mark($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	public function insert_mark($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_mark($array) {
		$id = parent::insert_batch($array);
		return $id;
	}

	public function update_mark($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function update_mark_classes($array, $id) {
		$this->db->update($this->_table_name, $array, $id);
		return $id;
	}

	public function update_mark_with_condition($array, $id) {
		$this->db->update($this->_table_name, $array, $id);
		return $this->db->affected_rows();
	}

	public function delete_mark($id){
		parent::delete($id);
	}

	public function sum_student_subject_mark($studentID, $classesID) {
		$array = array(
			"studentID" => $studentID,
			"classesID" => $classesID,
		);
		$this->db->select('attendance');
		$this->db->where($array);
		$query = $this->db->get('mark_attendance');
		return $query->row();
	}
	public function student_all_mark_array($array) {
		$this->db->select('*');
		$this->db->from('mark_attendance');
		

		if(isset($array['schoolyearID'])) {
			$this->db->where('mark_attendance.schoolyearID', $array['schoolyearID']);
		}

		if(isset($array['classesID'])) {
			$this->db->where('mark_attendance.classesID', $array['classesID']);
		}

		if(isset($array['studentID'])) {
			$this->db->where('mark_attendance.studentID', $array['studentID']);
		}

		$query = $this->db->get();
		return $query->result();
	}

	function update_batch_markattendance($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;
    }
}

/* End of file mark_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/mark_m.php */

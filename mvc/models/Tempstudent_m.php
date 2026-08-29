<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tempstudent_m extends MY_Model {

	protected $_table_name = 'temp_student_book';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = 'created_date';

	function __construct() {
		parent::__construct();
	}

	public function get_student($array=NULL, $signal=FALSE) {
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

	public function insert_invoice($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	public function insert_batch_mark($array) {
		$id = parent::insert_batch($array);
		return $id;
	}
	function update_batch_markrelation($data, $id = NULL) {
        parent::update_batch($data, $id);
        return TRUE;

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

}

/* End of file mark_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/mark_m.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Website_staff_m extends MY_Model {

	protected $_table_name = 'website_staff';
	protected $_primary_key = 'website_staffID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "sort_order asc, name asc";

	function __construct() {
		parent::__construct();
	}

	function get_website_staff($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}

	function get_single_website_staff($array) {
		$query = parent::get_single($array);
		return $query;
	}

	function get_order_by_website_staff($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function insert_website_staff($array) {
		$id = parent::insert($array);
		return $id;
	}

	function update_website_staff($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_website_staff($id){
		parent::delete($id);
	}

	// Next free sort_order for a given group, so new entries default to "last".
	public function next_sort_order($groupType) {
		$row = $this->db->query("SELECT MAX(sort_order) as maxOrder FROM website_staff WHERE group_type = ?", [$groupType])->row();
		return ($row && $row->maxOrder !== null) ? ((int) $row->maxOrder + 1) : 1;
	}
}

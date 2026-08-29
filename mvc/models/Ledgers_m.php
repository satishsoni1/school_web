<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class ledgers_m extends MY_Model {

	protected $_table_name = 'ledgers';
	protected $_primary_key = 'ledgersID';
	protected $_primary_filter = 'intval';
	protected $_order_by = "ledgersID asc";

	function __construct() {
		parent::__construct();
	}

	function get_ledgers($array=NULL, $signal=FALSE) {
		$query = parent::get($array, $signal);
		return $query;
	}
	

	function get_order_by_ledgers($array=NULL) {
		$query = parent::get_order_by($array);
		return $query;
	}

	function get_single_ledgers($array=NULL) {
		$query = parent::get_single($array);
		return $query;
	}

	function insert_ledgers($array) {
		$error = parent::insert($array);
		return TRUE;
	}

	function update_ledgers($data, $id = NULL) {
		parent::update($data, $id);
		return $id;
	}

	public function delete_ledgers($id){
		parent::delete($id);
	}

	function allledgers($ledgers) {
		$query = $this->db->query("SELECT * FROM ledgers WHERE ledgers LIKE '$ledgers%'");
		return $query->result();
	}
	function feetype_as_per_student($classesID,$packageID) {
		$query = $this->db->query("SELECT ledgers.ledgersID,ledgers.ledgers,amount FROM ledgers join class_fee on class_fee.ledgersID=ledgers.ledgersID WHERE classesID=".$classesID." and packageID=".$packageID);
		return $query->result();
	}
}

/* End of file ledgers_m.php */
/* Location: .//D/xampp/htdocs/school/mvc/models/ledgers_m.php */
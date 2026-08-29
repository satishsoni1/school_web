<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Markclass9_m extends MY_Model {

    protected $_table_name = 'mark_class9';
    protected $_primary_key = 'markclass9ID';
    protected $_primary_filter = 'intval';
    protected $_order_by = "markclass9ID asc";

    function __construct() {
        parent::__construct();
    }

    public function get_markclass9($array=NULL, $signal=FALSE) {
        $query = parent::get($array, $signal);
        return $query;
    }

    public function get_order_by_markclass9($array=NULL) {
        $query = parent::get_order_by($array);
        return $query;
    }

    public function get_single_markclass9($array=NULL) {
        $query = parent::get_single($array);
        return $query;
    }

    public function insert_markclass9($array) {
        $error = parent::insert($array);
        return TRUE;
    }

    public function update_markclass9($data, $id = NULL) {
        parent::update($data, $id);
        return $id;
    }

    public function delete_markclass9($id){
        parent::delete($id);
    }
}
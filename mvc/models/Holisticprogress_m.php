<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Holisticprogress_m extends MY_Model
{
    protected $_table_name    = 'holisticprogress';
    protected $_primary_key   = 'id';
    protected $_primary_filter = 'intval';
    protected $_order_by      = 'id desc';

    function __construct()
    {
        parent::__construct();
    }

    public function get_single_holisticprogress($array)
    {
        return $this->get_single($array);
    }

    public function insert_holisticprogress($array)
    {
        return $this->insert($array);
    }

    public function update_holisticprogress($array, $id)
    {
        return $this->update($array, $id);
    }
}

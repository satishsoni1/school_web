<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification_recipient_m extends MY_Model {

	protected $_table_name = 'notification_recipients';
	protected $_primary_key = 'id';
	protected $_primary_filter = 'intval';
	protected $_order_by = "id desc";

	function __construct() {
		parent::__construct();
	}

	// All notifications for one user, newest first, joined with the notification body.
	public function get_for_user($userID, $usertypeID, $limit = 50) {
		$this->db->select('notification_recipients.id as recipientID, notification_recipients.is_read, notification_recipients.read_at, notifications.*');
		$this->db->from('notification_recipients');
		$this->db->join('notifications', 'notifications.notificationID = notification_recipients.notificationID');
		$this->db->where('notification_recipients.userID', $userID);
		$this->db->where('notification_recipients.usertypeID', $usertypeID);
		$this->db->order_by('notifications.created_at', 'desc');
		$this->db->limit($limit);
		$query = $this->db->get();
		return $query->result();
	}

	public function get_unread_count($userID, $usertypeID) {
		$this->db->where('userID', $userID);
		$this->db->where('usertypeID', $usertypeID);
		$this->db->where('is_read', 0);
		return $this->db->count_all_results($this->_table_name);
	}

	public function mark_read($recipientID, $userID, $usertypeID) {
		$this->db->where('id', $recipientID);
		$this->db->where('userID', $userID);
		$this->db->where('usertypeID', $usertypeID);
		return $this->db->update($this->_table_name, ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')]);
	}

	public function mark_all_read($userID, $usertypeID) {
		$this->db->where('userID', $userID);
		$this->db->where('usertypeID', $usertypeID);
		$this->db->where('is_read', 0);
		return $this->db->update($this->_table_name, ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')]);
	}
}

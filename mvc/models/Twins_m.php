<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twins_m extends MY_Model {

    function __construct() {
        parent::__construct();
    }

    // 1. Find all siblings for a given student ID
    public function get_siblings($studentID) {
        // First, get the parentID of the selected student
        $this->db->select('parentID');
        $this->db->where('studentID', $studentID);
        $student = $this->db->get('student')->row();

        if ($student && $student->parentID > 0) {
            // Fetch all students with this parentID
            $this->db->select('studentID, name, classesID, sectionID, roll, photo, registerNO');
            $this->db->where('parentID', $student->parentID);
            $this->db->where('active', 1); // Only active students
            return $this->db->get('student')->result();
        }
        return [];
    }

    // 2. Get unpaid invoices for a list of student IDs
    public function get_unpaid_invoices_batch($studentIDs) {
        if(empty($studentIDs)) return [];

        $this->db->select('invoice.*, student.name as student_name, classes.classes');
        $this->db->from('invoice');
        $this->db->join('student', 'student.studentID = invoice.studentID');
        $this->db->join('classes', 'classes.classesID = invoice.classesID');
        $this->db->where_in('invoice.studentID', $studentIDs);
        $this->db->where('invoice.paidstatus !=', 2); // 0=Unpaid, 1=Partial, 2=Fully Paid
        $this->db->where('invoice.deleted_at', 1);
        $query = $this->db->get();
        return $query->result();
    }

    // 3. Get payment history for batch (Global Payment)
    public function get_payment_history_batch($studentIDs) {
        if(empty($studentIDs)) return [];

        $this->db->select('globalpayment.*, student.name as student_name');
        $this->db->from('globalpayment');
        $this->db->join('student', 'student.studentID = globalpayment.studentID');
        $this->db->where_in('globalpayment.studentID', $studentIDs);
        $this->db->order_by('globalpayment.globalpaymentID', 'desc');
        return $this->db->get()->result();
    }
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Twins extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("student_m");
        $this->load->model("classes_m");
        $this->load->model("invoice_m");
        $this->load->model("section_m");
        
        $language = $this->session->userdata('lang');
        $this->lang->load('invoice', $language);
    }

    // 1. Search Page
    public function index() {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css'
            ),
            'js' => array(
                'assets/select2/select2.js'
            )
        );
        
        $this->data['classes'] = $this->classes_m->get_classes();
        $this->data["subview"] = "twins/index";
        $this->load->view('_layout_main', $this->data);
    }

    // 2. Process Search & Redirect
    public function search() {
        $studentID = $this->input->post('studentID');
        if((int)$studentID) {
            redirect(base_url('twins/view_print/'.$studentID));
        } else {
            $this->session->set_flashdata('error', 'Please select a student.');
            redirect(base_url('twins/index'));
        }
    }

    // 3. View Siblings & Invoices
    public function view_print($studentID = null) {
        if(!(int)$studentID) { redirect(base_url('twins/index')); }

        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        // A. Find Parent of selected student
        $student = $this->student_m->get_single_student(['studentID' => $studentID]);
        
        if(empty($student)) {
            $this->session->set_flashdata('error', 'Student not found.');
            redirect(base_url('twins/index'));
        }

        // B. Find all siblings (Students with same ParentID)
        // If parentID is 0 or null, we only find the student themselves
        if($student->parentID > 0) {
            $this->data['siblings'] = $this->student_m->get_order_by_student([
                'parentID' => $student->parentID, 
                'schoolyearID' => $schoolyearID
            ]);
        } else {
            $this->data['siblings'] = [$student]; 
        }

        // C. Fetch Invoices for ALL Siblings
        $siblingIDs = pluck($this->data['siblings'], 'studentID');
        
        if(!empty($siblingIDs)) {
            $this->db->select('maininvoice.*, student.name as student_name, classes.classes');
            $this->db->from('maininvoice');
            $this->db->join('student', 'student.studentID = maininvoice.maininvoicestudentID');
            $this->db->join('classes', 'classes.classesID = maininvoice.maininvoiceclassesID');
            $this->db->where_in('maininvoice.maininvoicestudentID', $siblingIDs);
            $this->db->where('maininvoice.maininvoiceschoolyearID', $schoolyearID);
            $this->db->where('maininvoice.maininvoicedeleted_at', 1);
            $this->db->order_by('maininvoice.maininvoiceID', 'desc');
            $this->data['invoices'] = $this->db->get()->result();
        } else {
            $this->data['invoices'] = [];
        }

        $this->data["subview"] = "twins/view_print";
        $this->load->view('_layout_main', $this->data);
    }

    // 4. Redirect to Multi-Print Controller
    public function print_action() {
        $selected_invoices = $this->input->post('maininvoiceID'); // Array of IDs

        if(!empty($selected_invoices)) {
            // Convert Array [101, 102] to String "101-102"
            $idString = implode('-', $selected_invoices);
            
            // Redirect to the Invoice Controller's Multi-Print function
            redirect(base_url('invoice/print_preview_multi/'.$idString));
        } else {
            $this->session->set_flashdata('error', 'No invoices selected to print.');
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    // AJAX Helper for Search Dropdown
    public function getStudent() {
        $classesID = $this->input->post('classesID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        
        if((int)$classesID) {
            $students = $this->student_m->get_order_by_student([
                'classesID' => $classesID, 
                'schoolyearID' => $schoolyearID
            ]);
            
            echo "<option value='0'>Select Student</option>";
            foreach ($students as $student) {
                echo "<option value='".$student->studentID."'>".$student->name." (Roll: ".$student->roll.")</option>";
            }
        }
    }
}
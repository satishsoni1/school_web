<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Bookinvoice extends Admin_Controller
{
    /*
    | -----------------------------------------------------
    | PRODUCT NAME: 	INILABS SCHOOL MANAGEMENT SYSTEM
    | -----------------------------------------------------
    | AUTHOR:			INILABS TEAM
    | -----------------------------------------------------
    | EMAIL:			info@inilabs.net
    | -----------------------------------------------------
    | COPYRIGHT:		RESERVED BY INILABS IT
    | -----------------------------------------------------
    | WEBSITE:			http://inilabs.net
    | -----------------------------------------------------
    */

    function __construct()
    {

        parent::__construct();
        error_reporting(E_ALL);
ini_set('display_errors', '1');

        $this->load->model("tempstudent_m");
        $this->load->model("studentrelation_m");

        $language = $this->session->userdata('lang');
        $this->lang->load('invoice', $language);
    }


    public function index()
    {

        $this->data['student_data'] = $this->tempstudent_m->get_student();
        $this->data["subview"]              = "bookinvoice/index";
        $this->load->view('_layout_main', $this->data);
    }
    public function add()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            $this->data['headerassets'] = [
                'css' => [
                    'assets/datepicker/datepicker.css',
                    'assets/select2/css/select2.css',
                    'assets/select2/css/select2-bootstrap.css'
                ],
                'js'  => [
                    'assets/datepicker/datepicker.js',
                    'assets/select2/select2.js'
                ]
            ];

            $this->data['classes']  = $this->classes_m->general_get_classes();
            $this->data['students'] = [];

            $this->data["subview"] = "bookinvoice/add";
            $this->load->view('_layout_main', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }
    protected function rules($statusID = 0)
    {
        $rules = [
            [
                'field' => 'classesID',
                'label' => $this->lang->line("invoice_classesID"),
                'rules' => 'trim|required|xss_clean|max_length[11]'
            ],
            [
                'field' => 'studentID',
                'label' => $this->lang->line("invoice_studentID"),
                'rules' => 'trim|required|xss_clean'
            ],
            [
                'field' => 'amount',
                'label' => 'Amount',
                'rules' => 'trim|required|xss_clean|max_length[11]|numeric'
            ],
            [
                'field' => 'date',
                'label' => $this->lang->line("invoice_date"),
                'rules' => 'trim|required|xss_clean|max_length[10]|callback_date_valid'
            ],
        ];

        return $rules;
    }
    public function date_valid($date)
    {
        if (strlen($date) < 10) {
            $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
            return FALSE;
        } else {
            $arr  = explode("-", $date);
            $dd   = $arr[0];
            $mm   = $arr[1];
            $yyyy = $arr[2];
            if (checkdate($mm, $dd, $yyyy)) {
                return TRUE;
            } else {
                $this->form_validation->set_message("date_valid", "%s is not valid dd-mm-yyyy");
                return FALSE;
            }
        }
    }
    public function pdf_preview()
    {
        // $this->data = [];
        $this->data['student_data'][] = $this->tempstudent_m->get_single_mark(array("id" => $this->uri->segment(3)));
        $this->reportPDF('invoicemodule.css', $this->data, 'dashboard/print_previewviewpayment');
    }
    public function delete()
    {
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            $id = htmlentities(escapeString($this->uri->segment(3)));
            if ((int)$id) {
                $this->tempstudent_m->delete_mark($id);
                $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                redirect(base_url('bookinvoice/index'));
            } else {
                $this->data["subview"] = "error";
                $this->load->view('_layout_main', $this->data);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }
    public function saveinvoice()
    {

        $retArray['status'] = FALSE;
        if (($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('defaultschoolyearID') == 5)) {
            if (permissionChecker('bookinvoice_add') || permissionChecker('bookinvoice_edit')) {
                if ($_POST) {
                    $rules = $this->rules();
                    $this->form_validation->set_rules($rules);
                    if ($this->form_validation->run() == FALSE) {
                        $retArray['error']  = $this->form_validation->error_array();
                        $retArray['status'] = FALSE;
                        echo json_encode($retArray);
                        exit;
                    } else {
                        $classes  = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
                        $invoiceArray = [
                            'name'  => $this->input->post('studentID'),
                            'class'  => $classes[$this->input->post('classesID')],
                            'amount'  => $this->input->post('amount'),
                            'created_date'  => date('Y-m-d', strtotime($this->input->post('date')))
                        ];
                        $this->tempstudent_m->insert_invoice($invoiceArray);
                        $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                        $retArray['status']  = TRUE;
                        $retArray['message'] = 'Success';
                        echo json_encode($retArray);
                        exit;
                    }
                } else {
                    $retArray['error'] = ['posttype' => 'Post type is required.'];
                    echo json_encode($retArray);
                    exit;
                }
            } else {
                $retArray['error'] = ['permission' => 'Invoice permission is required.'];
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['error'] = ['permission' => 'Permission Denied.'];
            echo json_encode($retArray);
            exit;
        }
    }
    public function getstudent()
    {
        $classesID    = $this->input->post('classesID');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        if ($this->input->post('edittype')) {
            echo '<option value="0">' . $this->lang->line('invoice_select_student') . '</option>';
        } else {
            echo '<option value="0">' . $this->lang->line('invoice_all_student') . '</option>';
        }

        $students = $this->studentrelation_m->get_order_by_student([
            'srschoolyearID' => $schoolyearID,
            'srclassesID'    => $classesID
        ]);
        if (customCompute($students)) {
            foreach ($students as $student) {
                echo "<option value=\"$student->srname\">" . $student->srname . " - " . $this->lang->line('invoice_roll') . " - " . $student->srroll . "</option>";
            }
        }
    }
}

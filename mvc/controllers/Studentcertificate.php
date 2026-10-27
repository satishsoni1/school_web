<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin: generate, save, edit, print and delete student certificates / letters
 * (first type: Request for Leaving Certificate). Sidebar: Certificates
 * (db_migration_student_certificate.sql). Types and their fields: Studentcertificate_m::$types.
 */
class Studentcertificate extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('studentcertificate_m');
        $this->load->model('schoolyear_m');
    }

    private function requireAdmin()
    {
        if ($this->session->userdata('usertypeID') != 1) {
            $this->data['subview'] = 'error';
            $this->load->view('_layout_main', $this->data);
            return false;
        }
        return true;
    }

    /** Saved certificates (?type= filters). */
    public function index()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $type = (string) $this->input->get('type');
        $type = isset(Studentcertificate_m::$types[$type]) ? $type : '';

        $all    = $this->studentcertificate_m->get_all();
        $counts = array_count_values(array_map(function ($c) { return $c->type; }, $all));

        $this->data['type']         = $type;
        $this->data['types']        = Studentcertificate_m::$types;
        $this->data['counts']       = $counts;
        $this->data['total']        = count($all);
        $this->data['certificates'] = $type ? array_values(array_filter($all, function ($c) use ($type) { return $c->type === $type; })) : $all;
        $this->data['subview']      = 'studentcertificate/index';
        $this->load->view('_layout_main', $this->data);
    }

    /** add/<type>[?studentID=] */
    public function add($type = 'lc_request')
    {
        if (!isset(Studentcertificate_m::$types[$type])) {
            redirect(base_url('studentcertificate/index'));
            return;
        }
        $this->form($type, null);
    }

    public function edit($id = 0)
    {
        $cert = $this->studentcertificate_m->get($id);
        if (!$cert || !isset(Studentcertificate_m::$types[$cert->type])) {
            $this->session->set_flashdata('error', 'Certificate not found.');
            redirect(base_url('studentcertificate/index'));
            return;
        }
        $this->form($cert->type, $cert);
    }

    public function delete($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        if ($this->studentcertificate_m->get($id)) {
            $this->studentcertificate_m->delete($id);
            $this->session->set_flashdata('success', 'Certificate deleted.');
        }
        redirect(base_url('studentcertificate/index'));
    }

    /** Printable page (no admin layout). ?plain=1 leaves the letterhead out for pre-printed paper. */
    public function view($id = 0)
    {
        if ($this->session->userdata('usertypeID') != 1) {
            redirect(base_url('exceptionpage/error'));
            return;
        }
        $cert = $this->studentcertificate_m->get($id);
        if (!$cert || !isset(Studentcertificate_m::$types[$cert->type])) {
            show_404();
            return;
        }
        $info = $cert->studentID ? $this->studentcertificate_m->student_info($cert->studentID, '') : null;
        $this->load->view('studentcertificate/print', array(
            'cert'      => $cert,
            'd'         => $cert->data,
            'type'      => Studentcertificate_m::$types[$cert->type],
            'photo'     => ($info && $info['photo']) ? base_url('uploads/images/' . $info['photo']) : '',
            'siteinfos' => $this->data['siteinfos'],
            'plain'     => (bool) $this->input->get('plain'),
        ));
    }

    /** AJAX: student details used to pre-fill the form. */
    public function student_info()
    {
        if ($this->session->userdata('usertypeID') != 1) {
            show_404();
            return;
        }
        $info = $this->studentcertificate_m->student_info((int) $this->input->get('studentID'), $this->currentYear());
        $this->output->set_content_type('application/json')->set_output(json_encode($info ?: new stdClass()));
    }

    private function form($type, $cert)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $def    = Studentcertificate_m::$types[$type];
        $errors = array();

        if ($this->input->method() === 'post') {
            $refYear   = Studentcertificate_m::full_year($this->input->post('ref_year'));
            $refNo     = (int) $this->input->post('ref_no');
            $certDate  = (string) $this->input->post('cert_date');
            $studentID = (int) $this->input->post('studentID');

            $data = array();
            foreach ($def['fields'] as $key => $field) {
                $value = trim((string) $this->input->post('f_' . $key));
                if (isset($field['options']) && $value !== '' && !isset($field['options'][$value])) {
                    $value = '';
                }
                if (!empty($field['numeric']) && $value !== '') {
                    $value = str_replace(array(',', ' ', '₹'), '', $value);
                    if (!is_numeric($value)) {
                        $errors[] = $field['label'] . ' must be a number.';
                    }
                }
                if (!empty($field['required']) && $value === '') {
                    $errors[] = $field['label'] . ' is required.';
                }
                $data[$key] = $value;
            }
            if ($refNo < 1 || $refYear === '') {
                $errors[] = 'Reference number and year are required.';
            } elseif ($this->studentcertificate_m->ref_taken($refNo, $refYear, $cert ? $cert->id : null)) {
                $errors[] = "Ref. No. $refNo/$refYear is already used by another certificate.";
            }
            if (!strtotime($certDate)) {
                $errors[] = 'Date is required.';
            }

            if (!$errors) {
                $id = $this->studentcertificate_m->save(array(
                    'type'         => $type,
                    'ref_no'       => $refNo,
                    'ref_year'     => $refYear,
                    'cert_date'    => date('Y-m-d', strtotime($certDate)),
                    'studentID'    => $studentID ?: null,
                    'student_name' => isset($data['student_name']) ? mb_substr($data['student_name'], 0, 150) : '',
                    'data'         => $data,
                ) + ($cert ? array() : array(
                    'create_userID'   => $this->session->userdata('loginuserID'),
                    'create_username' => $this->session->userdata('username'),
                )), $cert ? $cert->id : null);
                $this->session->set_flashdata('success', $cert ? 'Certificate updated.' : 'Certificate generated.');
                redirect(base_url('studentcertificate/index?printed=' . $id));
                return;
            }
        }

        // New certificate: pre-fill from ?studentID= and the type's defaults.
        $prefill = array();
        if (!$cert) {
            $year = $this->currentYear();
            $info = $this->studentcertificate_m->student_info((int) $this->input->get('studentID'), $year);
            foreach ($def['fields'] as $key => $field) {
                if (isset($field['from']) && $info && isset($info[$field['from']])) {
                    $prefill[$key] = $info[$field['from']];
                } elseif (isset($field['from']) && $field['from'] === 'academic_year') {
                    $prefill[$key] = $year;
                } elseif (isset($field['default'])) {
                    $prefill[$key] = $field['default'];
                }
            }
            $prefill['studentID'] = $info ? $info['studentID'] : '';
            $prefill['ref_year']  = $year;
            $prefill['ref_no']    = $this->studentcertificate_m->next_ref_no($year);
            $prefill['cert_date'] = date('Y-m-d');
        }

        $this->data['headerassets'] = array(
            'css' => array('assets/select2/css/select2.css', 'assets/select2/css/select2-bootstrap.css'),
            'js'  => array('assets/select2/select2.js'),
        );
        $this->data['type']     = $type;
        $this->data['def']      = $def;
        $this->data['cert']     = $cert;
        $this->data['prefill']  = $prefill;
        $this->data['errors']   = $errors;
        $this->data['students'] = $this->studentcertificate_m->student_options();
        $this->data['subview']  = 'studentcertificate/form';
        $this->load->view('_layout_main', $this->data);
    }

    /** Selected academic year as "2026-2027". */
    private function currentYear()
    {
        $year = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID' => $this->session->userdata('defaultschoolyearID')));
        if ($year && $year->schoolyear) {
            return Studentcertificate_m::full_year($year->schoolyear);
        }
        $y = (int) date('Y') - ((int) date('n') < 6 ? 1 : 0);
        return $y . '-' . ($y + 1);
    }
}

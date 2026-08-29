<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Holisticreport extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('section_m');
        $this->load->model('studentrelation_m');
        $this->load->model('holisticprogress_m');
        $this->load->model('schoolyear_m');
        $this->load->model('teacherclasses_m');
        $this->load->model('sattendance_m');
    }

    // -------------------------------------------------------------------------
    // INDEX
    // -------------------------------------------------------------------------
    public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array(
                'assets/select2/css/select2.css',
                'assets/select2/css/select2-bootstrap.css',
            ),
            'js' => array(
                'assets/select2/select2.js',
            ),
        );
        $this->data['classes'] = $this->classes_m->general_get_classes();
        $this->data['subview'] = 'report/holistic/index';
        $this->load->view('_layout_main', $this->data);
    }

    // -------------------------------------------------------------------------
    // AJAX – Section dropdown
    // -------------------------------------------------------------------------
    public function getSection()
    {
        $classesID = (int) $this->input->post('classesID');
        if ($classesID <= 0) {
            echo "<option value='0'>Please Select Section</option>";
            return;
        }
        $sections = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
        echo "<option value='0'>Please Select Section</option>";
        if (customCompute($sections)) {
            foreach ($sections as $section) {
                echo "<option value='" . $section->sectionID . "'>" . $section->section . "</option>";
            }
        }
    }

    // -------------------------------------------------------------------------
    // AJAX – Student list
    // -------------------------------------------------------------------------
    public function getStudentList()
    {
        $retArray = array('status' => FALSE, 'render' => '');
        if ($_POST) {
            $classesID    = (int) $this->input->post('classesID');
            $sectionID    = (int) $this->input->post('sectionID');
            $schoolyearID = (int) $this->session->userdata('defaultschoolyearID');

            if ($classesID > 0) {
                $queryArray = array(
                    'srschoolyearID' => $schoolyearID,
                    'srclassesID'    => $classesID,
                );
                if ($sectionID > 0) {
                    $queryArray['srsectionID'] = $sectionID;
                }
                $this->data['students']  = $this->studentrelation_m->general_get_order_by_student($queryArray);
                $this->data['classesID'] = $classesID;
                $this->data['sectionID'] = $sectionID;
                $retArray['render']      = $this->load->view('report/holistic/student_list', $this->data, TRUE);
                $retArray['status']      = TRUE;
            }
        }
        echo json_encode($retArray);
        exit;
    }

    // -------------------------------------------------------------------------
    // PRIVATE HELPER – read competencies safely from $_POST
    //
    // WHY bypass $this->input->post() here?
    // CodeIgniter's global XSS filter mangles array KEYS that contain dots
    // and hyphens. For example 'C-1.1' gets corrupted to 'C-11' or similar.
    // Reading directly from $_POST and whitelisting values ourselves is the
    // only safe way to preserve keys like 'C-1.1', 'C-8.13', 'C-10.5'.
    // -------------------------------------------------------------------------
    private function _get_competencies_from_post()
    {
        $competencies   = array();
        $allowed_levels = array('Beginner', 'Progressing', 'Proficient', '');

        if (!isset($_POST['competencies']) || !is_array($_POST['competencies'])) {
            return $competencies;
        }

        foreach ($_POST['competencies'] as $raw_key => $terms) {
            // Sanitize key: allow only letters, digits, hyphen, dot → e.g. "C-8.13"
            $comp_key = preg_replace('/[^A-Za-z0-9\-\.]/', '', (string) $raw_key);
            if (empty($comp_key) || !is_array($terms)) continue;

            foreach ($terms as $raw_term => $raw_level) {
                // Sanitize term: allow only lowercase letters + digits → "t1", "t2"
                $term = preg_replace('/[^a-z0-9]/', '', strtolower((string) $raw_term));
                // Whitelist level value
                $level = in_array($raw_level, $allowed_levels, true) ? $raw_level : '';
                $competencies[$comp_key][$term] = $level;
            }
        }

        return $competencies;
    }

    // -------------------------------------------------------------------------
    // ADD / EDIT information form
    // -------------------------------------------------------------------------
    public function add_information($studentID, $classesID)
    {
        $studentID    = (int) $studentID;
        $classesID    = (int) $classesID;
        $schoolyearID = (int) $this->session->userdata('defaultschoolyearID');

        $this->data['student'] = $this->studentrelation_m->get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
            'srclassesID'    => $classesID,
        ));

        if (!customCompute($this->data['student'])) {
            $this->session->set_flashdata('error', 'Student not found');
            redirect(base_url('holisticreport/index'));
        }

        $this->data['classesID'] = $classesID;
        $this->data['sectionID'] = (int) $this->data['student']->srsectionID;

        $existing_record = $this->holisticprogress_m->get_single_holisticprogress(array(
            'studentID'    => $studentID,
            'schoolyearID' => $schoolyearID,
        ));
        $this->data['existing_data'] = $existing_record;

        if ($_POST) {

            // ── Photo uploads ────────────────────────────────────────────────
            $this->load->library('upload');
            $upload_path = FCPATH . 'uploads/holistic_photos/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 5120;
            $config['encrypt_name']  = TRUE;
            $this->upload->initialize($config);

            $photo_fields    = array('portfolio_snapshot', 'class_group_photo', 'activity_highlight_1', 'activity_highlight_2');
            $uploaded_photos = array();
            foreach ($photo_fields as $field) {
                if (!empty($_FILES[$field]['name'])) {
                    if ($this->upload->do_upload($field)) {
                        $uploaded_photos[$field] = $this->upload->data('file_name');
                    }
                }
            }

            // ── Health data ──────────────────────────────────────────────────
            $health_data = array(
                't1' => array(
                    'ft'     => $this->input->post('t1_height_ft'),
                    'weight' => $this->input->post('t1_weight'),
                ),
                't2' => array(
                    'ft'     => $this->input->post('t2_height_ft'),
                    'weight' => $this->input->post('t2_weight'),
                ),
            );

            // ── Feel at school (7 questions, index 0–6) ───────────────────────
            $feel_at_school = array();
            for ($i = 0; $i <= 6; $i++) {
                $feel_at_school[$i] = $this->input->post('feel_' . $i);
            }

            // ── Self assessment ───────────────────────────────────────────────
            $self_assessment = array(
                't1' => array(
                    0 => $this->input->post('self_t1_0'),
                    1 => $this->input->post('self_t1_1'),
                    2 => $this->input->post('self_t1_2'),
                ),
            );

            // ── Peer assessment ───────────────────────────────────────────────
            $peer_assessment = array(
                't1' => array(
                    0 => $this->input->post('peer_t1_0'),
                    1 => $this->input->post('peer_t1_1'),
                    2 => $this->input->post('peer_t1_2'),
                ),
            );

            // ── Parent feedback ───────────────────────────────────────────────
            $parent_feedback = array(
                't1' => array(
                    0 => $this->input->post('parent_t1_0'),
                    1 => $this->input->post('parent_t1_1'),
                    2 => $this->input->post('parent_t1_2'),
                    3 => $this->input->post('parent_t1_3'),
                ),
            );

            // ── Competencies – read from $_POST directly (CI XSS fix) ────────
            $competencies = $this->_get_competencies_from_post();
            // ── Annual summary ────────────────────────────────────────────────
            $annual_summary_raw = $this->input->post('annual_summary');
            $annual_summary     = is_array($annual_summary_raw) ? $annual_summary_raw : array();

            // ── Build DB array ────────────────────────────────────────────────
            $db_array = array(
                'studentID'    => $studentID,
                'classesID'    => $classesID,
                'sectionID'    => (int) $this->data['sectionID'],
                'schoolyearID' => $schoolyearID,

                'ambition'    => $this->input->post('ambition'),
                'best_friend' => $this->input->post('best_friend'),
                'fav_colour'  => $this->input->post('fav_colour'),
                'fav_food'    => $this->input->post('fav_food'),

                'interests'        => json_encode((array) $this->input->post('interests')),
                'health_data'      => json_encode($health_data),
                'feel_at_school'   => json_encode($feel_at_school),
                'competencies'     => json_encode($competencies),
                'self_assessment'  => json_encode($self_assessment),
                'peer_assessment'  => json_encode($peer_assessment),
                'parent_feedback'  => json_encode($parent_feedback),
                'teacher_remarks'  => $this->input->post('teacher_remarks'),
                'selfNotes'  => $this->input->post('selfNotes'),
                'peerNotes'  => $this->input->post('peerNotes'),
                'teacher_evidence' => $this->input->post('teacher_evidence'),
                'annual_summary'   => json_encode($annual_summary),
            );

            // ── Photos: new upload wins, else keep existing ───────────────────
            foreach ($photo_fields as $field) {
                if (isset($uploaded_photos[$field])) {
                    $db_array[$field] = $uploaded_photos[$field];
                } elseif (customCompute($existing_record) && !empty($existing_record->$field)) {
                    $db_array[$field] = $existing_record->$field;
                }
            }

            // ── Insert or Update ──────────────────────────────────────────────
            if (customCompute($existing_record)) {
                $this->holisticprogress_m->update_holisticprogress($db_array, $existing_record->id);
                $this->session->set_flashdata('success', 'Information updated successfully');
            } else {
                $this->holisticprogress_m->insert_holisticprogress($db_array);
                $this->session->set_flashdata('success', 'Information saved successfully');
            }

            redirect(base_url('holisticreport/index'));
        }

        $this->data['subview'] = 'report/holistic/add_information';
        $this->load->view('_layout_main', $this->data);
    }

    // -------------------------------------------------------------------------
    // GENERATE REPORT
    // -------------------------------------------------------------------------
    public function generate_report_1($studentID, $classesID)
    {
        $studentID    = (int) $studentID;
        $classesID    = (int) $classesID;
        $schoolyearID = (int) $this->session->userdata('defaultschoolyearID');

        $this->data['student'] = $this->studentrelation_m->get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
            'srclassesID'    => $classesID,
        ));
        if (!customCompute($this->data['student'])) {
            show_404();
        }

        $this->data['classes']    = $this->classes_m->get_single_classes(array('classesID' => $classesID));
        $this->data['section']    = $this->section_m->get_single_section(array('sectionID' => $this->data['student']->srsectionID));
        $this->data['schoolyear'] = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID' => $schoolyearID));

        $holistic_record = $this->holisticprogress_m->get_single_holisticprogress(array(
            'studentID'    => $studentID,
            'schoolyearID' => $schoolyearID,
        ));

        if (customCompute($holistic_record)) {
            $this->data['holistic'] = $holistic_record;

            $this->data['interests']      = json_decode($holistic_record->interests,      true) ?: array();
            $this->data['health']         = json_decode($holistic_record->health_data,    true) ?: array();
            $this->data['feel_at_school'] = json_decode($holistic_record->feel_at_school, true) ?: array();

            $comps                         = json_decode($holistic_record->competencies,   true) ?: array();
            $this->data['competencies']    = $comps;
            $this->data['comps']           = $comps;

            $summary                       = json_decode($holistic_record->annual_summary, true) ?: array();
            $this->data['annual_summary']  = $summary;
            $this->data['summary']         = $summary;

            $this->data['self_assessment'] = json_decode($holistic_record->self_assessment, true) ?: array();
            $this->data['peer_assessment'] = json_decode($holistic_record->peer_assessment, true) ?: array();
            $this->data['parent_feedback'] = json_decode($holistic_record->parent_feedback, true) ?: array();

            $this->data['portfolio_snapshot']   = !empty($holistic_record->portfolio_snapshot)   ? $holistic_record->portfolio_snapshot   : '';
            $this->data['class_group_photo']    = !empty($holistic_record->class_group_photo)    ? $holistic_record->class_group_photo    : '';
            $this->data['activity_highlight_1'] = !empty($holistic_record->activity_highlight_1) ? $holistic_record->activity_highlight_1 : '';
            $this->data['activity_highlight_2'] = !empty($holistic_record->activity_highlight_2) ? $holistic_record->activity_highlight_2 : '';

        } else {
            $this->data['holistic']             = null;
            $this->data['interests']            = array();
            $this->data['health']               = array();
            $this->data['feel_at_school']       = array();
            $this->data['competencies']         = array();
            $this->data['comps']                = array();
            $this->data['annual_summary']       = array();
            $this->data['summary']              = array();
            $this->data['self_assessment']      = array();
            $this->data['peer_assessment']      = array();
            $this->data['parent_feedback']      = array();
            $this->data['portfolio_snapshot']   = '';
            $this->data['class_group_photo']    = '';
            $this->data['activity_highlight_1'] = '';
            $this->data['activity_highlight_2'] = '';
        }

        $this->data['term'] = 't1';

        // ── Attendance ────────────────────────────────────────────────────────
        
        $teacher_data      = $this->teacherclasses_m->get_single_teacher_name($classesID);
        $this->data['teacher_sign']  = ($teacher_data[0]==null)?'assets/sign/17.png':$teacher_data[0];
        $this->data['teacher_name']  = $teacher_data[1] ?: 'Class Teacher';
       
        if($classesID == 1){
                $student = $this->sattendance_m->get_student_attendance_master($studentID);
                //var_dump($student);die;
                $working_days = $this->sattendance_m->get_working_days();

                $attendance_report = [];

                $month_map = [
                    '06'=>'Jun-25',
                    '07'=>'Jul-25',
                    '08'=>'Aug-25',
                    '09'=>'Sep-25',
                    '10'=>'Oct-25',
                    '11'=>'Nov-25',
                    '12'=>'Dec-25',
                    '01'=>'Jan-26',
                    '02'=>'Feb-26',
                    '03'=>'Mar-26'
                ];

                foreach($working_days as $wd){

                    $month = date('m', strtotime($wd['month_year']));
                    $working = $wd['working_days'];

                    $present = isset($month_map[$month]) ? (int)$student[$month_map[$month]] : 0;

                    $attendance_report[$month] = [
                        'working'=>$working,
                        'present'=>$present,
                        'percentage'=> ($working>0) ? round(($present/$working)*100,2) : 0
                    ];
                }
                
                $this->data['attendance_report'] = $attendance_report;
            }else{
                $academic_months    = array('04','05','06','07','08','09','10','11','12','01','02','03');
                $attendance_results = array();
        
                $attendance_records = $this->sattendance_m->get_order_by_attendance(array(
                    'studentID'    => $studentID,
                    'schoolyearID' => $schoolyearID,
                ));
        
                foreach ($academic_months as $m) {
                    $working_days = 0;
                    $present_days = 0;
                    $month_record = null;
        
                    if (!empty($attendance_records)) {
                        foreach ($attendance_records as $record) {
                            if (!empty($record->monthyear) && date('m', strtotime('01-' . $record->monthyear)) == $m) {
                                $month_record = $record;
                               // break;
                            }
                        }
                    }
        
                    if ($month_record) {
                        for ($i = 1; $i <= 31; $i++) {
                            $day_field = 'a' . $i;
                            if (!property_exists($month_record, $day_field)) continue;
                            $status = $month_record->$day_field;
                            if ($status !== '' && $status !== null) {
                                $working_days++;
                                if (in_array(strtoupper((string)$status), array('P', 'L'))) {
                                    $present_days++;
                                }
                            }
                        }
                    }
        
                    $attendance_results[$m] = array(
                        'working'    => $working_days,
                        'present'    => $present_days,
                        'percentage' => ($working_days > 0) ? round(($present_days / $working_days) * 100, 1) : 0,
                    );
                }
        
                $this->data['attendance_report'] = $attendance_results;
            }

        $this->load->view('report/holistic/report_card_3', $this->data);
    }

    public function add_information_4($studentID, $classesID)
    {
        $studentID    = (int) $studentID;
        $classesID    = (int) $classesID;
        $schoolyearID = (int) $this->session->userdata('defaultschoolyearID');

        $this->data['student'] = $this->studentrelation_m->get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
            'srclassesID'    => $classesID,
        ));

        if (!customCompute($this->data['student'])) {
            $this->session->set_flashdata('error', 'Student not found');
            redirect(base_url('holisticreport/index'));
        }

        $this->data['classesID'] = $classesID;
        $this->data['sectionID'] = (int) $this->data['student']->srsectionID;

        $existing_record = $this->holisticprogress_m->get_single_holisticprogress(array(
            'studentID'    => $studentID,
            'schoolyearID' => $schoolyearID,
        ));
        $this->data['existing_data'] = $existing_record;

        if ($_POST) {

            // ── Photo uploads ────────────────────────────────────────────────
            $this->load->library('upload');
            $upload_path = FCPATH . 'uploads/holistic_photos/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 5120;
            $config['encrypt_name']  = TRUE;
            $this->upload->initialize($config);

            $photo_fields    = array('portfolio_snapshot', 'class_group_photo', 'activity_highlight_1', 'activity_highlight_2');
            $uploaded_photos = array();
            foreach ($photo_fields as $field) {
                if (!empty($_FILES[$field]['name'])) {
                    if ($this->upload->do_upload($field)) {
                        $uploaded_photos[$field] = $this->upload->data('file_name');
                    }
                }
            }

            // ── Health data ──────────────────────────────────────────────────
            $health_data = array(
                't1' => array(
                    'ft'     => $this->input->post('t1_height_ft'),
                    'weight' => $this->input->post('t1_weight'),
                ),
                't2' => array(
                    'ft'     => $this->input->post('t2_height_ft'),
                    'weight' => $this->input->post('t2_weight'),
                ),
            );

            // ── Feel at school (7 questions, index 0–6) ───────────────────────
            $feel_at_school = array();
            for ($i = 0; $i <= 6; $i++) {
                $feel_at_school[$i] = $this->input->post('feel_' . $i);
            }

            // ── Self assessment ───────────────────────────────────────────────
            $self_assessment = array(
                't1' => array(
                    0 => $this->input->post('self_t1_0'),
                    1 => $this->input->post('self_t1_1'),
                    2 => $this->input->post('self_t1_2'),
                ),
            );

            // ── Peer assessment ───────────────────────────────────────────────
            $peer_assessment = array(
                't1' => array(
                    0 => $this->input->post('peer_t1_0'),
                    1 => $this->input->post('peer_t1_1'),
                    2 => $this->input->post('peer_t1_2'),
                ),
            );

            // ── Parent feedback ───────────────────────────────────────────────
            $parent_feedback = array(
                't1' => array(
                    0 => $this->input->post('parent_t1_0'),
                    1 => $this->input->post('parent_t1_1'),
                    2 => $this->input->post('parent_t1_2'),
                    3 => $this->input->post('parent_t1_3'),
                ),
            );

            // ── Competencies – read from $_POST directly (CI XSS fix) ────────
            $competencies = $this->_get_competencies_from_post();
            // ── Annual summary ────────────────────────────────────────────────
            $annual_summary_raw = $this->input->post('annual_summary');
            $annual_summary     = is_array($annual_summary_raw) ? $annual_summary_raw : array();

            // ── Build DB array ────────────────────────────────────────────────
            $db_array = array(
                'studentID'    => $studentID,
                'classesID'    => $classesID,
                'sectionID'    => (int) $this->data['sectionID'],
                'schoolyearID' => $schoolyearID,

                'ambition'    => $this->input->post('ambition'),
                'best_friend' => $this->input->post('best_friend'),
                'fav_colour'  => $this->input->post('fav_colour'),
                'fav_food'    => $this->input->post('fav_food'),

                'interests'        => json_encode((array) $this->input->post('interests')),
                'health_data'      => json_encode($health_data),
                'feel_at_school'   => json_encode($feel_at_school),
                'competencies'     => json_encode($competencies),
                'self_assessment'  => json_encode($self_assessment),
                'peer_assessment'  => json_encode($peer_assessment),
                'parent_feedback'  => json_encode($parent_feedback),
                'teacher_remarks'  => $this->input->post('teacher_remarks'),
                'selfNotes'  => $this->input->post('selfNotes'),
                'peerNotes'  => $this->input->post('peerNotes'),
                'teacher_evidence' => $this->input->post('teacher_evidence'),
                'annual_summary'   => json_encode($annual_summary),
            );

            // ── Photos: new upload wins, else keep existing ───────────────────
            foreach ($photo_fields as $field) {
                if (isset($uploaded_photos[$field])) {
                    $db_array[$field] = $uploaded_photos[$field];
                } elseif (customCompute($existing_record) && !empty($existing_record->$field)) {
                    $db_array[$field] = $existing_record->$field;
                }
            }

            // ── Insert or Update ──────────────────────────────────────────────
            if (customCompute($existing_record)) {
                $this->holisticprogress_m->update_holisticprogress($db_array, $existing_record->id);
                $this->session->set_flashdata('success', 'Information updated successfully');
            } else {
                $this->holisticprogress_m->insert_holisticprogress($db_array);
                $this->session->set_flashdata('success', 'Information saved successfully');
            }

            redirect(base_url('holisticreport/index'));
        }

        $this->data['subview'] = 'report/holistic/add_information_4';
        $this->load->view('_layout_main', $this->data);
    }
    public function generate_report_4($studentID, $classesID)
    {
        $studentID    = (int) $studentID;
        $classesID    = (int) $classesID;
        $schoolyearID = (int) $this->session->userdata('defaultschoolyearID');

        $this->data['student'] = $this->studentrelation_m->get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
            'srclassesID'    => $classesID,
        ));
        if (!customCompute($this->data['student'])) {
            show_404();
        }

        $this->data['classes']    = $this->classes_m->get_single_classes(array('classesID' => $classesID));
        $this->data['section']    = $this->section_m->get_single_section(array('sectionID' => $this->data['student']->srsectionID));
        $this->data['schoolyear'] = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID' => $schoolyearID));

        $holistic_record = $this->holisticprogress_m->get_single_holisticprogress(array(
            'studentID'    => $studentID,
            'schoolyearID' => $schoolyearID,
        ));

        if (customCompute($holistic_record)) {
            $this->data['holistic'] = $holistic_record;

            $this->data['interests']      = json_decode($holistic_record->interests,      true) ?: array();
            $this->data['health']         = json_decode($holistic_record->health_data,    true) ?: array();
            $this->data['feel_at_school'] = json_decode($holistic_record->feel_at_school, true) ?: array();

            $comps                         = json_decode($holistic_record->competencies,   true) ?: array();
            $this->data['competencies']    = $comps;
            $this->data['comps']           = $comps;

            $summary                       = json_decode($holistic_record->annual_summary, true) ?: array();
            $this->data['annual_summary']  = $summary;
            $this->data['summary']         = $summary;

            $this->data['self_assessment'] = json_decode($holistic_record->self_assessment, true) ?: array();
            $this->data['peer_assessment'] = json_decode($holistic_record->peer_assessment, true) ?: array();
            $this->data['parent_feedback'] = json_decode($holistic_record->parent_feedback, true) ?: array();

            $this->data['portfolio_snapshot']   = !empty($holistic_record->portfolio_snapshot)   ? $holistic_record->portfolio_snapshot   : '';
            $this->data['class_group_photo']    = !empty($holistic_record->class_group_photo)    ? $holistic_record->class_group_photo    : '';
            $this->data['activity_highlight_1'] = !empty($holistic_record->activity_highlight_1) ? $holistic_record->activity_highlight_1 : '';
            $this->data['activity_highlight_2'] = !empty($holistic_record->activity_highlight_2) ? $holistic_record->activity_highlight_2 : '';

        } else {
            $this->data['holistic']             = null;
            $this->data['interests']            = array();
            $this->data['health']               = array();
            $this->data['feel_at_school']       = array();
            $this->data['competencies']         = array();
            $this->data['comps']                = array();
            $this->data['annual_summary']       = array();
            $this->data['summary']              = array();
            $this->data['self_assessment']      = array();
            $this->data['peer_assessment']      = array();
            $this->data['parent_feedback']      = array();
            $this->data['portfolio_snapshot']   = '';
            $this->data['class_group_photo']    = '';
            $this->data['activity_highlight_1'] = '';
            $this->data['activity_highlight_2'] = '';
        }

        $this->data['term'] = 't1';

        // ── Attendance ────────────────────────────────────────────────────────
        $academic_months    = array('04','05','06','07','08','09','10','11','12','01','02','03');
        $attendance_results = array();

        $attendance_records = $this->sattendance_m->get_order_by_attendance(array(
            'studentID'    => $studentID,
            'schoolyearID' => $schoolyearID,
        ));
        
        foreach ($academic_months as $m) {
            $working_days = 0;
            $present_days = 0;
            $month_record = null;

            if (!empty($attendance_records)) {
                foreach ($attendance_records as $record) {
                    if (!empty($record->monthyear) && date('m', strtotime('01-' . $record->monthyear)) == $m) {
                        $month_record = $record;
                        //break;
                    }
                }
            }

            if ($month_record) {
                for ($i = 1; $i <= 31; $i++) {
                    $day_field = 'a' . $i;
                    if (!property_exists($month_record, $day_field)) continue;
                    $status = $month_record->$day_field;
                    if ($status !== '' && $status !== null) {
                        $working_days++;
                        if (in_array(strtoupper((string)$status), array('P', 'L'))) {
                            $present_days++;
                        }
                    }
                }
            }

            $attendance_results[$m] = array(
                'working'    => $working_days,
                'present'    => $present_days,
                'percentage' => ($working_days > 0) ? round(($present_days / $working_days) * 100, 1) : 0,
            );
        }

        $this->data['attendance_report'] = $attendance_results;
        $teacher_data      = $this->teacherclasses_m->get_single_teacher_name($classesID);
        $this->data['teacher_sign']  = ($teacher_data[0]==null)?'assets/sign/17.png':$teacher_data[0];
        $this->data['teacher_name']  = $teacher_data[1] ?: 'Class Teacher';

        $this->load->view('report/holistic/report_card_4', $this->data);
    }
    public function add_information_5($studentID, $classesID)
    {
        $studentID    = (int) $studentID;
        $classesID    = (int) $classesID;
        $schoolyearID = (int) $this->session->userdata('defaultschoolyearID');

        $this->data['student'] = $this->studentrelation_m->get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
            'srclassesID'    => $classesID,
        ));

        if (!customCompute($this->data['student'])) {
            $this->session->set_flashdata('error', 'Student not found');
            redirect(base_url('holisticreport/index'));
        }

        $this->data['classesID'] = $classesID;
        $this->data['sectionID'] = (int) $this->data['student']->srsectionID;

        $existing_record = $this->holisticprogress_m->get_single_holisticprogress(array(
            'studentID'    => $studentID,
            'schoolyearID' => $schoolyearID,
        ));
        $this->data['existing_data'] = $existing_record;

        if ($_POST) {

            // ── Photo uploads ────────────────────────────────────────────────
            $this->load->library('upload');
            $upload_path = FCPATH . 'uploads/holistic_photos/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'gif|jpg|png|jpeg';
            $config['max_size']      = 5120;
            $config['encrypt_name']  = TRUE;
            $this->upload->initialize($config);

            $photo_fields    = array('portfolio_snapshot', 'class_group_photo', 'activity_highlight_1', 'activity_highlight_2');
            $uploaded_photos = array();
            foreach ($photo_fields as $field) {
                if (!empty($_FILES[$field]['name'])) {
                    if ($this->upload->do_upload($field)) {
                        $uploaded_photos[$field] = $this->upload->data('file_name');
                    }
                }
            }

            // ── Health data ──────────────────────────────────────────────────
            $health_data = array(
                't1' => array(
                    'ft'     => $this->input->post('t1_height_ft'),
                    'weight' => $this->input->post('t1_weight'),
                ),
                't2' => array(
                    'ft'     => $this->input->post('t2_height_ft'),
                    'weight' => $this->input->post('t2_weight'),
                ),
            );

            // ── Feel at school (7 questions, index 0–6) ───────────────────────
            $feel_at_school = array();
            for ($i = 0; $i <= 6; $i++) {
                $feel_at_school[$i] = $this->input->post('feel_' . $i);
            }

            // ── Self assessment ───────────────────────────────────────────────
            $self_assessment = array(
                't1' => array(
                    0 => $this->input->post('self_t1_0'),
                    1 => $this->input->post('self_t1_1'),
                    2 => $this->input->post('self_t1_2'),
                ),
            );

            // ── Peer assessment ───────────────────────────────────────────────
            $peer_assessment = array(
                't1' => array(
                    0 => $this->input->post('peer_t1_0'),
                    1 => $this->input->post('peer_t1_1'),
                    2 => $this->input->post('peer_t1_2'),
                ),
            );

            // ── Parent feedback ───────────────────────────────────────────────
            $parent_feedback = array(
                't1' => array(
                    0 => $this->input->post('parent_t1_0'),
                    1 => $this->input->post('parent_t1_1'),
                    2 => $this->input->post('parent_t1_2'),
                    3 => $this->input->post('parent_t1_3'),
                ),
            );

            // ── Competencies – read from $_POST directly (CI XSS fix) ────────
            $competencies = $this->_get_competencies_from_post();
            // ── Annual summary ────────────────────────────────────────────────
            $annual_summary_raw = $this->input->post('annual_summary');
            $annual_summary     = is_array($annual_summary_raw) ? $annual_summary_raw : array();

            // ── Build DB array ────────────────────────────────────────────────
            $db_array = array(
                'studentID'    => $studentID,
                'classesID'    => $classesID,
                'sectionID'    => (int) $this->data['sectionID'],
                'schoolyearID' => $schoolyearID,

                'ambition'    => $this->input->post('ambition'),
                'best_friend' => $this->input->post('best_friend'),
                'fav_colour'  => $this->input->post('fav_colour'),
                'fav_food'    => $this->input->post('fav_food'),

                'interests'        => json_encode((array) $this->input->post('interests')),
                'health_data'      => json_encode($health_data),
                'feel_at_school'   => json_encode($feel_at_school),
                'competencies'     => json_encode($competencies),
                'self_assessment'  => json_encode($self_assessment),
                'peer_assessment'  => json_encode($peer_assessment),
                'parent_feedback'  => json_encode($parent_feedback),
                'teacher_remarks'  => $this->input->post('teacher_remarks'),
                'selfNotes'  => $this->input->post('selfNotes'),
                'peerNotes'  => $this->input->post('peerNotes'),
                'teacher_evidence' => $this->input->post('teacher_evidence'),
                'annual_summary'   => json_encode($annual_summary),
            );

            // ── Photos: new upload wins, else keep existing ───────────────────
            foreach ($photo_fields as $field) {
                if (isset($uploaded_photos[$field])) {
                    $db_array[$field] = $uploaded_photos[$field];
                } elseif (customCompute($existing_record) && !empty($existing_record->$field)) {
                    $db_array[$field] = $existing_record->$field;
                }
            }

            // ── Insert or Update ──────────────────────────────────────────────
            if (customCompute($existing_record)) {
                $this->holisticprogress_m->update_holisticprogress($db_array, $existing_record->id);
                $this->session->set_flashdata('success', 'Information updated successfully');
            } else {
                $this->holisticprogress_m->insert_holisticprogress($db_array);
                $this->session->set_flashdata('success', 'Information saved successfully');
            }

            redirect(base_url('holisticreport/index'));
        }

        $this->data['subview'] = 'report/holistic/add_information_5';
        $this->load->view('_layout_main', $this->data);
    }
    public function generate_report_5($studentID, $classesID)
    {
        $studentID    = (int) $studentID;
        $classesID    = (int) $classesID;
        $schoolyearID = (int) $this->session->userdata('defaultschoolyearID');

        $this->data['student'] = $this->studentrelation_m->get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
            'srclassesID'    => $classesID,
        ));
        if (!customCompute($this->data['student'])) {
            show_404();
        }

        $this->data['classes']    = $this->classes_m->get_single_classes(array('classesID' => $classesID));
        $this->data['section']    = $this->section_m->get_single_section(array('sectionID' => $this->data['student']->srsectionID));
        $this->data['schoolyear'] = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID' => $schoolyearID));

        $holistic_record = $this->holisticprogress_m->get_single_holisticprogress(array(
            'studentID'    => $studentID,
            'schoolyearID' => $schoolyearID,
        ));

        if (customCompute($holistic_record)) {
            $this->data['holistic'] = $holistic_record;

            $this->data['interests']      = json_decode($holistic_record->interests,      true) ?: array();
            $this->data['health']         = json_decode($holistic_record->health_data,    true) ?: array();
            $this->data['feel_at_school'] = json_decode($holistic_record->feel_at_school, true) ?: array();

            $comps                         = json_decode($holistic_record->competencies,   true) ?: array();
            $this->data['competencies']    = $comps;
            $this->data['comps']           = $comps;

            $summary                       = json_decode($holistic_record->annual_summary, true) ?: array();
            $this->data['annual_summary']  = $summary;
            $this->data['summary']         = $summary;

            $this->data['self_assessment'] = json_decode($holistic_record->self_assessment, true) ?: array();
            $this->data['peer_assessment'] = json_decode($holistic_record->peer_assessment, true) ?: array();
            $this->data['parent_feedback'] = json_decode($holistic_record->parent_feedback, true) ?: array();

            $this->data['portfolio_snapshot']   = !empty($holistic_record->portfolio_snapshot)   ? $holistic_record->portfolio_snapshot   : '';
            $this->data['class_group_photo']    = !empty($holistic_record->class_group_photo)    ? $holistic_record->class_group_photo    : '';
            $this->data['activity_highlight_1'] = !empty($holistic_record->activity_highlight_1) ? $holistic_record->activity_highlight_1 : '';
            $this->data['activity_highlight_2'] = !empty($holistic_record->activity_highlight_2) ? $holistic_record->activity_highlight_2 : '';

        } else {
            $this->data['holistic']             = null;
            $this->data['interests']            = array();
            $this->data['health']               = array();
            $this->data['feel_at_school']       = array();
            $this->data['competencies']         = array();
            $this->data['comps']                = array();
            $this->data['annual_summary']       = array();
            $this->data['summary']              = array();
            $this->data['self_assessment']      = array();
            $this->data['peer_assessment']      = array();
            $this->data['parent_feedback']      = array();
            $this->data['portfolio_snapshot']   = '';
            $this->data['class_group_photo']    = '';
            $this->data['activity_highlight_1'] = '';
            $this->data['activity_highlight_2'] = '';
        }

        $this->data['term'] = 't1';

        // ── Attendance ────────────────────────────────────────────────────────
        $academic_months    = array('04','05','06','07','08','09','10','11','12','01','02','03');
        $attendance_results = array();

        $attendance_records = $this->sattendance_m->get_order_by_attendance(array(
            'studentID'    => $studentID,
            'schoolyearID' => $schoolyearID,
        ));
       //var_dump($attendance_records);die;
        foreach ($academic_months as $m) {
            $working_days = 0;
            $present_days = 0;
            $month_record = null;

            if (!empty($attendance_records)) {
                foreach ($attendance_records as $record) {
                    if (!empty($record->monthyear) && date('m', strtotime('01-' . $record->monthyear)) == $m) {
                        $month_record = $record;
                        //break;
                    }
                }
            }

            if ($month_record) {
                for ($i = 1; $i <= 31; $i++) {
                    $day_field = 'a' . $i;
                    if (!property_exists($month_record, $day_field)) continue;
                    $status = $month_record->$day_field;
                    if ($status !== '' && $status !== null && !empty($status)) {
                        $working_days++;
                        if (in_array(strtoupper((string)$status), array('P', 'L'))) {
                            $present_days++;
                        }
                    }
                }
            }

            $attendance_results[$m] = array(
                'working'    => $working_days,
                'present'    => $present_days,
                'percentage' => ($working_days > 0) ? round(($present_days / $working_days) * 100, 1) : 0,
            );
        }

        $this->data['attendance_report'] = $attendance_results;
        $teacher_data      = $this->teacherclasses_m->get_single_teacher_name($classesID);
        $this->data['teacher_sign']  = ($teacher_data[0]==null)?'assets/sign/17.png':$teacher_data[0];
        $this->data['teacher_name']  = $teacher_data[1] ?: 'Class Teacher';

        $this->load->view('report/holistic/report_card_5', $this->data);
    }
    public function generate_report_6($studentID, $classesID)
    {
        $studentID    = (int) $studentID;
        $classesID    = (int) $classesID;
        $schoolyearID = (int) $this->session->userdata('defaultschoolyearID');

        $this->data['student'] = $this->studentrelation_m->get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
            'srclassesID'    => $classesID,
        ));
        if (!customCompute($this->data['student'])) {
            show_404();
        }

        $this->data['classes']    = $this->classes_m->get_single_classes(array('classesID' => $classesID));
        $this->data['section']    = $this->section_m->get_single_section(array('sectionID' => $this->data['student']->srsectionID));
        $this->data['schoolyear'] = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID' => $schoolyearID));

        $holistic_record = $this->holisticprogress_m->get_single_holisticprogress(array(
            'studentID'    => $studentID,
            'schoolyearID' => $schoolyearID,
        ));

        if (customCompute($holistic_record)) {
            $this->data['holistic'] = $holistic_record;

            $this->data['interests']      = json_decode($holistic_record->interests,      true) ?: array();
            $this->data['health']         = json_decode($holistic_record->health_data,    true) ?: array();
            $this->data['feel_at_school'] = json_decode($holistic_record->feel_at_school, true) ?: array();

            $comps                         = json_decode($holistic_record->competencies,   true) ?: array();
            $this->data['competencies']    = $comps;
            $this->data['comps']           = $comps;

            $summary                       = json_decode($holistic_record->annual_summary, true) ?: array();
            $this->data['annual_summary']  = $summary;
            $this->data['summary']         = $summary;

            $this->data['self_assessment'] = json_decode($holistic_record->self_assessment, true) ?: array();
            $this->data['peer_assessment'] = json_decode($holistic_record->peer_assessment, true) ?: array();
            $this->data['parent_feedback'] = json_decode($holistic_record->parent_feedback, true) ?: array();

            $this->data['portfolio_snapshot']   = !empty($holistic_record->portfolio_snapshot)   ? $holistic_record->portfolio_snapshot   : '';
            $this->data['class_group_photo']    = !empty($holistic_record->class_group_photo)    ? $holistic_record->class_group_photo    : '';
            $this->data['activity_highlight_1'] = !empty($holistic_record->activity_highlight_1) ? $holistic_record->activity_highlight_1 : '';
            $this->data['activity_highlight_2'] = !empty($holistic_record->activity_highlight_2) ? $holistic_record->activity_highlight_2 : '';

        } else {
            $this->data['holistic']             = null;
            $this->data['interests']            = array();
            $this->data['health']               = array();
            $this->data['feel_at_school']       = array();
            $this->data['competencies']         = array();
            $this->data['comps']                = array();
            $this->data['annual_summary']       = array();
            $this->data['summary']              = array();
            $this->data['self_assessment']      = array();
            $this->data['peer_assessment']      = array();
            $this->data['parent_feedback']      = array();
            $this->data['portfolio_snapshot']   = '';
            $this->data['class_group_photo']    = '';
            $this->data['activity_highlight_1'] = '';
            $this->data['activity_highlight_2'] = '';
        }

        $this->data['term'] = 't1';

        // ── Attendance ────────────────────────────────────────────────────────
        $academic_months    = array('04','05','06','07','08','09','10','11','12','01','02','03');
        $attendance_results = array();

        $attendance_records = $this->sattendance_m->get_order_by_attendance(array(
            'studentID'    => $studentID,
            'schoolyearID' => $schoolyearID,
        ));

        foreach ($academic_months as $m) {
            $working_days = 0;
            $present_days = 0;
            $month_record = null;

            if (!empty($attendance_records)) {
                foreach ($attendance_records as $record) {
                    if (!empty($record->monthyear) && date('m', strtotime('01-' . $record->monthyear)) == $m) {
                        $month_record = $record;
                       // break;
                    }
                }
            }

            if ($month_record) {
                for ($i = 1; $i <= 31; $i++) {
                    $day_field = 'a' . $i;
                    if (!property_exists($month_record, $day_field)) continue;
                    $status = $month_record->$day_field;
                    if ($status !== '' && $status !== null) {
                        $working_days++;
                        if (in_array(strtoupper((string)$status), array('P', 'L'))) {
                            $present_days++;
                        }
                    }
                }
            }

            $attendance_results[$m] = array(
                'working'    => $working_days,
                'present'    => $present_days,
                'percentage' => ($working_days > 0) ? round(($present_days / $working_days) * 100, 1) : 0,
            );
        }

        $this->data['attendance_report'] = $attendance_results;
        $teacher_data      = $this->teacherclasses_m->get_single_teacher_name($classesID);
        $this->data['teacher_sign']  = ($teacher_data[0]==null)?'assets/sign/17.png':$teacher_data[0];
        $this->data['teacher_name']  = $teacher_data[1] ?: 'Class Teacher';

        $this->load->view('report/holistic/report_card_6', $this->data);
    }
    public function generate_report_3($studentID, $classesID)
    {
        $this->generate_report_1($studentID, $classesID);
    }
}
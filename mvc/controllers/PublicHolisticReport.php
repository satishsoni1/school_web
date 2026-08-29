<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class PublicHolisticReport extends Frontend_Controller
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
        $this->load->model('setting_m');
        $this->defaultschoolyearID = (int) $this->uri->segment(5);
        $this->studentID = (int) $this->uri->segment(3);
        $this->classesID = (int) $this->uri->segment(4);
       // var_dump($this->defaultschoolyearID); die;
        //$this->defaultschoolyearID = $this->setting_m->get_setting()->school_year;
        
    }

    // -------------------------------------------------------------------------
    // GENERATE REPORT
    // -------------------------------------------------------------------------
    public function generate_report_1()
    {
        $studentID    = (int) $this->studentID;
        $classesID    = (int) $this->classesID;
        $schoolyearID = (int) $this->defaultschoolyearID;

        $this->data['student'] = $this->studentrelation_m->get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
        ));
        if (!customCompute($this->data['student'])) {
            show_404();
        }

        $this->data['classes']    = $this->classes_m->get_single_classes(array('classesID' => $this->data['student']->srclassesID));
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
    public function generate_report_4()
    {
        $studentID    = (int) $this->studentID;
        $schoolyearID = (int) $this->defaultschoolyearID;
        $this->data['student'] = $this->studentrelation_m->get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
        ));
        if (!customCompute($this->data['student'])) {
            show_404();
        }

        $this->data['classes']    = $this->classes_m->get_single_classes(array('classesID' => $this->data['student']->srclassesID));
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
    
    public function generate_report_5()
    {
        $studentID    = (int) $this->studentID;
        $schoolyearID = (int) $this->defaultschoolyearID;

        $this->data['student'] = $this->studentrelation_m->get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
        ));
        if (!customCompute($this->data['student'])) {
            show_404();
        }

        $this->data['classes']    = $this->classes_m->get_single_classes(array('classesID' => $this->data['student']->srclassesID));
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
    public function generate_report_6()
    {
        $studentID    = (int) $this->studentID;
        $schoolyearID = (int) $this->defaultschoolyearID;

        $this->data['student'] = $this->studentrelation_m->get_single_student(array(
            'srstudentID'    => $studentID,
            'srschoolyearID' => $schoolyearID,
        ));
        if (!customCompute($this->data['student'])) {
            show_404();
        }

        $this->data['classes']    = $this->classes_m->get_single_classes(array('classesID' => $this->data['student']->srclassesID));
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
    public function generate_report_3()
    {
        $this->generate_report_1();
    }
}
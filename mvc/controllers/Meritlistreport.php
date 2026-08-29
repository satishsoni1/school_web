<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Meritlistreport extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("classes_m");
        $this->load->model('section_m');
        $this->load->model("studentrelation_m");
        $this->load->model("exam_m");
        $this->load->model("subject_m");
        $this->load->model("mark_m"); 
        $this->load->model("markclass9_m"); 

        $language = $this->session->userdata('lang');
        $this->lang->load('progresscardreport', $language);
    }

    protected function rules()
    {
        return array(
            array('field' => 'classesID', 'label' => "Class", 'rules' => 'trim|required|xss_clean|callback_unique_data'),
            array('field' => 'sectionID', 'label' => "Section", 'rules' => 'trim|xss_clean'),
            // ExamID removed as per your updated code
        );
    }

    public function index()
    {
        $this->data['headerassets'] = array(
            'css' => array('assets/select2/css/select2.css', 'assets/select2/css/select2-bootstrap.css'),
            'js' => array('assets/select2/select2.js')
        );
        $this->data['classes'] = $this->classes_m->general_get_classes();
        $this->data['exams']   = $this->exam_m->get_exam();
        $this->data["subview"] = "report/meritlist/MeritlistReportView";
        $this->load->view('_layout_main', $this->data);
    }

    public function getMeritlistreport()
    {
        $retArray['status'] = FALSE;
        $retArray['render'] = '';

        if (permissionChecker('meritlistreport') || true) {
            if ($_POST) {
                $rules = $this->rules();
                $this->form_validation->set_rules($rules);

                if ($this->form_validation->run() == FALSE) {
                    $retArray = $this->form_validation->error_array();
                    $retArray['status'] = FALSE;
                    echo json_encode($retArray);
                    exit;
                } else {
                    $classesID    = $this->input->post('classesID');
                    $sectionID    = $this->input->post('sectionID');
                    $schoolyearID = $this->session->userdata('defaultschoolyearID');

                    // Fetch Students
                    $queryArray = ['srschoolyearID' => $schoolyearID, 'srclassesID' => $classesID];
                    if ((int)$sectionID > 0) {
                        $queryArray['srsectionID'] = $sectionID;
                    }
                    $students = $this->studentrelation_m->general_get_order_by_student($queryArray);
                    $student_ranks = [];

                    // ==========================================
                    // BRANCH 1: CLASS 9 LOGIC
                    // ==========================================
                    if ($classesID == 21 || $classesID == "21") {
                        $subjects = $this->subject_m->general_get_order_by_subject(['classesID' => $classesID]);
                        $mArray = ['schoolyearID' => $schoolyearID, 'classesID' => $classesID];
                        
                        // Fetch marks from custom table
                        $class9_marks = $this->markclass9_m->get_order_by_markclass9($mArray);
                       
                        // STRCIT MAPPING: Force integer keys so string "2527" and int 2527 don't fail to match
                        $marks_data = [];
                        if (is_array($class9_marks) || is_object($class9_marks)) {
                            foreach ($class9_marks as $m) {
                                $stID = (int)$m->studentID;
                                $suID = (int)$m->subjectID;
                                $marks_data[$stID][$suID] = $m;
                            }
                        }

                        if (customCompute($students)) {
                            foreach ($students as $student) {
                                
                                // Safely extract student ID (Fallback to studentID if srstudentID is missing)
                                $sID = 0;
                                if (isset($student->srstudentID) && $student->srstudentID) {
                                    $sID = (int)$student->srstudentID;
                                } elseif (isset($student->studentID) && $student->studentID) {
                                    $sID = (int)$student->studentID;
                                }

                                $sName  = isset($student->name) ? $student->name : (isset($student->srname) ? $student->srname : '');
                                $sRoll  = isset($student->roll) ? $student->roll : (isset($student->srroll) ? $student->srroll : '');
                                $sSec   = isset($student->sectionID) ? $student->sectionID : (isset($student->srsectionID) ? $student->srsectionID : 0);
                                $sPhoto = isset($student->photo) ? $student->photo : 'default.png';

                                $grand_total = 0;

                                if (customCompute($subjects)) {
                                    foreach ($subjects as $subject) {
                                        $subjID = (int)$subject->subjectID;
                                        
                                        // Match the exact integer keys
                                        $row = isset($marks_data[$sID][$subjID]) ? $marks_data[$sID][$subjID] : null;

                                        // IT Subject Logic
                                        if (strpos(strtolower($subject->subject), 'information technology') !== false) {
                                            $th = ($row && isset($row->it_theory) && is_numeric(trim((string)$row->it_theory))) ? (float)trim((string)$row->it_theory) : 0;
                                            $pr = ($row && isset($row->it_practical) && is_numeric(trim((string)$row->it_practical))) ? (float)trim((string)$row->it_practical) : 0;
                                            $grand_total += round($th + $pr);
                                        } 
                                        // Standard Scholastic Logic
                                        else if (isset($subject->type) && $subject->type == 1) {
                                            
                                            // Bulletproof extraction of raw string values to float
                                            $pt1_raw  = ($row && isset($row->pt1) && is_numeric(trim((string)$row->pt1))) ? (float)trim((string)$row->pt1) : 0;
                                            $pt2_raw  = ($row && isset($row->pt2) && is_numeric(trim((string)$row->pt2))) ? (float)trim((string)$row->pt2) : 0;
                                            $pt3_raw  = ($row && isset($row->pt3) && is_numeric(trim((string)$row->pt3))) ? (float)trim((string)$row->pt3) : 0;
                                            
                                            $nb_disp  = ($row && isset($row->notebook) && is_numeric(trim((string)$row->notebook))) ? (float)trim((string)$row->notebook) : 0;
                                            $pf_disp  = ($row && isset($row->portfolio) && is_numeric(trim((string)$row->portfolio))) ? (float)trim((string)$row->portfolio) : 0;
                                            $se_disp  = ($row && isset($row->enrichment) && is_numeric(trim((string)$row->enrichment))) ? (float)trim((string)$row->enrichment) : 0;
                                            $ann_disp = ($row && isset($row->annual) && is_numeric(trim((string)$row->annual))) ? (float)trim((string)$row->annual) : 0;

                                            // Math Calculation
                                            $pt1_math = round(($pt1_raw / 30) * 5);
                                            $pt2_math = round(($pt2_raw / 80) * 5);
                                            $pt3_math = round(($pt3_raw / 30) * 5);

                                            $pt_array = [$pt1_math, $pt2_math, $pt3_math];
                                            rsort($pt_array);
                                            $best_two_avg = round(($pt_array[0] + $pt_array[1]) / 2);

                                            $nb_math  = round($nb_disp);
                                            $pf_math  = round($pf_disp);
                                            $se_math  = round($se_disp);
                                            $ann_math = round($ann_disp);

                                            $grand_total += ($best_two_avg + $nb_math + $pf_math + $se_math + $ann_math);
                                        }
                                    }
                                }
                                
                                $student_ranks[] = [
                                    'studentID' => $sID,
                                    'name'      => $sName,
                                    'roll'      => $sRoll,
                                    'photo'     => $sPhoto,
                                    'sectionID' => $sSec,
                                    'total'     => $grand_total 
                                ];
                            }
                        }
                    }
                    // ==========================================
                    // BRANCH 2: STANDARD CLASSES LOGIC
                    // ==========================================
                    else {
                        $mArray = ['schoolyearID' => $schoolyearID, 'classesID' => $classesID];
                        $marks = $this->mark_m->student_all_mark_array($mArray);

                        $student_marks_sum = [];
                        if (is_array($marks) || is_object($marks)) {
                            foreach ($marks as $m) {
                                $stID = (int)$m->studentID;
                                if (!isset($student_marks_sum[$stID])) {
                                    $student_marks_sum[$stID] = 0;
                                }
                                $student_marks_sum[$stID] += (isset($m->mark) && is_numeric(trim((string)$m->mark))) ? (float)trim((string)$m->mark) : 0;
                            }
                        }

                        if (customCompute($students)) {
                            foreach ($students as $student) {
                                
                                $sID = 0;
                                if (isset($student->srstudentID) && $student->srstudentID) {
                                    $sID = (int)$student->srstudentID;
                                } elseif (isset($student->studentID) && $student->studentID) {
                                    $sID = (int)$student->studentID;
                                }

                                $sName  = isset($student->name) ? $student->name : (isset($student->srname) ? $student->srname : '');
                                $sRoll  = isset($student->roll) ? $student->roll : (isset($student->srroll) ? $student->srroll : '');
                                $sSec   = isset($student->sectionID) ? $student->sectionID : (isset($student->srsectionID) ? $student->srsectionID : 0);
                                $sPhoto = isset($student->photo) ? $student->photo : 'default.png';

                                $student_ranks[] = [
                                    'studentID' => $sID,
                                    'name'      => $sName,
                                    'roll'      => $sRoll,
                                    'photo'     => $sPhoto,
                                    'sectionID' => $sSec, 
                                    'total'     => isset($student_marks_sum[$sID]) ? round($student_marks_sum[$sID]) : 0
                                ];
                            }
                        }
                    }

                    // ==========================================
                    // COMMON RANKING LOGIC (Both Branches)
                    // ==========================================
                    usort($student_ranks, function ($a, $b) {
                        return $b['total'] <=> $a['total']; // Sort by total descending
                    });

                    $rank = 1;
                    $actual_position = 1;
                    $prev_score = null;

                    foreach ($student_ranks as $key => $st) {
                        if ($prev_score !== null && $st['total'] < $prev_score) {
                            $rank = $actual_position;
                        }
                        $student_ranks[$key]['rank'] = $rank;
                        $prev_score = $st['total'];
                        $actual_position++;
                    }

                    $this->data['merit_list'] = $student_ranks;
                    $this->data['classes']    = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
                    $this->data['sections']   = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
                    $this->data['classesID']  = $classesID;
                    $this->data['sectionID']  = $sectionID;

                    $retArray['render'] = $this->load->view('report/meritlist/MeritlistReportRender', $this->data, true);
                    $retArray['status'] = TRUE;
                    echo json_encode($retArray);
                    exit();
                }
            }
        }
    }

    public function unique_data($data)
    {
        if ($data != "" && $data === "0") {
            $this->form_validation->set_message('unique_data', 'The %s field is required.');
            return FALSE;
        }
        return TRUE;
    }
}
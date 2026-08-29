<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Progresscardreport extends Admin_Controller
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
		$this->load->model("classes_m");
		$this->load->model('section_m');
		$this->load->model("studentrelation_m");
		$this->load->model("exam_m");
		$this->load->model("markpercentage_m");
		$this->load->model("subject_m");
		$this->load->model("setting_m");
		$this->load->model("mark_m");
		$this->load->model("grade_m");
		$this->load->model("studentgroup_m");
		$this->load->model("marksetting_m");
		$this->load->model("markteacherinput_m");
		$this->load->model("markclass9_m");

		$language = $this->session->userdata('lang');
		$this->lang->load('progresscardreport', $language);
	}

	protected function rules()
	{
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("progresscardreport_class"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("progresscardreport_section"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("progresscardreport_student"),
				'rules' => 'trim|xss_clean'
			),
		);
		return $rules;
	}

	protected function send_pdf_to_mail_rules()
	{
		$rules = array(
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("progresscardreport_class"),
				'rules' => 'trim|required|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'sectionID',
				'label' => $this->lang->line("progresscardreport_section"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'studentID',
				'label' => $this->lang->line("progresscardreport_student"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'to',
				'label' => $this->lang->line("progresscardreport_to"),
				'rules' => 'trim|required|xss_clean|valid_email'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("progresscardreport_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("progresscardreport_message"),
				'rules' => 'trim|xss_clean'
			),
		);
		return $rules;
	}

	public function index()
	{
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/select2/css/select2.css',
				'assets/select2/css/select2-bootstrap.css',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css',
			),
			'js' => array(
				'assets/select2/select2.js',
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js',
			)
		);
		$this->data['classes'] = $this->classes_m->general_get_classes();
		$this->data["subview"] = "report/progresscard/ProgresscardReportView";
		$this->load->view('_layout_main', $this->data);
	}

	public function getProgresscardreport()
	{
		$retArray['status'] = FALSE;
		$retArray['render'] = '';
		if (permissionChecker('progresscardreport')) {
			if ($_POST) {
				$classesID    = $this->input->post('classesID');
				$sectionID    = $this->input->post('sectionID');
				$studentID    = $this->input->post('studentID');
				$termID    = $this->input->post('termID');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$rules = $this->rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
					exit;
				} else {
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;
					$this->data['studentID'] = $studentID;

					$mArray       = [];
					$queryArray   = [];
					$mArray['schoolyearID']        = $schoolyearID;
					$queryArray['srschoolyearID']  = $schoolyearID;
					if ((int)$classesID > 0) {
						$mArray['classesID']       = $classesID;
						$queryArray['srclassesID'] = $classesID;
					}
					if ((int)$sectionID > 0) {
						$mArray['sectionID']       = $sectionID;
						$queryArray['srsectionID'] = $sectionID;
					}
					if ((int)$studentID > 0) {
						$mArray['studentID']       = $studentID;
						$queryArray['srstudentID'] = $studentID;
					}

					$this->data['classes']  = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
					$this->data['groups']   = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');

					$students               = $this->studentrelation_m->general_get_order_by_student($queryArray);
					$marks                  = $this->mark_m->student_all_mark_array($mArray);
					$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 1));
					$optionalSubjects       = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 0));

					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$markpercentagesclassArr = isset($markpercentagesmainArr[$classesID]) ? $markpercentagesmainArr[$classesID] : [];
					$settingExam            = array_keys($markpercentagesclassArr);
					$percentageArr          = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

					$this->data['markpercentagesclassArr'] = $markpercentagesclassArr;
					$this->data['settingmarktypeID']       = $settingmarktypeID;

					$retMark = [];
					if (customCompute($marks)) {
						foreach ($marks as $mark) {
							$retMark[$mark->examID][$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
						}
					}

					$markArray      = [];
					$studentChecker = [];
					$validExam      = [];
					if (customCompute($settingExam)) {
						foreach ($settingExam as $examID) {
							if (customCompute($students)) {
								foreach ($students as $student) {
									$opuniquepercentageArr = [];
									if ($student->sroptionalsubjectID > 0) {
										$opuniquepercentageArr = isset($markpercentagesclassArr[$examID][$student->sroptionalsubjectID]) ? $markpercentagesclassArr[$examID][$student->sroptionalsubjectID] : [];
									}
									$oppercentageMark = 0;
									if (customCompute($mandatorySubjects)) {
										foreach ($mandatorySubjects as $mandatorySubject) {
											$uniquepercentageArr = isset($markpercentagesclassArr[$examID][$mandatorySubject->subjectID]) ? $markpercentagesclassArr[$examID][$mandatorySubject->subjectID] : [];
											$markpercentages     = [];
											if (customCompute($uniquepercentageArr)) {
												$markpercentages = $uniquepercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
											}

											if (customCompute($markpercentages)) {
												foreach ($markpercentages as $markpercentageID) {
													$f = false;
													if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
														$f = true;
													}

													if (isset($retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
														$markArray[$examID][$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
													}



													$f = false;
													if (customCompute($opuniquepercentageArr)) {
														if (isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
															$f = true;
														}
													}
													if (!isset($studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID]) && $f) {
														$oppercentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
														if ($student->sroptionalsubjectID > 0) {

															if (isset($retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																$markArray[$examID][$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
															}
														}
														$studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID] = TRUE;
													}
												}
											}
										}
									}
								}
							}
						}
					}


					$this->data['percentageArr']     = $percentageArr;
					$this->data['grades']            = $this->grade_m->get_grade();
					$this->data['optionalSubjects']  = pluck($optionalSubjects, 'obj', 'subjectID');
					$this->data['mandatorySubjects']  = pluck($mandatorySubjects, 'obj', 'subjectID');
					$this->data['markpercentages']  = $this->marksetting_m->get_marksetting_markpercentages_add($percentageArr);
					$this->data['totalSubject']      = customCompute($mandatorySubjects);
					$this->data['validExams']        = $validExam;
					$this->data['exams']             = pluck($this->exam_m->get_exam(), 'exam', 'examID');;
					$this->data['students']          = $students;
					$this->data['markArray']         = $markArray;
					$this->data['settingExam']       = $settingExam;
					$this->data['re_open_date'] = "2025-04-01";
					$this->data['generate_date'] = "2025-03-21";
					$this->data['termID'] = $termID;
					$retArray['render'] = $this->load->view('report/progresscard/ProgresscardReport', $this->data, true);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit();
				}
			} else {
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['render'] =  $this->load->view('report/reporterror', $this->data, true);
			$retArray['status'] = TRUE;
			echo json_encode($retArray);
			exit;
		}
	}

	public function send_pdf_to_mail()
	{
		$retArray['status']  = FALSE;
		$retArray['message'] = '';
		if (permissionChecker('progresscardreport')) {
			if ($_POST) {
				$to           = $this->input->post('to');
				$subject      = $this->input->post('subject');
				$message      = $this->input->post('message');
				$classesID    = $this->input->post('classesID');
				$sectionID    = $this->input->post('sectionID');
				$studentID    = $this->input->post('studentID');
				$schoolyearID = $this->session->userdata('defaultschoolyearID');

				$rules = $this->send_pdf_to_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
					echo json_encode($retArray);
					exit;
				} else {
					$this->data['classesID'] = $classesID;
					$this->data['sectionID'] = $sectionID;
					$this->data['studentID'] = $studentID;

					$mArray       = [];
					$queryArray   = [];
					$mArray['schoolyearID']        = $schoolyearID;
					$queryArray['srschoolyearID']  = $schoolyearID;
					if ((int)$classesID > 0) {
						$mArray['classesID']       = $classesID;
						$queryArray['srclassesID'] = $classesID;
					}
					if ((int)$sectionID > 0) {
						$mArray['sectionID']       = $sectionID;
						$queryArray['srsectionID'] = $sectionID;
					}
					if ((int)$studentID > 0) {
						$mArray['studentID']       = $studentID;
						$queryArray['srstudentID'] = $studentID;
					}

					$this->data['classes']  = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
					$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
					$this->data['groups']   = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');

					$students               = $this->studentrelation_m->general_get_order_by_student($queryArray);
					$marks                  = $this->mark_m->student_all_mark_array($mArray);
					$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 1));
					$optionalSubjects       = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 0));

					$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
					$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
					$markpercentagesclassArr = isset($markpercentagesmainArr[$classesID]) ? $markpercentagesmainArr[$classesID] : [];
					$settingExam            = array_keys($markpercentagesclassArr);
					$percentageArr          = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

					$this->data['markpercentagesclassArr'] = $markpercentagesclassArr;
					$this->data['settingmarktypeID']       = $settingmarktypeID;

					$retMark = [];
					if (customCompute($marks)) {
						foreach ($marks as $mark) {
							$retMark[$mark->examID][$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
						}
					}

					$markArray      = [];
					$studentChecker = [];
					$validExam      = [];
					if (customCompute($settingExam)) {
						foreach ($settingExam as $examID) {
							if (customCompute($students)) {
								foreach ($students as $student) {
									$opuniquepercentageArr = [];
									if ($student->sroptionalsubjectID > 0) {
										$opuniquepercentageArr = isset($markpercentagesclassArr[$examID][$student->sroptionalsubjectID]) ? $markpercentagesclassArr[$examID][$student->sroptionalsubjectID] : [];
									}
									$oppercentageMark = 0;
									if (customCompute($mandatorySubjects)) {
										foreach ($mandatorySubjects as $mandatorySubject) {
											$uniquepercentageArr = isset($markpercentagesclassArr[$examID][$mandatorySubject->subjectID]) ? $markpercentagesclassArr[$examID][$mandatorySubject->subjectID] : [];
											$markpercentages     = [];
											if (customCompute($uniquepercentageArr)) {
												$markpercentages = $uniquepercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
											}

											if (customCompute($markpercentages)) {
												foreach ($markpercentages as $markpercentageID) {
													$f = false;
													if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
														$f = true;
													}

													if (isset($retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
														$markArray[$examID][$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
													}



													$f = false;
													if (customCompute($opuniquepercentageArr)) {
														if (isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
															$f = true;
														}
													}
													if (!isset($studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID]) && $f) {
														$oppercentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
														if ($student->sroptionalsubjectID > 0) {

															if (isset($retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
																$markArray[$examID][$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
															}
														}
														$studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID] = TRUE;
													}
												}
											}
										}
									}
								}
							}
						}
					}

					$this->data['percentageArr']     = $percentageArr;
					$this->data['grades']            = $this->grade_m->get_grade();
					$this->data['optionalSubjects']  = pluck($optionalSubjects, 'obj', 'subjectID');
					$this->data['mandatorySubjects'] = $mandatorySubjects;
					$this->data['totalSubject']      = customCompute($mandatorySubjects);
					$this->data['validExams']        = $validExam;
					$this->data['exams']             = pluck($this->exam_m->get_exam(), 'exam', 'examID');;
					$this->data['students']          = $students;
					$this->data['markArray']         = $markArray;
					$this->data['settingExam']       = $settingExam;

					$this->reportSendToMail('progresscardreport.css', $this->data, 'report/progresscard/ProgresscardReportPDF', $to, $subject, $message);
					$retArray['status'] = TRUE;
					echo json_encode($retArray);
					exit;
				}
			} else {
				$retArray['message'] = $this->lang->line('progresscardreport_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('progresscardreport_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function getSection()
	{
		$classesID = $this->input->post('classesID');
		if ((int)$classesID) {
			$sections = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
			echo "<option value='0'>", $this->lang->line("progresscardreport_please_select"), "</option>";
			if (customCompute($sections)) {
				foreach ($sections as $section) {
					echo "<option value=\"$section->sectionID\">" . $section->section . "</option>";
				}
			}
		}
	}

	public function getStudent()
	{
		$classesID = $this->input->post('classesID');
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if ((int)$classesID) {
			$students = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
			if (customCompute($students)) {
				echo "<option value='0'>" . $this->lang->line("progresscardreport_please_select") . "</option>";
				foreach ($students as $student) {
					echo "<option value=\"$student->srstudentID\">" . $student->srname . "</option>";
				}
			}
		}
	}

	public function unique_data($data)
	{
		if ($data != "") {
			if ($data === "0") {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
		}
		return TRUE;
	}
	public function pdf()
	{
		if (permissionChecker('progresscardreport')) {
			$classesID    = htmlentities(escapeString($this->uri->segment(3)));
			$sectionID    = htmlentities(escapeString($this->uri->segment(4)));
			$studentID    = htmlentities(escapeString($this->uri->segment(5)));
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			if ((int)$classesID && ((int)$sectionID || $sectionID >= 0) && ((int)$studentID || $studentID >= 0)) {
				$this->data['classesID'] = $classesID;
				$this->data['sectionID'] = $sectionID;
				$this->data['studentID'] = $studentID;

				$mArray       = [];
				$queryArray   = [];
				$mArray['schoolyearID']        = $schoolyearID;
				$queryArray['srschoolyearID']  = $schoolyearID;
				if ((int)$classesID > 0) {
					$mArray['classesID']       = $classesID;
					$queryArray['srclassesID'] = $classesID;
				}
				if ((int)$sectionID > 0) {
					$mArray['sectionID']       = $sectionID;
					$queryArray['srsectionID'] = $sectionID;
				}
				if ((int)$studentID > 0) {
					$mArray['studentID']       = $studentID;
					$queryArray['srstudentID'] = $studentID;
				}

				$this->data['classes']  = pluck($this->classes_m->general_get_classes(), 'classes', 'classesID');
				$this->data['sections'] = pluck($this->section_m->general_get_section(), 'section', 'sectionID');
				$this->data['groups']   = pluck($this->studentgroup_m->get_studentgroup(), 'group', 'studentgroupID');

				$students               = $this->studentrelation_m->general_get_order_by_student($queryArray);
				$marks                  = $this->mark_m->student_all_mark_array($mArray);
				$mandatorySubjects      = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 1));
				$optionalSubjects       = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID, 'type' => 0));

				$settingmarktypeID      = $this->data['siteinfos']->marktypeID;
				$markpercentagesmainArr = $this->marksetting_m->get_marksetting_markpercentages();
				$markpercentagesclassArr = isset($markpercentagesmainArr[$classesID]) ? $markpercentagesmainArr[$classesID] : [];
				$settingExam            = array_keys($markpercentagesclassArr);
				$percentageArr          = pluck($this->markpercentage_m->get_markpercentage(), 'obj', 'markpercentageID');

				$this->data['markpercentagesclassArr'] = $markpercentagesclassArr;
				$this->data['settingmarktypeID']       = $settingmarktypeID;

				$retMark = [];
				if (customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->examID][$mark->studentID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}

				$markArray      = [];
				$studentChecker = [];
				$validExam      = [];
				if (customCompute($settingExam)) {
					foreach ($settingExam as $examID) {
						if (customCompute($students)) {
							foreach ($students as $student) {
								$opuniquepercentageArr = [];
								if ($student->sroptionalsubjectID > 0) {
									$opuniquepercentageArr = isset($markpercentagesclassArr[$examID][$student->sroptionalsubjectID]) ? $markpercentagesclassArr[$examID][$student->sroptionalsubjectID] : [];
								}
								$oppercentageMark = 0;
								if (customCompute($mandatorySubjects)) {
									foreach ($mandatorySubjects as $mandatorySubject) {
										$uniquepercentageArr = isset($markpercentagesclassArr[$examID][$mandatorySubject->subjectID]) ? $markpercentagesclassArr[$examID][$mandatorySubject->subjectID] : [];
										$markpercentages     = [];
										if (customCompute($uniquepercentageArr)) {
											$markpercentages = $uniquepercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
										}

										if (customCompute($markpercentages)) {
											foreach ($markpercentages as $markpercentageID) {
												$f = false;
												if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
													$f = true;
												}

												if (isset($retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID]) && $f) {
													$markArray[$examID][$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$mandatorySubject->subjectID][$markpercentageID];
												}



												$f = false;
												if (customCompute($opuniquepercentageArr)) {
													if (isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
														$f = true;
													}
												}
												if (!isset($studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID]) && $f) {
													$oppercentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
													if ($student->sroptionalsubjectID > 0) {

														if (isset($retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID])) {
															$markArray[$examID][$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID] = $retMark[$examID][$student->srstudentID][$student->sroptionalsubjectID][$markpercentageID];
														}
													}
													$studentChecker['subject'][$examID][$student->srstudentID][$markpercentageID] = TRUE;
												}
											}
										}
									}
								}
							}
						}
					}
				}

				$this->data['percentageArr']     = $percentageArr;
				$this->data['grades']            = $this->grade_m->get_grade();
				$this->data['optionalSubjects']  = pluck($optionalSubjects, 'obj', 'subjectID');
				$this->data['mandatorySubjects'] = $mandatorySubjects;
				$this->data['totalSubject']      = customCompute($mandatorySubjects);
				$this->data['validExams']        = $validExam;
				$this->data['exams']             = pluck($this->exam_m->get_exam(), 'exam', 'examID');;
				$this->data['students']          = $students;
				$this->data['markArray']         = $markArray;
				$this->data['settingExam']       = $settingExam;

				$this->reportPDF('progresscardreport.css', $this->data, 'report/progresscard/ProgresscardReportPDF');
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "errorpermission";
			$this->load->view('_layout_main', $this->data);
		}
	}
	public function print_preview_all()
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $classID      = htmlentities(escapeString($this->uri->segment(3)));
        $sectionID    = htmlentities(escapeString($this->uri->segment(4)));
        $studentID    = htmlentities(escapeString($this->uri->segment(5)));
        $termID       = htmlentities(escapeString($this->uri->segment(6)));

        if ((int)$classID) {
            
            // Build Query Arrays for Students and Marks
            $queryArray = ['srschoolyearID' => $schoolyearID];
            $mArray     = ['schoolyearID'   => $schoolyearID]; 

            if ((int)$classID > 0) {
                $queryArray['srclassesID'] = $classID;
                $mArray['classesID']       = $classID;
            }
            if ((int)$sectionID > 0) {
                $queryArray['srsectionID'] = $sectionID;
            }
            if ((int)$studentID > 0) {
                $queryArray['srstudentID'] = $studentID;
                $mArray['studentID']       = $studentID;
            }

            // 1. Fetch Students (Required for BOTH Class 9 and other classes)
            $students = $this->studentrelation_m->general_get_order_by_student($queryArray);
            $this->data['students'] = $students;

            // 2. Fetch Individual Student Profiles & Teacher Inputs
            foreach ($students as $std) {
                $student = $this->studentrelation_m->get_single_student(array('srstudentID' => $std->studentID, 'srclassesID' => $classID, 'srschoolyearID' => $schoolyearID));
                if (customCompute($student)) {
                    $this->data['teacher_input'][$std->studentID] = $this->markteacherinput_m->get_single_mark(array('studentID' => $std->studentID));
                    $this->data['student'][$std->studentID]       = $student;
                }
            }

            // 3. Common Data Needed for Both Views
            $classes = $this->classes_m->get_single_classes(array('classesID' => $classID));
            $this->data['classes']       = $classes;
            $this->data['class_numeric'] = pluck($this->classes_m->get_classes(), 'classes', 'classes_numeric');
            $this->data['schoolyear']    = $this->schoolyear_m->get_obj_schoolyear($schoolyearID);
            $this->data['termID']        = $termID;
			$this->data['re_open_date']  = "2026-04-01";
			$this->data['generate_date'] = "2026-03-16";
            $footer = ' ';

            // =========================================================
            // BRANCH 1: CLASS 9 CUSTOM LOGIC (classID == 21)
            // =========================================================
            if ($classID == 21) {
                
                // Fetch from the custom Class 9 table using the filtered $mArray
                $class9_marks = $this->markclass9_m->get_order_by_markclass9($mArray);
                
                $formatted_c9_marks = [];
                if(customCompute($class9_marks)) {
                    foreach($class9_marks as $m) {
                        // Format: array[studentID][examID][subjectID] = rowData
                        $formatted_c9_marks[$m->studentID][$m->examID][$m->subjectID] = $m;
                    }
                }
                $this->data['class9_marks_data'] = $formatted_c9_marks;
                
                $this->data['exams']    = pluck($this->exam_m->get_exam(), 'exam', 'examID');
                $this->data['grades']   = $this->grade_m->get_grade();
                $this->data['subjects'] = pluck($this->subject_m->general_get_order_by_subject(array('classesID' => $classID)), 'obj', 'subjectID');
                
                // Generate PDF for Class 9
                $this->reportPDFWithFooter('markmodule.css', $this->data, 'report/progresscard/class9_report_card', $footer);

            } 
            // =========================================================
            // BRANCH 2: STANDARD LOGIC FOR ALL OTHER CLASSES
            // =========================================================
            else {
                
                $marks          = $this->mark_m->student_all_mark_array($mArray);
                $marks_optional = $this->mark_m->student_all_optional_mark_array($mArray);
                
                $retMark = [];
                if (customCompute($marks)) {
                    foreach ($marks as $mark) {
                        $retMark[$mark->studentID][$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
                    }
                }
                
                $retOptionalMark = [];
                if (customCompute($marks_optional)) {
                    foreach ($marks_optional as $mark) {
                        $retOptionalMark[$mark->studentID][$mark->examID][$mark->subjectID] = $mark->grade;
                    }
                }
                
                $this->data['marks']          = $retMark;
                $this->data['optional_marks'] = $retOptionalMark;

                $this->data['exams']  = pluck($this->exam_m->get_exam(), 'exam', 'examID');
                $this->data['grades'] = $this->grade_m->get_grade();
                
                $this->data['usertype']      = $this->usertype_m->get_single_usertype(array('usertypeID' => 3));
                $this->data['re_open_date']  = "2026-04-01";
				if(in_array($classID, [9, 18, 22, 10, 14])) {
					$this->data['generate_date'] = "2026-03-16";
				} else {
                	$this->data['generate_date'] = "2026-03-18";
				}
                $markpercentages = $this->markpercentage_m->get_markpercentage();
                $this->data['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
                $marksettings = $this->marksetting_m->get_marksetting_markpercentages();
                $this->data['marksettings']      = isset($marksettings[$classID]) ? $marksettings[$classID] : [];
                $this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;

                $subjects = $this->subject_m->general_get_order_by_subject(array('classesID' => $classID));
                $subjectArr         = [];
                $optionalsubjectArr = [];
                if (customCompute($subjects)) {
                    foreach ($subjects as $subject) {
                        if ($subject->type == 0) {
                            $optionalsubjectArr[$subject->subjectID] = $subject->subjectID;
                        }
                        $subjectArr[$subject->subjectID] = $subject;
                    }
                }

                $this->data['subjects']           = $subjectArr;
                $this->data['optionalsubjectArr'] = $optionalsubjectArr;
                
                // Generate PDF for standard classes
                $this->reportPDFWithFooter('markmodule.css', $this->data, 'report/progresscard/print_preview_all', $footer);
            }
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }
}

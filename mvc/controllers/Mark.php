<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mark extends Admin_Controller {
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
	function __construct() {
		parent::__construct();
		$this->load->model("mark_m");
		$this->load->model("markoptional_m");
		
		$this->load->model("grade_m");
		$this->load->model("classes_m");
		$this->load->model("exam_m");
		$this->load->model("subject_m");
		$this->load->model("schoolyear_m");
		$this->load->model("section_m");
		$this->load->model("student_m");
		$this->load->model("markrelation_m");
		$this->load->model("markpercentage_m");
		$this->load->model('studentrelation_m');
		$this->load->model('marksetting_m');
		$this->load->model('markteacherinput_m');
		$this->load->model('markclass9_m');

		
		$language = $this->session->userdata('lang');
		$this->lang->load('mark', $language);
	}

	protected function rules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("mark_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_examID'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("mark_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classesID'
			),
			// array(
			// 	'field' => 'sectionID',
			// 	'label' => $this->lang->line("mark_section"),
			// 	'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_sectionID'
			// ),
			array(
				'field' => 'subjectID',
				'label' => $this->lang->line("mark_subject"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_subjectID'
			)
		);
		return $rules;
	}

	protected function markRules() {
		$rules = array(
			array(
				'field' => 'examID',
				'label' => $this->lang->line("mark_exam"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_examID'
			),
			array(
				'field' => 'classesID',
				'label' => $this->lang->line("mark_classes"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_classesID'
			),
			array(
				'field' => 'subjectID',
				'label' => $this->lang->line("mark_subject"),
				'rules' => 'trim|required|xss_clean|max_length[11]|callback_unique_subjectID'
			),
			array(
				'field' => 'inputs',
				'label' => $this->lang->line("mark_subject"),
				'rules' => 'trim|xss_clean|max_length[11]|callback_unique_inputs'
			)
		);
		return $rules;
	}

	public function send_mail_rules() {
		$rules = array(
			array(
				'field' => 'to',
				'label' => $this->lang->line("mark_to"),
				'rules' => 'trim|required|max_length[60]|valid_email|xss_clean'
			),
			array(
				'field' => 'subject',
				'label' => $this->lang->line("mark_subject"),
				'rules' => 'trim|required|xss_clean'
			),
			array(
				'field' => 'message',
				'label' => $this->lang->line("mark_message"),
				'rules' => 'trim|xss_clean'
			),
			array(
				'field' => 'id',
				'label' => $this->lang->line("mark_studentID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			),
			array(
				'field' => 'set',
				'label' => $this->lang->line("mark_classesID"),
				'rules' => 'trim|required|max_length[10]|xss_clean|callback_unique_data'
			)
		);
		return $rules;
	}

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


		$myProfile = false;
		$schoolyearID = $this->session->userdata('defaultschoolyearID');
		if($this->session->userdata('usertypeID') == 3) {
			$id = $this->data['myclass'];
			if(!permissionChecker('mark_view')) {
				$myProfile = true;
			}
		} else {
			$id = htmlentities(escapeString($this->uri->segment(3)));
		}

		if($this->session->userdata('usertypeID') == 3 && $myProfile) {
			$url = $id;
			$id = $this->session->userdata('loginuserID');
			$this->view($id, $url);
		} else {
			$this->data['set'] = $id;
			$this->data['classes'] = $this->classes_m->get_classes();

			if((int)$id) {
				$fetchClass = pluck($this->data['classes'], 'classesID', 'classesID');
				if(isset($fetchClass[$id])) {
					$this->data['students'] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, 'srschoolyearID' => $schoolyearID));
					if(customCompute($this->data['students'])) {
						$sections = $this->section_m->general_get_order_by_section(array("classesID" => $id));
						$this->data['sections'] = $sections;
						foreach ($sections as $key => $section) {
							$this->data['allsection'][$section->sectionID] = $this->studentrelation_m->get_order_by_student(array('srclassesID' => $id, "srsectionID" => $section->sectionID, 'srschoolyearID' => $schoolyearID));
						}
					} else {
						$this->data['students'] = [];
					}
				} else {
					$this->data['students'] = [];
				}
			} else {
				$this->data['students'] = [];
			}

			$this->data["subview"] = "mark/index";
			$this->load->view('_layout_main', $this->data);
		}
	}

	public function add() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);
	        $this->data['students']           = [];
			$this->data['settingmarktypeID']  = $this->data['siteinfos']->marktypeID;
			$graduateclass                    = $this->data['siteinfos']->ex_class;

	        $this->data['set_exam']    = 0;
	        $this->data['set_classes'] = 0;
	        $this->data['set_subject'] = 0;

	        $this->data['sendExam']    = [];
	        $this->data['sendSubject'] = [];
	        $this->data['sendClasses'] = [];
	        $this->data['exams']       = [];

	        $classesID = $this->input->post("classesID");
	        if((int)$classesID) {
	        	$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID);
	            $this->data['subjects'] = $this->subject_m->get_order_by_subject(array('classesID' => $classesID));
	        } else {
	            $this->data['subjects'] = [];
	        }

	        $this->data['classes']  = $this->classes_m->get_order_by_classes(['classesID !='=> $graduateclass]);

	        if($_POST) {
	            $rules = $this->rules();
	            $this->form_validation->set_rules($rules);
	            if ($this->form_validation->run() == FALSE) {
	                $this->data["subview"] = "mark/add";
	                $this->load->view('_layout_main', $this->data);
	            } else {
	                $examID          = $this->input->post('examID');
	                $classesID       = $this->input->post('classesID');
	                // $sectionID       = $this->input->post('sectionID');
	                $subjectID       = $this->input->post('subjectID');
	                $this->data['set_exam']    = $examID;
			        $this->data['set_classes'] = $classesID;
			        // $this->data['set_section'] = $sectionID;
			        $this->data['set_subject'] = $subjectID;

	                $exam            = $this->exam_m->get_single_exam(array('examID'=> $examID));
	                $subject         = $this->subject_m->get_single_subject(array('subjectID'=> $subjectID));
	                $classes         = $this->classes_m->get_single_classes(array('classesID'=> $classesID));
	                // $section         = $this->section_m->get_single_section(array('sectionID'=> $sectionID));
	                $markpercentages = $this->markpercentage_m->get_markpercentage();
	        		
	        		$markpercentageArr['marktypeID'] = $this->data['siteinfos']->marktypeID;
	        		$markpercentageArr['classesID']  = $classesID;
	        		$markpercentageArr['examID']     = $examID;
	        		$markpercentageArr['subjectID']  = $subjectID;
	        		$markpercentageArr['subject']    = $subject;

	                $this->data['sendExam']     = $exam;
	                $this->data['sendSubject']  = $subject;
	                $this->data['sendClasses']  = $classes;
	                // $this->data['sendSection']  = $section;

	                $schoolyearID       = $this->session->userdata('defaultschoolyearID');
	                $studentArray = [
	                	'srclassesID'   => $classesID,
	                	// 'srsectionID'   => $sectionID,
	                	'srschoolyearID'=> $schoolyearID,
	                ];

	                $students  = [];
	                if(customCompute($subject)) {
	                	if($subject->type == 1) {
			                $students = $this->studentrelation_m->get_order_by_student([
			                    "srclassesID"    	=> $classesID,
			                    'srschoolyearID' 	=> $schoolyearID
			                ]);
	                	} else {
	                		$students = $this->studentrelation_m->get_order_by_student(array(
								"srclassesID" => $classesID,
								'srschoolyearID' => $schoolyearID,
								'sroptionalsubjectID' => $subject->subjectID
							));

							$studentArray['sroptionalsubjectID'] = $subject->subjectID;
	                	}
	                }
					
	                $sendStudent = $this->studentrelation_m->get_order_by_student($studentArray);
					
	                $markPluck   = pluck($this->mark_m->get_order_by_mark(array("examID" => $examID, "classesID" => $classesID, "	subjectID" => $subjectID, 'schoolyearID' => $schoolyearID)), 'obj', 'studentID');

	                $array = [];
	                if(customCompute($students)) {
	                    foreach ($students as $student) {
	                        if(!isset($markPluck[$student->studentID])) {
	                            $array[] = array(
	                                "examID"       => $examID,
									"schoolyearID" => $schoolyearID,
	                                "exam"         => $exam->exam,
	                                "studentID"    => $student->studentID,
	                                "classesID"    => $classesID,
	                                "subjectID"    => $subjectID,
	                                "subject"      => $subject->subject,
	                                "year"         => date('Y'),
	                                "create_date"  => date("Y-m-d H:i:s"),
	                                'create_userID'=> $this->session->userdata("loginuserID"),
	                                'create_usertypeID' => $this->session->userdata('usertypeID')
	                            );
	                        }
	                    }
	                
	                    if(customCompute($array)) {
		                    $count = customCompute($array);

		                    $firstID = $this->mark_m->insert_batch_mark($array);
		                    $lastID = $firstID + ($count-1);

		                    $markRelationArray = []; 
		                    if($lastID >= $firstID) {
		                    	for ($i = $firstID; $i <= $lastID ; $i++) {
		                    		foreach ($markpercentages as $value) {
										$markRelationArray[] = [
											"markID" => $i,
											"markpercentageID" => $value->markpercentageID
										];
									}
		                    	}
		                    }

							if(customCompute($markRelationArray)) {
								$this->markrelation_m->insert_batch_markrelation($markRelationArray);
							}
	                    }

	                    $mark = $this->mark_m->get_order_by_mark(array('schoolyearID' => $schoolyearID, "examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID));
	                    $this->data['marks'] = $mark;
	                }

					if(customCompute($students)) {
						$missingmMarkRelationArray = [];
						$allMarkWithRelation = $this->markrelation_m->get_all_mark_with_relation(array('schoolyearID' => $schoolyearID, 'examID' => $examID, 'classesID' => $classesID, 'subjectID' => $subjectID));


						$studentMarkPercentage = [];
						foreach ($allMarkWithRelation as $key => $value) {
							$studentMarkPercentage[$value->studentID][$value->examID][$value->subjectID]['markpercentage'][] = $value->markpercentageID;
							$studentMarkPercentage[$value->studentID][$value->examID]['markID'][$value->subjectID] = $value->markID;
						}

						$markpercentages = pluck($markpercentages, 'markpercentageID');
						foreach ($students as $student) {
							$studentPercentage = isset($studentMarkPercentage[$student->studentID][$examID][$subjectID]['markpercentage']) ? $studentMarkPercentage[$student->studentID][$examID][$subjectID]['markpercentage'] : [];

							if(customCompute($studentPercentage)) {
								$diffMarkPercentage = array_diff($markpercentages, $studentMarkPercentage[$student->studentID][$examID][$subjectID]['markpercentage']);
								foreach ($diffMarkPercentage as $item) {
									$missingmMarkRelationArray[] = [
										"markID" => $studentMarkPercentage[$student->studentID][$examID]['markID'][$subjectID],
										"markpercentageID" => $item
									];
								}
							}
						}

						if(customCompute($missingmMarkRelationArray)) {
							$this->markrelation_m->insert_batch_markrelation($missingmMarkRelationArray);
						}
					}

					$this->data['students']         = $sendStudent;
					
					$this->data['markpercentages']  = $this->marksetting_m->get_marksetting_markpercentages_add($markpercentageArr);
				//	var_dump($this->data['markpercentages']);die;
					$this->data['markRelations']    = $this->getMarkRelationArray($this->mark_m->student_all_mark_array(array('schoolyearID' => $schoolyearID, 'examID' => $examID, 'classesID' => $classesID, 'subjectID' => $subjectID)));

					$this->data["subview"] = "mark/add";
	                $this->load->view('_layout_main', $this->data);
	            }
	        } else {
	            $this->data["subview"] = "mark/add";
	            $this->load->view('_layout_main', $this->data);
	        }
		} else {
			$this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
		}
	}
	public function add_grade_only() {
		if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
			$this->data['headerassets'] = array(
				'css' => array(
					'assets/select2/css/select2.css',
					'assets/select2/css/select2-bootstrap.css'
				),
				'js' => array(
					'assets/select2/select2.js'
				)
			);
	        $this->data['students']           = [];
			$this->data['settingmarktypeID']  = $this->data['siteinfos']->marktypeID;
			$graduateclass                    = $this->data['siteinfos']->ex_class;

	        $this->data['set_exam']    = 0;
	        $this->data['set_classes'] = 0;
	        $this->data['set_subject'] = 0;

	        $this->data['sendExam']    = [];
	        $this->data['sendSubject'] = [];
	        $this->data['sendClasses'] = [];
	        $this->data['exams']       = [];

	        $classesID = $this->input->post("classesID");
	        if((int)$classesID) {
	        	$this->data['exams']    = $this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID);
	            $this->data['subjects'] = $this->subject_m->get_order_by_subject(array('classesID' => $classesID));
	        } else {
	            $this->data['subjects'] = [];
	        }

	        $this->data['classes']  = $this->classes_m->get_order_by_classes(['classesID !='=> $graduateclass]);

	        if($_POST) {
	            $rules = $this->rules();
	            $this->form_validation->set_rules($rules);
	            if ($this->form_validation->run() == FALSE) {
	                $this->data["subview"] = "mark/add_grade_subject";
	                $this->load->view('_layout_main', $this->data);
	            } else {
	                $examID          = $this->input->post('examID');
	                $classesID       = $this->input->post('classesID');
	                $subjectID       = $this->input->post('subjectID');
	                $this->data['set_exam']    = $examID;
			        $this->data['set_classes'] = $classesID;
			        $this->data['set_subject'] = $subjectID;

	                $exam            = $this->exam_m->get_single_exam(array('examID'=> $examID));
	                $subject         = $this->subject_m->get_single_subject(array('subjectID'=> $subjectID));
	                $classes         = $this->classes_m->get_single_classes(array('classesID'=> $classesID));
	                $markpercentages = $this->markpercentage_m->get_markpercentage();

	        		$markpercentageArr['marktypeID'] = $this->data['siteinfos']->marktypeID;
	        		$markpercentageArr['classesID']  = $classesID;
	        		$markpercentageArr['examID']     = $examID;
	        		$markpercentageArr['subjectID']  = $subjectID;
	        		$markpercentageArr['subject']    = $subject;

	                $this->data['sendExam']     = $exam;
	                $this->data['sendSubject']  = $subject;
	                $this->data['sendClasses']  = $classes;

	                $schoolyearID       = $this->session->userdata('defaultschoolyearID');
	                $studentArray = [
	                	'srclassesID'   => $classesID,
	                	'srschoolyearID'=> $schoolyearID,
	                ];

	                $students  = [];
	                if(customCompute($subject)) {
	                	
			                $students = $this->studentrelation_m->get_order_by_student([
			                    "srclassesID"    	=> $classesID,
			                    'srschoolyearID' 	=> $schoolyearID
			                ]);
	                	
	                }
					
	                $sendStudent = $this->studentrelation_m->get_order_by_student($studentArray);
					
	                $markPluck   = pluck($this->markoptional_m->get_order_by_mark(array("examID" => $examID, "classesID" => $classesID, "	subjectID" => $subjectID, 'schoolyearID' => $schoolyearID)), 'obj', 'studentID');

	                $array = [];
	                if(customCompute($students)) {
	                    foreach ($students as $student) {
	                        if(!isset($markPluck[$student->studentID])) {
	                            $array[] = array(
	                                "examID"       => $examID,
									"schoolyearID" => $schoolyearID,
	                                "exam"         => $exam->exam,
	                                "studentID"    => $student->studentID,
	                                "classesID"    => $classesID,
	                                "subjectID"    => $subjectID,
	                                "subject"      => $subject->subject,
	                                "year"         => date('Y'),
	                                "create_date"  => date("Y-m-d H:i:s"),
	                                'create_userID'=> $this->session->userdata("loginuserID"),
	                                'create_usertypeID' => $this->session->userdata('usertypeID')
	                            );
	                        }
	                    }
	                
	                    if(customCompute($array)) {
		                    $count = customCompute($array);

		                    $firstID = $this->markoptional_m->insert_batch_mark($array);
		                    
	                    }

	                    $mark = $this->markoptional_m->get_order_by_mark(array('schoolyearID' => $schoolyearID, "examID" => $examID, "classesID" => $classesID, "subjectID" => $subjectID));
	                    $this->data['marks'] = $mark;
	                }


					$this->data['students']         = $sendStudent;
					
					
					
					$this->data["subview"] = "mark/add_grade_subject";
	                $this->load->view('_layout_main', $this->data);
	            }
	        } else {
	            $this->data["subview"] = "mark/add_grade_subject";
	            $this->load->view('_layout_main', $this->data);
	        }
		} else {
			$this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
		}
	}

	public function view($studentID = null, $classID = null) {
		$this->data['headerassets'] = array(
			'css' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.css'
			),
			'js' => array(
				'assets/custom-scrollbar/jquery.mCustomScrollbar.concat.min.js'
			)
		);

		if((int) $studentID && (int) $classID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classID, 'srschoolyearID' => $schoolyearID));
			if(customCompute($student)) {
				$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
				if(isset($fetchClass[$classID])) {
					$this->getView($studentID, $classID);
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);	
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}

	private function getMarkRelationArray($arrays=NULL) {
		$mark   = [];
		$markwr = [];
		if(customCompute($arrays)) {
			foreach ($arrays as $array) {
				$mark[$array->studentID][$array->markpercentageID]   = $array->mark;
				$markwr[$array->studentID][$array->markpercentageID] = $array->markrelationID;
			}
		}
		$this->data['markwr'] = $markwr;
		return $mark;
	}

	private function getView($id, $url)
    {
		if((int)$id && (int)$url) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentInfo = $this->studentrelation_m->get_single_student(array('srstudentID' => $id, 'srclassesID' => $url, 'srschoolyearID' => $schoolyearID));

			$this->pluckInfo();
			$this->basicInfo($studentInfo);
			$this->markInfo($studentInfo);

			if(customCompute($studentInfo)) {
				$this->data["subview"] = "mark/view";
				$this->load->view('_layout_main', $this->data);
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		}
	}

	private function pluckInfo() {
		$this->data['subjects'] = pluck($this->subject_m->general_get_subject(), 'subject', 'subjectID');
	}

	private function basicInfo($studentInfo) {
		if(customCompute($studentInfo)) {
			$this->data['profile']  = $studentInfo;
			$this->data['usertype'] = $this->usertype_m->get_single_usertype(array('usertypeID' => $studentInfo->usertypeID));
			$this->data['class']    = $this->classes_m->get_single_classes(array('classesID' => $studentInfo->srclassesID));
			$this->data['section']  = $this->section_m->general_get_single_section(array('sectionID' => $studentInfo->srsectionID));
		} else {
			$this->data['profile'] = [];
		}
	}

	private function markInfo($studentInfo) {
		if(customCompute($studentInfo)) {
			$this->getMark($studentInfo->studentID, $studentInfo->srclassesID);
		} else {
			$this->data['set'] 				= [];
			$this->data["exams"] 			= [];
			$this->data["grades"] 			= [];
			$this->data['markpercentages']	= [];
			$this->data['validExam'] 		= [];
			$this->data['separatedMarks'] 	= [];
			$this->data["highestMarks"] 	= [];
			$this->data["section"] 			= [];
		}
	}

	private function getMark($studentID, $classesID) {
		if((int)$studentID && (int)$classesID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$student      = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
			$classes      = $this->classes_m->get_single_classes(array('classesID' => $classesID));

			if(customCompute($student) && customCompute($classes)) {
				$queryArray = [
					'classesID'    => $student->srclassesID,
					'sectionID'    => $student->srsectionID,
					'studentID'    => $student->srstudentID, 
					'schoolyearID' => $schoolyearID, 
				];

				$exams             = pluck($this->exam_m->get_exam(), 'exam', 'examID');
				$grades            = $this->grade_m->get_grade();
				$marks             = $this->mark_m->student_all_mark_array($queryArray);
				$markpercentages   = $this->markpercentage_m->get_markpercentage();

				$subjects          = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
				$subjectArr        = [];
				$optionalsubjectArr= [];
				if(customCompute($subjects)) {
					foreach ($subjects as $subject) {
						if($subject->type == 0) {
							$optionalsubjectArr[$subject->subjectID] = $subject->subjectID;
						}
						$subjectArr[$subject->subjectID] = $subject;
					}
				}

				$retMark = [];
				if(customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}

				$allStudentMarks = $this->mark_m->student_all_mark_array(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
				$highestMarks    = [];
				foreach ($allStudentMarks as $value) {
					if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
						$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
					}
					$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
				}
				$marksettings  = $this->marksetting_m->get_marksetting_markpercentages();

				$this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
				$this->data['subjects']          = $subjectArr;
				$this->data['exams']             = $exams;
				$this->data['grades']            = $grades;
				$this->data['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
				$this->data['optionalsubjectArr']= $optionalsubjectArr;
				$this->data['marks']             = $retMark;
				$this->data['highestmarks']      = $highestMarks;
				$this->data['marksettings']      = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];
			} else {
				$this->data['settingmarktypeID'] = 0;
				$this->data['subjects']          = [];
				$this->data['exams']             = [];
				$this->data['grades']            = [];
				$this->data['markpercentages']   = [];
				$this->data['optionalsubjectArr']= [];
				$this->data['marks']             = [];
				$this->data['highestmarks']      = [];
				$this->data['marksettings']      = [];
			}
		} else {
			$this->data['settingmarktypeID'] = 0;
			$this->data['subjects']          = [];
			$this->data['exams']             = [];
			$this->data['grades']            = [];
			$this->data['markpercentages']   = [];
			$this->data['optionalsubjectArr']= [];
			$this->data['marks']             = [];
			$this->data['highestmarks']      = [];
			$this->data['marksettings']      = [];
		}
	}
	public function mark_send() {
        $retArray['status'] = FALSE;
        $retArray['message'] = '';

        if($_POST) {
            $rules = $this->markRules();
            $this->form_validation->set_rules($rules);
            if ($this->form_validation->run() == FALSE) {
                $retArray = $this->form_validation->error_array();
                $retArray['status'] = FALSE;
                echo json_encode($retArray);
                exit;
            } else {
                $examID         = $this->input->post("examID");
                $classesID      = $this->input->post("classesID");
                $subjectID      = $this->input->post("subjectID");
                $inputs         = $this->input->post("inputs");
                $schoolyearID   = $this->data['siteinfos']->school_year;

                $markRelationArray = [];
                if(customCompute($inputs)) {
                    foreach ($inputs as $key => $value) {
                        $data = explode('-', $value['mark']);
                        if(!empty($value['value']) || $value['value'] != "") {
                            // Check for AB case-insensitively, otherwise parse as a positive number
                            //$mark_val = strtoupper(trim($value['value'])) === 'AB' ? 'AB' : abs($value['value']);
                            $mark_val = strtoupper(trim($value['value'])) === 'AB' ? 'AB' : (strtoupper(trim($value['value'])) === 'NA' ? 'NA' : abs($value['value']));
                            $markRelationArray[] = [
                                'markrelationID' => $data[1],
                                'mark' => $mark_val
                            ];
                        } else {
                            $markRelationArray[] = [
                                'markrelationID' => $data[1],
                                'mark' => NULL
                            ];
                        }
                    }
                }

                if(customCompute($markRelationArray)) {
                    $this->markrelation_m->update_batch_markrelation($markRelationArray, 'markrelationID');
                }

                $retArray['status'] = TRUE;;
                $retArray['message'] = $this->lang->line('mark_success');
                echo json_encode($retArray);
                exit;
            }
        } else {
            $retArray['message'] = 'Something wrong';
            echo json_encode($retArray);
            exit;
        }
    }
	
	public function mark_send_grade() {
		$retArray['status'] = FALSE;
        $retArray['message'] = '';

        if($_POST) {
	        $rules = $this->markRules();
	        $this->form_validation->set_rules($rules);
	        if ($this->form_validation->run() == FALSE) {
	            $retArray = $this->form_validation->error_array();
	            $retArray['status'] = FALSE;
	            echo json_encode($retArray);
	            exit;
	        } else {
				$examID 		= $this->input->post("examID");
				$classesID		= $this->input->post("classesID");
				$subjectID 		= $this->input->post("subjectID");
				$inputs 		= $this->input->post("inputs");
				$schoolyearID 	= $this->data['siteinfos']->school_year;

				$markRelationArray = [];
				if(customCompute($inputs)) {
					foreach ($inputs as $key => $value) {
						$data = explode('-', $value['mark']);
						if(!empty($value['value']) || $value['value'] != "") {
							$markRelationArray[] = [
								'markID' => $data[1],
								'grade' => $value['value']
							];
						} else {
							$markRelationArray[] = [
								'markID' => $data[1],
								'grade' => NULL
							];
						}
					}
				}
				
				
				if(customCompute($markRelationArray)) {
					$this->markoptional_m->update_batch_markrelation($markRelationArray, 'markID');
				}
				
				$retArray['status'] = TRUE;;
				$retArray['message'] = $this->lang->line('mark_success');
				echo json_encode($retArray);
            	exit;
	        }
	    } else {
			$retArray['message'] = 'Something wrong';
            echo json_encode($retArray);
            exit;
	    }
	}
	public function print_multi_preview($studentID,$classID) {
		if(permissionChecker('mark_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('mark') && ($this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3)))))) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			

			if((int)$studentID && (int)$classID) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classID, 'srschoolyearID' => $schoolyearID));
				if(customCompute($student)) {
					$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
					if(isset($fetchClass[$classID])) {
						$this->getMarkPrintPDFDuplicate($studentID, $classID);
					} else {
						$this->data["subview"] = "error";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	public function print_preview() {
		if(permissionChecker('mark_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('mark') && ($this->session->userdata('loginuserID') == htmlentities(escapeString($this->uri->segment(3)))))) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$studentID 	= htmlentities(escapeString($this->uri->segment(3)));
			$classID 	= htmlentities(escapeString($this->uri->segment(4)));

			if((int)$studentID && (int)$classID) {
				$schoolyearID = $this->session->userdata('defaultschoolyearID');
				$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classID, 'srschoolyearID' => $schoolyearID));
				if(customCompute($student)) {
					$fetchClass = pluck($this->classes_m->get_classes(), 'classesID', 'classesID');
					if(isset($fetchClass[$classID])) {
						$this->getMarkPrintPDF($studentID, $classID);
					} else {
						$this->data["subview"] = "error";
						$this->load->view('_layout_main', $this->data);
					}
				} else {
					$this->data["subview"] = "error";
					$this->load->view('_layout_main', $this->data);
				}
			} else {
				$this->data["subview"] = "error";
				$this->load->view('_layout_main', $this->data);
			}
		} else {
			$this->data["subview"] = "error";
			$this->load->view('_layout_main', $this->data);
		}
	}
	private function getMarkPrintPDFDuplicate($studentID, $classesID) {
		if((int)$studentID && (int)$classesID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$student      = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID));
			$classes      = $this->classes_m->get_single_classes(array('classesID' => $classesID));

			if(customCompute($student) && customCompute($classes)) {
				$queryArray = [
					'classesID'    => $student->srclassesID,
					'sectionID'    => $student->srsectionID,
					'studentID'    => $student->srstudentID, 
					'schoolyearID' => $schoolyearID, 
				];

				$exams             = pluck($this->exam_m->get_exam(), 'exam', 'examID');
				$grades            = $this->grade_m->get_grade();
				$marks             = $this->mark_m->student_all_mark_array($queryArray);
				$marks_optional             = $this->mark_m->student_all_optional_mark_array($queryArray);
				
				$markpercentages   = $this->markpercentage_m->get_markpercentage();

				$subjects          = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
				$subjectArr        = [];
				$optionalsubjectArr= [];
				if(customCompute($subjects)) {
					foreach ($subjects as $subject) {
						if($subject->type == 0) {
							$optionalsubjectArr[$subject->subjectID] = $subject->subjectID;
						}
						$subjectArr[$subject->subjectID] = $subject;
					}
				}

				$retMark = [];
				if(customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}
				$retOptionalMark = [];
				if(customCompute($marks_optional)) {
					foreach ($marks_optional as $mark) {
						$retOptionalMark[$mark->examID][$mark->subjectID] = $mark->grade;
					}
				}
				$usertype        = $this->usertype_m->get_single_usertype(array('usertypeID' => $student->usertypeID));
				$section         = $this->section_m->general_get_single_section(array('sectionID' => $student->srsectionID));

				$allStudentMarks = $this->mark_m->student_all_mark_array(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
				$highestMarks    = [];
				foreach ($allStudentMarks as $value) {
					if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
						$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
					}
					$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
				}
				$marksettings  = $this->marksetting_m->get_marksetting_markpercentages();

				$this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
				$this->data['subjects']          = $subjectArr;
				$this->data['exams']             = $exams;
				$this->data['grades']            = $grades;
				$this->data['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
				$this->data['optionalsubjectArr']= $optionalsubjectArr;
				$this->data['marks']             = $retMark;
				$this->data['optional_marks']             = $retOptionalMark;
				$this->data['highestmarks']      = $highestMarks;
				$this->data['marksettings']      = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];

				$this->data['student']           = $student;
				$this->data['classes']           = $classes;
				$this->data['section']           = $section;
				$this->data['usertype']          = $usertype;
				$this->data['schoolyear']          = $this->schoolyear_m->get_obj_schoolyear($schoolyearID);
				$this->data['re_open_date'] = "2023-04-03";
				$this->data['generate_date'] = "2023-10-01";
				
				$this->reportPDF('markmodule.css',$this->data, 'mark/print_preview');
			} else {
				$this->data['settingmarktypeID'] = 0;
				$this->data['subjects']          = [];
				$this->data['exams']             = [];
				$this->data['grades']            = [];
				$this->data['markpercentages']   = [];
				$this->data['optionalsubjectArr']= [];
				$this->data['marks']             = [];
				$this->data['highestmarks']      = [];
				$this->data['marksettings']      = [];
				
				$this->data['student']           = [];
				$this->data['classes']           = [];
				$this->data['section']           = [];
				$this->data['usertype']          = [];
			}
		} else {
			$this->data['settingmarktypeID'] = 0;
			$this->data['subjects']          = [];
			$this->data['exams']             = [];
			$this->data['grades']            = [];
			$this->data['markpercentages']   = [];
			$this->data['optionalsubjectArr']= [];
			$this->data['marks']             = [];
			$this->data['highestmarks']      = [];
			$this->data['marksettings']      = [];
			
			$this->data['student']           = [];
			$this->data['classes']           = [];
			$this->data['section']           = [];
			$this->data['usertype']          = [];
		}
	}
	private function getMarkPrintPDF($studentID, $classesID) {
		if((int)$studentID && (int)$classesID) {
			$schoolyearID = $this->session->userdata('defaultschoolyearID');
			$student      = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID));
			$classes      = $this->classes_m->get_single_classes(array('classesID' => $classesID));

			if(customCompute($student) && customCompute($classes)) {
				$queryArray = [
					'classesID'    => $student->srclassesID,
					'sectionID'    => $student->srsectionID,
					'studentID'    => $student->srstudentID, 
					'schoolyearID' => $schoolyearID, 
				];

				$exams             = pluck($this->exam_m->get_exam(), 'exam', 'examID');
				$grades            = $this->grade_m->get_grade();
				$marks             = $this->mark_m->student_all_mark_array($queryArray);
				$marks_optional             = $this->mark_m->student_all_optional_mark_array($queryArray);
				
				$markpercentages   = $this->markpercentage_m->get_markpercentage();

				$subjects          = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
				$subjectArr        = [];
				$optionalsubjectArr= [];
				if(customCompute($subjects)) {
					foreach ($subjects as $subject) {
						if($subject->type == 0) {
							$optionalsubjectArr[$subject->subjectID] = $subject->subjectID;
						}
						$subjectArr[$subject->subjectID] = $subject;
					}
				}

				$retMark = [];
				if(customCompute($marks)) {
					foreach ($marks as $mark) {
						$retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
					}
				}
				$retOptionalMark = [];
				if(customCompute($marks_optional)) {
					foreach ($marks_optional as $mark) {
						$retOptionalMark[$mark->examID][$mark->subjectID] = $mark->grade;
					}
				}
				$usertype        = $this->usertype_m->get_single_usertype(array('usertypeID' => $student->usertypeID));
				$section         = $this->section_m->general_get_single_section(array('sectionID' => $student->srsectionID));

				$allStudentMarks = $this->mark_m->student_all_mark_array(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
				$highestMarks    = [];
				foreach ($allStudentMarks as $value) {
					if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
						$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
					}
					$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
				}
				$marksettings  = $this->marksetting_m->get_marksetting_markpercentages();

				$this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
				$this->data['subjects']          = $subjectArr;
				$this->data['exams']             = $exams;
				$this->data['grades']            = $grades;
				$this->data['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
				$this->data['optionalsubjectArr']= $optionalsubjectArr;
				$this->data['marks']             = $retMark;
				$this->data['optional_marks']             = $retOptionalMark;
				$this->data['highestmarks']      = $highestMarks;
				$this->data['marksettings']      = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];

				$this->data['student']           = $student;
				$this->data['classes']           = $classes;
				$this->data['section']           = $section;
				$this->data['usertype']          = $usertype;
				$this->data['schoolyear']          = $this->schoolyear_m->get_obj_schoolyear($schoolyearID);
				$this->data['re_open_date'] = "2024-04-01";
				$this->data['generate_date'] = "2024-03-20";
				$this->data['class_numeric'] = pluck($this->classes_m->get_classes(),'classes', 'classes_numeric');
				$this->data['teacher_input'] = $this->markteacherinput_m->get_single_mark(array('studentID'=>$studentID));
				$this->reportPDF('markmodule.css',$this->data, 'mark/print_preview');
			} else {
				$this->data['settingmarktypeID'] = 0;
				$this->data['subjects']          = [];
				$this->data['exams']             = [];
				$this->data['grades']            = [];
				$this->data['markpercentages']   = [];
				$this->data['optionalsubjectArr']= [];
				$this->data['marks']             = [];
				$this->data['highestmarks']      = [];
				$this->data['marksettings']      = [];
				
				$this->data['student']           = [];
				$this->data['classes']           = [];
				$this->data['section']           = [];
				$this->data['usertype']          = [];
			}
		} else {
			$this->data['settingmarktypeID'] = 0;
			$this->data['subjects']          = [];
			$this->data['exams']             = [];
			$this->data['grades']            = [];
			$this->data['markpercentages']   = [];
			$this->data['optionalsubjectArr']= [];
			$this->data['marks']             = [];
			$this->data['highestmarks']      = [];
			$this->data['marksettings']      = [];
			
			$this->data['student']           = [];
			$this->data['classes']           = [];
			$this->data['section']           = [];
			$this->data['usertype']          = [];
		}
	}

	public function send_mail() {
		$retArray['status'] = FALSE;
		$retArray['message'] = '';
		if(permissionChecker('mark_view') || (($this->session->userdata('usertypeID') == 3) && permissionChecker('mark') && ($this->session->userdata('loginuserID') == $this->input->post('id')))) {
			if($_POST) {
				$rules = $this->send_mail_rules();
				$this->form_validation->set_rules($rules);
				if ($this->form_validation->run() == FALSE) {
					$retArray = $this->form_validation->error_array();
					$retArray['status'] = FALSE;
				    echo json_encode($retArray);
				    exit;
				} else {
					$studentID = $this->input->post('id');
					$classesID = $this->input->post('set');

					if((int)$studentID && (int)$classesID) {
						$schoolyearID = $this->session->userdata('defaultschoolyearID');
						$student = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srclassesID' => $classesID, 'srschoolyearID' => $schoolyearID));
						$classes = $this->classes_m->get_single_classes(array('classesID' => $classesID));
						if(customCompute($student) && customCompute($classes)) {
							$email        = $this->input->post('to');
							$inputsubject = $this->input->post('subject');
							$message      = $this->input->post('message');

							$queryArray = [
								'classesID' => $student->srclassesID,
								'sectionID' => $student->srsectionID,
								'studentID' => $student->srstudentID, 
								'schoolyearID' => $schoolyearID, 
							];

							$exams             = pluck($this->exam_m->get_exam(), 'exam', 'examID');
							$grades            = $this->grade_m->get_grade();
							$marks             = $this->mark_m->student_all_mark_array($queryArray);
							$markpercentages   = $this->markpercentage_m->get_markpercentage();

							$subjects          = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
							$subjectArr        = [];
							$optionalsubjectArr= [];
							if(customCompute($subjects)) {
								foreach ($subjects as $subject) {
									if($subject->type == 0) {
										$optionalsubjectArr[$subject->subjectID] = $subject->subjectID;
									}
									$subjectArr[$subject->subjectID] = $subject;
								}
							}

							$retMark = [];
							if(customCompute($marks)) {
								foreach ($marks as $mark) {
									$retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
								}
							}
							$usertype        = $this->usertype_m->get_single_usertype(array('usertypeID' => $student->usertypeID));
							$section         = $this->section_m->general_get_single_section(array('sectionID' => $student->srsectionID));

							$allStudentMarks = $this->mark_m->student_all_mark_array(array('classesID' => $classesID, 'schoolyearID' => $schoolyearID));
							$highestMarks = [];
							foreach ($allStudentMarks as $value) {
								if(!isset($highestMarks[$value->examID][$value->subjectID][$value->markpercentageID])) {
									$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = -1;
								}
								$highestMarks[$value->examID][$value->subjectID][$value->markpercentageID] = max($value->mark, $highestMarks[$value->examID][$value->subjectID][$value->markpercentageID]);
							}
							$marksettings  = $this->marksetting_m->get_marksetting_markpercentages();

							$this->data['settingmarktypeID'] = $this->data['siteinfos']->marktypeID;
							$this->data['subjects']          = $subjectArr;
							$this->data['exams']             = $exams;
							$this->data['grades']            = $grades;
							$this->data['markpercentages']   = pluck($markpercentages, 'obj', 'markpercentageID');
							$this->data['optionalsubjectArr']= $optionalsubjectArr;
							$this->data['marks']             = $retMark;
							$this->data['highestmarks']      = $highestMarks;
							$this->data['marksettings']      = isset($marksettings[$classesID]) ? $marksettings[$classesID] : [];

							$this->data['student']           = $student;
							$this->data['classes']           = $classes;
							$this->data['section']           = $section;
							$this->data['usertype']          = $usertype;

							$this->reportSendToMail('markmodule.css',$this->data, 'mark/print_preview', $email, $inputsubject, $message);
							$retArray['message'] = "Success";
							$retArray['status'] = TRUE;
							echo json_encode($retArray);
						    exit;
						} else {
							$retArray['message'] = $this->lang->line('mark_data_not_found');
							echo json_encode($retArray);
							exit;
						}
					} else {
						$retArray['message'] = $this->lang->line('mark_data_not_found');
						echo json_encode($retArray);
						exit;
					}
				}
			} else {
				$retArray['message'] = $this->lang->line('mark_permissionmethod');
				echo json_encode($retArray);
				exit;
			}
		} else {
			$retArray['message'] = $this->lang->line('mark_permission');
			echo json_encode($retArray);
			exit;
		}
	}

	public function mark_list() {
		$classID = $this->input->post('id');
		if((int)$classID) {
			$string = base_url("mark/index/$classID");
			echo $string;
		} else {
			redirect(base_url("mark/index"));
		}
	}

	public function examcall() {
		$classesID = $this->input->post('classesID');
		if((int)$classesID) {
			$exams    = pluck($this->marksetting_m->get_exam($this->data['siteinfos']->marktypeID, $classesID), 'obj', 'examID');
			echo "<option value='0'>", $this->lang->line("mark_select_exam"),"</option>";
			if(customCompute($exams)) {
				foreach ($exams as $exam) {
					echo "<option value=".$exam->examID.">".$exam->exam."</option>";
				}
			}
		} else {
			echo "<option value='0'>", $this->lang->line("mark_select_exam"),"</option>";
		}
	}

	public function subjectcall() {
		$id = $this->input->post('id');
		if((int)$id) {
			$allsubject = $this->subject_m->get_order_by_subject(array("classesID" => $id,"type" => 1));
			echo "<option value='0'>", $this->lang->line("mark_select_subject"),"</option>";
			foreach ($allsubject as $value) {
				echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
			}
		} else {
			echo "<option value='0'>", $this->lang->line("mark_select_subject"),"</option>";
		}
	}
	public function subjectcall_optional() {
		$id = $this->input->post('id');
		if((int)$id) {
			$allsubject = $this->subject_m->get_order_by_subject(array("classesID" => $id,"type" => 0));
			echo "<option value='0'>", $this->lang->line("mark_select_subject"),"</option>";
			foreach ($allsubject as $value) {
				echo "<option value=\"$value->subjectID\">",$value->subject,"</option>";
			}
		} else {
			echo "<option value='0'>", $this->lang->line("mark_select_subject"),"</option>";
		}
	}

	public function sectioncall() {
		$id = $this->input->post('id');
		if((int)$id) {
			$allsection = $this->section_m->get_order_by_section(array("classesID" => $id));
			echo "<option value='0'>", $this->lang->line("mark_select_section"),"</option>";
			foreach ($allsection as $value) {
				echo "<option value=\"$value->sectionID\">",$value->section,"</option>";
			}
		} else {
			echo "<option value='0'>", $this->lang->line("mark_select_section"),"</option>";
		}
	}

	public function unique_data($data) {
		if($data != '') {
			if($data == '0') {
				$this->form_validation->set_message('unique_data', 'The %s field is required.');
				return FALSE;
			}
			return TRUE;
		}
		return TRUE;
	}

	public function unique_examID() {
		if($this->input->post('examID') == 0) {
			$this->form_validation->set_message("unique_examID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_classesID() {
		if($this->input->post('classesID') == 0) {
			$this->form_validation->set_message("unique_classesID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_sectionID() {
		if($this->input->post('sectionID') == 0) {
			$this->form_validation->set_message("unique_sectionID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}

	public function unique_subjectID() {
		if($this->input->post('subjectID') == 0) {
			$this->form_validation->set_message("unique_subjectID", "The %s field is required");
	     	return FALSE;
		}
		return TRUE;
	}
	public function unique_inputs() {
        $inputs = $this->input->post('inputs');
        if(customCompute($inputs)) {
            $classesID       = $this->input->post('classesID');
            $examID          = $this->input->post('examID');
            $subjectID       = $this->input->post('subjectID');
            $subject         = $this->subject_m->get_single_subject(array('subjectID'=> $subjectID));

            $markpercentageArr['marktypeID'] = $this->data['siteinfos']->marktypeID;
            $markpercentageArr['classesID']  = $classesID;
            $markpercentageArr['examID']     = $examID;
            $markpercentageArr['subjectID']  = $subjectID;
            $markpercentageArr['subject']    = $subject;

            $getMarkPercentage = $this->marksetting_m->get_marksetting_markpercentages_add($markpercentageArr);
            foreach ($inputs as $value) {
                $markpercentageID = $value['markpercentageid'];
                $markValue        = trim($value['value']);

                if(isset($getMarkPercentage[$markpercentageID])) {
                    if(is_numeric($markValue)) {
                        if(0 > $markValue || $markValue > $getMarkPercentage[$markpercentageID]->percentage) {
                            $this->form_validation->set_message('unique_inputs', 'Mark can not cross max mark');
                            return FALSE;
                        }
                    } else if(strtoupper($markValue) === "AB" || strtoupper($markValue) === "NA") {
                        // Allow 'AB' or 'ab' to pass validation
                    } else {
                        if(is_string($markValue) && $markValue != '') {
                            $this->form_validation->set_message('unique_inputs', 'String data is deniable');
                            return FALSE;
                        }
                    }
                }
            }
        }
        return TRUE;
    }
	
	public function add_class9_marks() {
        // Note: this used to force error_reporting(E_ALL) + display_errors=1 here, which
        // re-enabled PHP 8.2's deprecation-notice flood for this one page (see index.php's
        // ENVIRONMENT switch) — that flood is what broke this page with visible PHP errors.
        if(($this->data['siteinfos']->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) {
            $this->data['headerassets'] = array(
                'css' => array('assets/select2/css/select2.css', 'assets/select2/css/select2-bootstrap.css'),
                'js' => array('assets/select2/select2.js')
            );
            
            // Only fetch Class 9
            $this->data['classes'] = $this->classes_m->get_order_by_classes(['classesID' => 21]);
            
            $classesID = $this->input->post("classesID");
            // $sectionID = $this->input->post("sectionID");
            
            // Hardcode examID to 1 since Class 9 only has one exam format
            $examID = 1; 

            $this->data['set_classes'] = $classesID ? $classesID : 0;
            // $this->data['set_section'] = $sectionID ? $sectionID : 0;

            if((int)$classesID) {
                // $this->data['sections'] = $this->section_m->general_get_order_by_section(array('classesID' => $classesID));
                
                // Fetch ALL students for the selected section
                // if($sectionID) {
                    $this->data['students'] = $this->studentrelation_m->general_get_order_by_student(array('srclassesID' => $classesID, 'srschoolyearID' => $this->session->userdata('defaultschoolyearID')));
                // } else {
                //     $this->data['students'] = [];
                // }
                
                $this->data['subjects'] = $this->subject_m->general_get_order_by_subject(array('classesID' => $classesID));
            } else {
                // $this->data['sections'] = []; 
                $this->data['students'] = []; 
                $this->data['subjects'] = [];
            }

            if($_POST && $this->input->post("save_marks")) {
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $marks_input = $this->input->post("marks");

                if(customCompute($marks_input)) {
                    // Loop through each Student
                    foreach ($marks_input as $studentID => $student_subjects) {
                        // Loop through each Subject for that Student
                        foreach ($student_subjects as $subjectID => $data) {
                            $existingMark = $this->markclass9_m->get_single_markclass9(array(
                                'examID' => $examID, 'classesID' => $classesID, 'subjectID' => $subjectID, 'studentID' => $studentID, 'schoolyearID' => $schoolyearID
                            ));

                            $saveData = array(
                                'pt1' => isset($data['pt1']) && $data['pt1'] !== '' ? $data['pt1'] : 0,
                                'pt2' => isset($data['pt2']) && $data['pt2'] !== '' ? $data['pt2'] : 0,
                                'pt3' => isset($data['pt3']) && $data['pt3'] !== '' ? $data['pt3'] : 0,
                                'notebook' => isset($data['notebook']) && $data['notebook'] !== '' ? $data['notebook'] : 0,
                                'portfolio' => isset($data['portfolio']) && $data['portfolio'] !== '' ? $data['portfolio'] : 0,
                                'enrichment' => isset($data['enrichment']) && $data['enrichment'] !== '' ? $data['enrichment'] : 0,
                                'annual' => isset($data['annual']) && $data['annual'] !== '' ? $data['annual'] : 0,
                                'it_theory' => isset($data['it_theory']) && $data['it_theory'] !== '' ? $data['it_theory'] : 0,
                                'it_practical' => isset($data['it_practical']) && $data['it_practical'] !== '' ? $data['it_practical'] : 0,
                                'co_scholastic_grade' => isset($data['co_scholastic_grade']) ? $data['co_scholastic_grade'] : NULL,
                            );

                            if(customCompute($existingMark)) {
                                $this->markclass9_m->update_markclass9($saveData, $existingMark->markclass9ID);
                            } else {
                                $saveData['schoolyearID'] = $schoolyearID;
                                $saveData['examID'] = $examID;
                                $saveData['classesID'] = $classesID;
                                $saveData['studentID'] = $studentID;
                                $saveData['subjectID'] = $subjectID;
                                $saveData['create_date'] = date("Y-m-d H:i:s");
                                $saveData['create_userID'] = $this->session->userdata("loginuserID");
                                $saveData['create_usertypeID'] = $this->session->userdata('usertypeID');
                                $this->markclass9_m->insert_markclass9($saveData);
                            }
                        }
                    }
                    $this->session->set_flashdata('success', $this->lang->line('menu_success'));
                    redirect(base_url("mark/add_class9_marks"));
                }
            }
            
            // Pre-load all existing marks for the entire section into a 2D array: $existing_marks[studentID][subjectID]
            $this->data['existing_marks'] = [];
            if($classesID) {
                $marks = $this->markclass9_m->get_order_by_markclass9(array('classesID' => $classesID, 'examID' => $examID, 'schoolyearID' => $this->session->userdata('defaultschoolyearID')));
                foreach($marks as $m) {
                    $this->data['existing_marks'][$m->studentID][$m->subjectID] = $m;
                }
            }

            $this->data["subview"] = "mark/add_class9_marks";
            $this->load->view('_layout_main', $this->data);
        } else {
            $this->data["subview"] = "error";
            $this->load->view('_layout_main', $this->data);
        }
    }
}
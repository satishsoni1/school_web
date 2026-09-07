<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

class Leaveapply extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('leaveapplication_m');
        $this->load->model('leavecategory_m');
        $this->load->model('usertype_m');
        $this->load->model('leaveassign_m');
        $this->load->model('studentrelation_m');
        $this->load->model('section_m');
        $this->load->model('systemadmin_m');
        $this->load->model('teacher_m');
    }

    public function index_get()
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $this->retdata['leaveapplications'] = $this->leaveapplication_m->get_order_by_leaveapply_with_user(array('leaveapplications.schoolyearID' => $schoolyearID, 'leaveapplications.create_usertypeID' => $this->session->userdata('usertypeID'), 'leaveapplications.create_userID' => $this->session->userdata('loginuserID')));
        $this->retdata['leavecategorys'] = pluck($this->leavecategory_m->get_leavecategory(), 'leavecategory', 'leavecategoryID');

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    public function view_get($id = null)
    {
        if ((int)$id) {
            $schoolyearID  = $this->session->userdata("defaultschoolyearID");
            $this->retdata['usertypes'] = pluck($this->usertype_m->get_usertype(),'usertype','usertypeID');
            $this->retdata['leaveapply'] = $this->leaveapplication_m->get_single_leaveapplication(array('leaveapplicationID' => $id, 'schoolyearID' => $schoolyearID));

            if(customCompute($this->retdata['leaveapply'])) {
                if(($this->retdata['leaveapply']->create_userID == $this->session->userdata('loginuserID')) && ($this->retdata['leaveapply']->create_usertypeID == $this->session->userdata('usertypeID'))) {

                    $leavecategory = $this->leavecategory_m->get_single_leavecategory(array('leavecategoryID' => $this->retdata['leaveapply']->leavecategoryID));
                    if(customCompute($leavecategory)) {
                        $this->retdata['leaveapply']->category = $leavecategory->leavecategory;
                    } else {
                        $this->retdata['leaveapply']->category = '';
                    }

                    $availableleave = $this->leaveapplication_m->get_sum_of_leave_days_by_user_for_single_category($this->session->userdata('usertypeID'), $this->session->userdata('loginuserID'), $schoolyearID, $this->retdata['leaveapply']->leavecategoryID);
                    if(isset($availableleave->days) && $availableleave->days > 0) {
                        $availableleavedays = $availableleave->days;
                    } else {
                        $availableleavedays = 0;
                    }

                    $leaveassign = $this->leaveassign_m->get_single_leaveassign(array('leavecategoryID' => $this->retdata['leaveapply']->leavecategoryID, 'schoolyearID' => $schoolyearID));
                    if(customCompute($leaveassign)) {
                        $this->retdata['leaveapply']->leaveavabledays = ($leaveassign->leaveassignday - $availableleavedays);
                    } else {
                        $this->retdata['leaveapply']->leaveavabledays = $this->lang->line('leaveapply_deleted');
                    }

                    $this->retdata['applicant']= getObjectByUserTypeIDAndUserID($this->retdata['leaveapply']->create_usertypeID, $this->retdata['leaveapply']->create_userID, $schoolyearID);

                    $this->retdata['daysArray'] = $this->leavedayscustomCompute($this->retdata['leaveapply']->from_date, $this->retdata['leaveapply']->to_date);

                    $this->response([
                        'status'    => true,
                        'message'   => 'Success',
                        'data'      => $this->retdata
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status'    => false,
                        'message'   => 'Error 404',
                        'data'      => []
                    ], REST_Controller::HTTP_OK);
                }
            } else {
                $this->response([
                    'status'    => false,
                    'message'   => 'Error 404',
                    'data'      => []
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status'    => false,
                'message'   => 'Error 404',
                'data'      => []
            ], REST_Controller::HTTP_OK);
        }
    }

    /**
     * Bootstrap data for the mobile "Apply for Leave" form.
     * Returns the leave categories (with days assigned / remaining for the applicant)
     * and, for a parent login, the list of that parent's children to apply on behalf of.
     */
    public function create_get()
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $usertypeID   = $this->session->userdata('usertypeID');

        // Leave in the student/parent portal is always for a student -> usertype 3.
        $categories = $this->leavecategory_m->get_join_leavecategory_and_leaveassign(3, $schoolyearID);

        // For a student login we can compute the exact remaining balance now.
        $usedByCategory = [];
        if ($usertypeID == 3) {
            $usedByCategory = pluck(
                $this->leaveapplication_m->get_sum_of_leave_days_by_user(3, $this->session->userdata('loginuserID'), $schoolyearID),
                'days',
                'leavecategoryID'
            );
        }

        $retCategories = [];
        if (customCompute($categories)) {
            foreach ($categories as $category) {
                $assigned = isset($category->leaveassignday) ? (float)$category->leaveassignday : 0;
                $used     = isset($usedByCategory[$category->leavecategoryID]) ? (float)$usedByCategory[$category->leavecategoryID] : 0;
                $retCategories[] = [
                    'leavecategoryID' => $category->leavecategoryID,
                    'leavecategory'   => $category->leavecategory,
                    'leaveassignday'  => $assigned,
                    'remaining'       => ($usertypeID == 3) ? max(0, $assigned - $used) : $assigned,
                ];
            }
        }
        $this->retdata['leavecategories'] = $retCategories;

        // Parent: list children so the form can pick which student the leave is for.
        $children = [];
        if ($usertypeID == 4) {
            $students = $this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID));
            if (customCompute($students)) {
                foreach ($students as $student) {
                    $children[] = [
                        'studentID'   => $student->studentID,
                        'name'        => $student->name,
                        'srclassesID' => $student->srclassesID,
                        'srsectionID' => $student->srsectionID,
                    ];
                }
            }
        }
        $this->retdata['children'] = $children;

        $this->response([
            'status'  => true,
            'message' => 'Success',
            'data'    => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    /**
     * Create a leave application from the mobile app (student or parent).
     * The approver is auto-routed to the student's section in-charge teacher,
     * falling back to the first system admin. No attachment in this version.
     */
    public function add_post()
    {
        $schoolyearID = $this->session->userdata('defaultschoolyearID');
        $usertypeID   = $this->session->userdata('usertypeID');
        $loginuserID  = $this->session->userdata('loginuserID');

        if ($usertypeID != 3 && $usertypeID != 4) {
            $this->response([
                'status' => false, 'message' => 'Not allowed', 'data' => []
            ], REST_Controller::HTTP_UNAUTHORIZED);
            return;
        }

        $leavecategoryID = (int) inputCall('leavecategoryID');
        $fromDateRaw     = trim((string) inputCall('from_date'));
        $toDateRaw       = trim((string) inputCall('to_date'));
        $reason          = trim((string) inputCall('reason'));
        $postedStudentID = (int) inputCall('studentID');

        // Resolve which student this leave belongs to.
        $studentID = 0;
        if ($usertypeID == 3) {
            $studentID = (int) $loginuserID;
        } else {
            // Parent: the posted studentID must be one of this parent's children.
            $children = pluck($this->studentrelation_m->get_order_by_student(array('srschoolyearID' => $schoolyearID)), 'studentID', 'studentID');
            if ($postedStudentID && isset($children[$postedStudentID])) {
                $studentID = $postedStudentID;
            }
        }

        // ---- validation -------------------------------------------------------
        $validation = [];
        if ($leavecategoryID <= 0) {
            $validation['leavecategoryID'] = 'The leave category field is required.';
        }
        $fromTs = strtotime($fromDateRaw);
        $toTs   = strtotime($toDateRaw);
        if (!$fromDateRaw || $fromTs === false) {
            $validation['from_date'] = 'The from date field is not a valid date.';
        }
        if (!$toDateRaw || $toTs === false) {
            $validation['to_date'] = 'The to date field is not a valid date.';
        }
        if (empty($validation['from_date']) && empty($validation['to_date']) && $toTs < $fromTs) {
            $validation['to_date'] = 'The to date cannot be earlier than the from date.';
        }
        if ($reason === '') {
            $validation['reason'] = 'The reason field is required.';
        } elseif (mb_strlen($reason) > 10000) {
            $validation['reason'] = 'The reason field cannot exceed 10000 characters.';
        }
        if ($usertypeID == 4 && $studentID <= 0) {
            $validation['studentID'] = 'Please select a valid student.';
        }

        if (customCompute($validation)) {
            $this->response([
                'status'  => false,
                'message' => 'Validation Error',
                'data'    => ['validation' => $validation],
            ], REST_Controller::HTTP_NOT_FOUND);
            return;
        }

        $fromDate = date('Y-m-d', $fromTs);
        $toDate   = date('Y-m-d', $toTs);

        $leavedaysCount = $this->leavedayscustomCompute($fromDate, $toDate);
        $leaveDays      = isset($leavedaysCount['totaldayCount']) ? $leavedaysCount['totaldayCount'] : 0;

        $approver = $this->resolveApprover($studentID, $schoolyearID);

        $array = [
            'from_date'                => $fromDate,
            'to_date'                  => $toDate,
            'leave_days'               => $leaveDays,
            'leavecategoryID'          => $leavecategoryID,
            'applicationto_usertypeID' => $approver['usertypeID'],
            'applicationto_userID'     => $approver['userID'],
            'reason'                   => $reason,
            'attachment'              => '',
            'attachmentorginalname'   => '',
            'from_time'               => date('H:i:s'),
            'to_time'                 => date('H:i:s'),
            'create_date'             => date('Y-m-d H:i:s'),
            'modify_date'             => date('Y-m-d H:i:s'),
            'create_userID'           => $studentID,
            'create_usertypeID'       => 3,
            'schoolyearID'            => $schoolyearID,
        ];

        $this->leaveapplication_m->insert_leaveapplication($array);
        $newID = $this->db->insert_id();

        // Notify the approver and the applicant (student + parent).
        try {
            $recipients = $this->notification_lib->studentsToRecipients([$studentID]);
            $recipients[] = ['userID' => $approver['userID'], 'usertypeID' => $approver['usertypeID']];
            $this->notification_lib->notify([
                'title'       => 'Leave Application Submitted',
                'message'     => 'A leave application has been submitted for ' . date('d M Y', $fromTs) . ' - ' . date('d M Y', $toTs) . '.',
                'type'        => 'leaveapplication',
                'referenceID' => $newID,
                'recipients'  => $recipients,
            ]);
        } catch (Exception $e) {
            // notification failure must not fail the submission
        }

        $this->response([
            'status'  => true,
            'message' => 'Success',
            'data'    => $newID
        ], REST_Controller::HTTP_OK);
    }

    /**
     * Pick the person a student's leave application is routed to for approval:
     * the section in-charge teacher, otherwise the first system admin.
     */
    private function resolveApprover($studentID, $schoolyearID)
    {
        $student = $this->studentrelation_m->get_single_student(array('srstudentID' => $studentID, 'srschoolyearID' => $schoolyearID));
        if (customCompute($student) && !empty($student->srsectionID)) {
            $section = $this->section_m->general_get_single_section(array('sectionID' => $student->srsectionID));
            if (customCompute($section) && !empty($section->teacherID)) {
                $teacher = $this->teacher_m->get_single_teacher(array('teacherID' => $section->teacherID));
                if (customCompute($teacher)) {
                    return ['usertypeID' => 2, 'userID' => (int) $section->teacherID];
                }
            }
        }

        $admins = $this->systemadmin_m->get_systemadmin();
        if (customCompute($admins)) {
            return ['usertypeID' => 1, 'userID' => (int) $admins[0]->systemadminID];
        }

        // Last resort: route to admin id 1.
        return ['usertypeID' => 1, 'userID' => 1];
    }

    private function leavedayscustomCompute($fromdate, $todate)
    {
        $allholidayArray    = $this->getHolidaysSession();
        $getweekenddayArray = $this->getWeekendDaysSession();
        $leavedays = get_day_using_two_date(strtotime($fromdate), strtotime($todate));

        $holidayCount    = 0;
        $weekenddayCount = 0;
        $leavedayCount   = 0;
        $totaldayCount   = 0;
        $retArray = [];
        if(customCompute($leavedays)) {
            foreach($leavedays as $leaveday) {
                if(in_array($leaveday, $allholidayArray)) {
                    $holidayCount++;
                } elseif(in_array($leaveday, $getweekenddayArray)) {
                    $weekenddayCount++;
                } else {
                    $leavedayCount++;
                }
                $totaldayCount++;
            }
        }

        $retArray['fromdate']        = $fromdate;
        $retArray['todate']          = $todate;
        $retArray['holidayCount']    = $holidayCount;
        $retArray['weekenddayCount'] = $weekenddayCount;
        $retArray['leavedayCount']   = $leavedayCount;
        $retArray['totaldayCount']   = $totaldayCount;
        return $retArray;
    }
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Admin-only test tool: manually send a push notification to everyone, a whole class,
// or one specific user — so you can verify the OneSignal pipeline (Notification_lib)
// is actually delivering before relying on it from the real activity triggers.
// Reachable at: <site>/sendnotification/index
class Sendnotification extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('systemadmin_m');
        $this->load->model('teacher_m');
        $this->load->model('studentrelation_m');
        $this->load->model('parents_m');
        $this->load->model('user_m');
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

    public function index()
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $this->data['classes'] = $this->classes_m->get_classes();
        $this->data['result'] = null;

        if ($_POST) {
            $target = $this->input->post('target'); // broadcast | all | class | user
            $title = $this->input->post('title') ?: 'Test Notification';
            $message = $this->input->post('message') ?: 'This is a test notification.';

            if ($target === 'broadcast') {
                // Pure connectivity smoke test: every subscribed device, ignores
                // targeting/history entirely. Use this first to confirm the
                // app<->OneSignal<->server wiring works at all.
                $response = $this->notification_lib->sendBroadcastTest($title, $message);
                $this->data['result'] = [
                    'notificationID' => null,
                    'recipientCount' => null, // unknown — OneSignal decides who's "All"
                    'pushResponse' => $response,
                ];
                $this->data['subview'] = 'sendnotification/index';
                $this->load->view('_layout_main', $this->data);
                return;
            }

            $recipients = [];
            $usertypeIDs = null;

            if ($target === 'all') {
                $usertypeIDs = [1, 2, 3, 4];
            } elseif ($target === 'class') {
                $classesID = $this->input->post('classesID');
                $schoolyearID = $this->session->userdata('defaultschoolyearID');
                $students = $this->studentrelation_m->general_get_order_by_student(array(
                    'srclassesID' => $classesID,
                    'srschoolyearID' => $schoolyearID,
                ));
                $studentIDs = pluck($students, 'studentID');
                $recipients = $this->notification_lib->studentsToRecipients($studentIDs);
            } elseif ($target === 'user') {
                $usertypeID = (int) $this->input->post('usertypeID');
                $userID = (int) $this->input->post('userID');
                if ($usertypeID && $userID) {
                    $recipients = [['userID' => $userID, 'usertypeID' => $usertypeID]];
                }
            }

            $options = array(
                'title' => $title ?: 'Test Notification',
                'message' => $message ?: 'This is a test notification.',
                'type' => 'test',
            );
            if ($usertypeIDs) {
                $options['usertypeIDs'] = $usertypeIDs;
            } else {
                $options['recipients'] = $recipients;
            }

            $this->data['result'] = $this->notification_lib->notify($options);
        }

        $this->data['subview'] = 'sendnotification/index';
        $this->load->view('_layout_main', $this->data);
    }

    // AJAX: populate the "specific user" dropdown once a usertype is chosen
    // (mirrors Leaveapply::usercall's cascading-select pattern).
    public function usercall()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $usertypeID = $this->input->post('id');
        $schoolyearID = $this->session->userdata('defaultschoolyearID');

        echo "<option value=''>Select User</option>";
        if ($usertypeID == 1) {
            $users = $this->systemadmin_m->get_systemadmin();
            foreach ((array) $users as $u) {
                echo "<option value=\"$u->systemadminID\">$u->name</option>";
            }
        } elseif ($usertypeID == 2) {
            $users = $this->teacher_m->get_teacher();
            foreach ((array) $users as $u) {
                echo "<option value=\"$u->teacherID\">$u->name</option>";
            }
        } elseif ($usertypeID == 3) {
            $users = $this->studentrelation_m->get_order_by_student(array('schoolyearID' => $schoolyearID));
            foreach ((array) $users as $u) {
                echo "<option value=\"$u->studentID\">$u->name</option>";
            }
        } elseif ($usertypeID == 4) {
            $users = $this->parents_m->get_parents();
            foreach ((array) $users as $u) {
                echo "<option value=\"$u->parentsID\">$u->name</option>";
            }
        } else {
            $users = $this->user_m->get_order_by_user(array('usertypeID' => $usertypeID));
            foreach ((array) $users as $u) {
                echo "<option value=\"$u->userID\">$u->name</option>";
            }
        }
    }
}

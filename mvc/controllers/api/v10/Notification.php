<?php
use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

// Lets the app list the logged-in user's notification history and check for
// unread ones at app load (see notification.service.ts / app.component.ts).
class Notification extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('notification_recipient_m');
    }

    public function index_get()
    {
        $userID = $this->session->userdata('loginuserID');
        $usertypeID = $this->session->userdata('usertypeID');

        $notifications = $this->notification_recipient_m->get_for_user($userID, $usertypeID);
        $unreadCount = $this->notification_recipient_m->get_unread_count($userID, $usertypeID);

        $this->response([
            'status'  => true,
            'message' => 'Success',
            'data'    => [
                'notifications' => $notifications,
                'unreadCount'   => $unreadCount,
            ],
        ], REST_Controller::HTTP_OK);
    }

    public function markread_post($id = null)
    {
        $userID = $this->session->userdata('loginuserID');
        $usertypeID = $this->session->userdata('usertypeID');
        $recipientID = $id ?: inputCall('id');

        if ($recipientID) {
            $this->notification_recipient_m->mark_read($recipientID, $userID, $usertypeID);
        } else {
            $this->notification_recipient_m->mark_all_read($userID, $usertypeID);
        }

        $this->response([
            'status'  => true,
            'message' => 'Success',
            'data'    => [],
        ], REST_Controller::HTTP_OK);
    }
}

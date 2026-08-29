<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Shared notification pipeline used by every module that needs to alert users
 * of a new activity (notice, assignment, attendance, event, holiday, leave
 * status change, exam/HPC results, ...).
 *
 * It does two things for every call:
 *   1. Persists the notification + one row per recipient in
 *      `notifications` / `notification_recipients` so the app can list a
 *      user's notification history and check for unread ones on load
 *      (see api/v10/Notification.php).
 *   2. Pushes it through OneSignal (already wired client-side in
 *      app.component.ts) targeted at the resolved recipients' external_id
 *      (`{usertypeID}_{userID}`, set via OneSignal.login() on the client).
 *
 * Usage:
 *   $this->load->library('notification_lib');
 *   $this->notification_lib->notify([
 *       'title'       => 'New Assignment',
 *       'message'     => $description,           // HTML allowed, cleaned before push
 *       'type'        => 'assignment',            // matches app.component.ts click-router types
 *       'referenceID' => $assignmentID,
 *       'recipients'  => $this->notification_lib->studentsToRecipients($studentIDs), // or build manually
 *   ]);
 */
class Notification_lib
{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->config('onesignal');
        $this->CI->load->database();
    }

    /**
     * @param array $options {
     *   title: string,
     *   message: string,
     *   type: string,
     *   referenceID?: int,
     *   recipients?: array<{userID:int, usertypeID:int}>  explicit recipient list
     *   usertypeIDs?: int[]  when no explicit recipients are given, notify every known
     *                        user of these usertypeIDs (used for school-wide notices)
     * }
     */
    public function notify($options)
    {
        $title = isset($options['title']) ? $options['title'] : '';
        $message = isset($options['message']) ? $options['message'] : '';
        $type = isset($options['type']) ? $options['type'] : 'general';
        $referenceID = isset($options['referenceID']) ? $options['referenceID'] : null;

        $recipients = isset($options['recipients']) && is_array($options['recipients']) ? $options['recipients'] : [];
        if (!customCompute($recipients) && !empty($options['usertypeIDs'])) {
            foreach ($options['usertypeIDs'] as $usertypeID) {
                $recipients = array_merge($recipients, $this->allActiveUserIDs($usertypeID));
            }
        }
        // De-duplicate (a parent/student might otherwise be resolved twice)
        $seen = [];
        $uniqueRecipients = [];
        foreach ($recipients as $r) {
            if (empty($r['userID']) || empty($r['usertypeID'])) {
                continue;
            }
            $key = $r['usertypeID'] . '_' . $r['userID'];
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $uniqueRecipients[] = $r;
            }
        }

        if (!customCompute($uniqueRecipients)) {
            return ['notificationID' => null, 'recipientCount' => 0, 'pushResponse' => null];
        }

        $this->CI->db->insert('notifications', [
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'referenceID' => $referenceID,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        $notificationID = $this->CI->db->insert_id();

        $recipientRows = [];
        $externalIDs = [];
        foreach ($uniqueRecipients as $r) {
            $recipientRows[] = [
                'notificationID' => $notificationID,
                'userID' => $r['userID'],
                'usertypeID' => $r['usertypeID'],
                'is_read' => 0,
            ];
            $externalIDs[] = $r['usertypeID'] . '_' . $r['userID'];
        }
        if (customCompute($recipientRows)) {
            $this->CI->db->insert_batch('notification_recipients', $recipientRows);
        }

        $pushResponse = $this->sendPush($title, $message, $externalIDs, $type, $referenceID);

        return [
            'notificationID' => $notificationID,
            'recipientCount' => count($uniqueRecipients),
            'pushResponse' => $pushResponse,
        ];
    }

    /**
     * Expand a list of studentIDs into recipient pairs for the student themselves
     * and their parent, using the same studentrelation/parents lookup already used
     * by the absent-email/SMS flow in api/v10/Sattendance.php.
     */
    public function studentsToRecipients($studentIDs)
    {
        $recipients = [];
        if (!customCompute($studentIDs)) {
            return $recipients;
        }
        // parentID lives on the `student` table (see Studentrelation_m::userRelation()'s
        // usertypeID==4 branch, which filters on student.parentID) — NOT on `studentrelation`,
        // so this must query student_m directly rather than the bare studentrelation row.
        $this->CI->load->model('student_m');
        foreach ($studentIDs as $studentID) {
            $recipients[] = ['userID' => $studentID, 'usertypeID' => 3];
            $student = $this->CI->student_m->get_single_student(['studentID' => $studentID]);
            if ($student && !empty($student->parentID)) {
                $recipients[] = ['userID' => $student->parentID, 'usertypeID' => 4];
            }
        }
        return $recipients;
    }

    private function allActiveUserIDs($usertypeID)
    {
        $table = null;
        $key = null;
        switch ((int) $usertypeID) {
            case 1: $table = 'systemadmin'; $key = 'systemadminID'; break;
            case 2: $table = 'teacher'; $key = 'teacherID'; break;
            case 3: $table = 'student'; $key = 'studentID'; break;
            case 4: $table = 'parents'; $key = 'parentsID'; break;
        }
        if (!$table) {
            return [];
        }
        $rows = $this->CI->db->select($key)->from($table)->get()->result();
        $out = [];
        foreach ($rows as $row) {
            $out[] = ['userID' => $row->$key, 'usertypeID' => $usertypeID];
        }
        return $out;
    }

    /**
     * Push through OneSignal, targeting specific users via external_id
     * (set client-side via OneSignal.login()) instead of broadcasting to everyone.
     */
    private function sendPush($title, $message, $externalIDs, $type, $referenceID)
    {
        $appID = $this->CI->config->item('onesignal_app_id');
        $apiKey = $this->CI->config->item('onesignal_api_key');
        if (!$appID || !$apiKey || !customCompute($externalIDs)) {
            return null;
        }

        $fields = [
            'app_id' => $appID,
            'headings' => ['en' => $title],
            'contents' => ['en' => $this->cleanMessage($message)],
            'data' => ['type' => $type, 'referenceID' => $referenceID],
            'include_external_user_ids' => array_values(array_unique($externalIDs)),
            'channel_for_external_user_ids' => 'push',
        ];

        return $this->callOneSignal($fields);
    }

    /**
     * Pure connectivity smoke test: pushes to OneSignal's "All" segment (every
     * subscribed device, regardless of external_id/login) so you can confirm the
     * app<->OneSignal<->server wiring works before worrying about per-user targeting.
     * Bypasses notification history entirely — this is diagnostic only.
     */
    public function sendBroadcastTest($title, $message)
    {
        $appID = $this->CI->config->item('onesignal_app_id');
        $apiKey = $this->CI->config->item('onesignal_api_key');
        if (!$appID || !$apiKey) {
            return null;
        }

        $fields = [
            'app_id' => $appID,
            'headings' => ['en' => $title],
            'contents' => ['en' => $this->cleanMessage($message)],
            'included_segments' => ['All'],
        ];

        return $this->callOneSignal($fields);
    }

    private function cleanMessage($message)
    {
        $cleanMessage = strip_tags($message);
        $cleanMessage = html_entity_decode($cleanMessage, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        return trim(preg_replace('/\s+/', ' ', $cleanMessage));
    }

    private function callOneSignal($fields)
    {
        $apiKey = $this->CI->config->item('onesignal_api_key');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            log_message('error', 'OneSignal push failed: ' . curl_error($ch));
        }
        curl_close($ch);

        return $response;
    }
}

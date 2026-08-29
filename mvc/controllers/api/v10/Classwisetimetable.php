<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

class Classwisetimetable extends Api_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * GET /api/v10/classwisetimetable/index/$classesID
     */
    public function index_get($classID = null)
    {
        $loginuserID = $this->session->userdata("loginuserID");
        $usertypeID = $this->session->userdata("usertypeID");

        // Automatically find class ID if not supplied for student logins
        if (empty($classID) && $usertypeID == 3) {
            $student = $this->db->get_where('student', array('studentID' => $loginuserID))->row();
            if (!empty($student)) {
                $classID = isset($student->classesID) ? $student->classesID : 0;
            }
        }

        $classes = $this->db->get('classes')->result_array();

        $timetable = array();
        if (!empty($classID)) {
            $this->db->select('*');
            $this->db->from('classwise_timetable');
            $this->db->where('classesID', $classID);
            //$this->db->order_by('start_time', 'ASC');
            $this->db->order_by("STR_TO_DATE(start_time, '%h:%i %p')", "", false);
            $query = $this->db->get();
            $timetable = $query->result_array();
           // $timetable = $this->fill_missing_breaks($timetable);
        }

        // Map class names from the classes table (falls back to the sheet's own class_name)
        $classMap = array();
        foreach ($classes as $c) {
            $classMap[$c['classesID']] = $c['classes'];
        }

        foreach ($timetable as &$row) {
            $cid = $row['classesID'];
            $row['class_display_name'] = isset($classMap[$cid]) ? $classMap[$cid] : $row['class_name'];
        }

        $this->retdata['classes'] = $classes;
        $this->retdata['timetable'] = $timetable;

        $this->response([
            'status'    => true,
            'message'   => 'Success',
            'data'      => $this->retdata
        ], REST_Controller::HTTP_OK);
    }

    /**
     * ASSEMBLY / LONG BREAK / SHORT BREAK are the same every day.
     * Rather than requiring a BREAK row to be stored per day, use whichever
     * day already has them (e.g. Monday) as the template and clone them onto
     * every other day that has PERIOD rows but is missing that break.
     */
    private function fill_missing_breaks($rows)
    {
        $daysWithPeriods = array();
        $breakTemplates = array(); // sort_order => template row
        $existingBreaks = array(); // "day|sort_order" => true

        foreach ($rows as $row) {
            if ($row['slot_type'] === 'PERIOD') {
                $daysWithPeriods[$row['day']] = true;
            } elseif ($row['slot_type'] === 'BREAK') {
                if (!isset($breakTemplates[$row['sort_order']])) {
                    $breakTemplates[$row['sort_order']] = $row;
                }
                $existingBreaks[$row['day'] . '|' . $row['sort_order']] = true;
            }
        }

        foreach (array_keys($daysWithPeriods) as $day) {
            foreach ($breakTemplates as $sortOrder => $template) {
                $key = $day . '|' . $sortOrder;
                if (!isset($existingBreaks[$key])) {
                    $newRow = $template;
                    $newRow['id'] = null;
                    $newRow['day'] = $day;
                    $rows[] = $newRow;
                }
            }
        }

        usort($rows, function ($a, $b) {
            return $a['sort_order'] <=> $b['sort_order'];
        });

        return $rows;
    }
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin: class-wise list of student and parent login usernames for the default school year,
 * with Print and CSV export. Passwords are listed too (this install stores them as plain text).
 * Sidebar: Report → Username List (db_migration_usernamereport_menu.sql).
 */
class Usernamereport extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->model('schoolyear_m');
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

    /** Filter form + report (?classesID=&status=), or CSV with &export=csv. */
    public function index()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $classes   = $this->classList();
        $classesID = (int) $this->input->get('classesID');
        $status    = (string) $this->input->get('status');
        if (!in_array($status, array('active', 'inactive', 'all'), true)) {
            $status = 'active';
        }
        if ($classesID && !isset($classes[$classesID])) {
            $classesID = 0;
        }

        $generated = $this->input->get('classesID') !== null;
        $rows      = $generated ? $this->rows($classes, $classesID, $status) : array();
        $schoolyear = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID' => $this->session->userdata('defaultschoolyearID')));

        if ($generated && $this->input->get('export') === 'csv') {
            $this->csv($rows, $classes, $schoolyear);
            return;
        }

        // Group by class so each class prints as its own block.
        $grouped = array();
        foreach ($rows as $r) {
            $grouped[(int) $r->srclassesID][] = $r;
        }

        $this->data['classes']    = $classes;
        $this->data['classesID']  = $classesID;
        $this->data['status']     = $status;
        $this->data['generated']  = $generated;
        $this->data['grouped']    = $grouped;
        $this->data['total']      = count($rows);
        $this->data['schoolyear'] = $schoolyear;
        $this->data['subview']    = 'report/username/index';
        $this->load->view('_layout_main', $this->data);
    }

    private function rows($classes, $classesID, $status)
    {
        if (!$classes) {
            return array();
        }
        // Class and roll come from studentrelation, i.e. as they were in the selected school year.
        $this->db->select('studentrelation.srclassesID, studentrelation.srclasses, studentrelation.srroll, studentrelation.srregisterNO,
                           studentrelation.srname, student.studentID, student.username, student.password, student.active,
                           parents.name AS parent_name, parents.username AS parent_username, parents.password AS parent_password, parents.phone AS parent_phone');
        $this->db->from('studentrelation');
        $this->db->join('student', 'student.studentID = studentrelation.srstudentID');
        $this->db->join('parents', 'parents.parentsID = student.parentID', 'LEFT');
        $this->db->where('studentrelation.srschoolyearID', (int) $this->session->userdata('defaultschoolyearID'));
        if ($classesID) {
            $this->db->where('studentrelation.srclassesID', $classesID);
        } else {
            $this->db->where_in('studentrelation.srclassesID', array_keys($classes));
        }
        if ($status === 'active') {
            $this->db->where('student.active', 1);
        } elseif ($status === 'inactive') {
            $this->db->where('student.active', 0);
        }
        // Unescaped: "srroll + 0" sorts roll numbers numerically, and CI would quote the 0 as a column.
        $this->db->order_by('studentrelation.srroll + 0 ASC, studentrelation.srname ASC', '', FALSE);
        $rows = $this->db->get()->result();

        // Keep the classes' own order (classList) rather than classesID order.
        $order = array_flip(array_keys($classes));
        usort($rows, function ($a, $b) use ($order) {
            return $order[(int) $a->srclassesID] - $order[(int) $b->srclassesID];
        });
        return $rows;
    }

    private function csv($rows, $classes, $schoolyear)
    {
        $name = 'username-list' . ($schoolyear ? '-' . preg_replace('/[^A-Za-z0-9]+/', '-', $schoolyear->schoolyear) : '') . '.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $name . '"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel shows names correctly
        fputcsv($out, array('Class', 'Roll', 'Register No', 'Student Name', 'Student Username', 'Student Password', 'Status', 'Parent Name', 'Parent Username', 'Parent Password', 'Parent Phone'));
        foreach ($rows as $r) {
            fputcsv($out, array(
                self::className($r, $classes),
                $r->srroll,
                $r->srregisterNO,
                $r->srname,
                $r->username,
                $r->password,
                $r->active ? 'Active' : 'Inactive',
                $r->parent_name,
                $r->parent_username,
                $r->parent_password,
                $r->parent_phone,
            ));
        }
        fclose($out);
        exit;
    }

    /** Class name stored on the studentrelation row for that year; master name if it's blank. */
    public static function className($row, $classes)
    {
        if ((string) $row->srclasses !== '') {
            return $row->srclasses;
        }
        return isset($classes[$row->srclassesID]) ? $classes[$row->srclassesID]->classes : '';
    }

    /** Classes keyed by classesID, without the "REMOVED" holding class. */
    private function classList()
    {
        $classes = array();
        foreach ((array) $this->classes_m->general_get_classes() as $c) {
            if (strtoupper($c->classes) !== 'REMOVED') {
                $classes[(int) $c->classesID] = $c;
            }
        }
        return $classes;
    }
}

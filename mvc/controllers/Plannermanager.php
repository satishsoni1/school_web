<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// Admin-only manager for the Academic Planner (academic_planner) and the
// Periodic Test schedule + syllabus (periodic_test_schedule / periodic_test_syllabus).
// The mobile app reads these same tables live via api/v10/academicplanner and
// api/v10/periodictest, so anything saved here shows up for students on their next
// pull-to-refresh / login.
// Reachable at: <site>/plannermanager/index
class Plannermanager extends Admin_Controller
{
    private $plannerTypes = array('holiday', 'activity', 'sports', 'exam', 'test', 'event');

    function __construct()
    {
        parent::__construct();
        $this->load->model('classes_m');
        $this->load->database();
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

    private function classList()
    {
        return $this->classes_m->get_classes();
    }

    public function index()
    {
        if (!$this->requireAdmin()) {
            return;
        }

        $this->db->order_by('event_date', 'ASC');
        $this->data['planner_events'] = $this->db->get('academic_planner')->result();

        $classes = $this->classList();
        $classMap = array();
        foreach ($classes as $c) {
            $classMap[$c->classesID] = $c->classes;
        }

        $this->db->order_by('test_date', 'ASC');
        $schedules = $this->db->get('periodic_test_schedule')->result();
        foreach ($schedules as &$s) {
            $s->class_name = isset($classMap[$s->classesID]) ? $classMap[$s->classesID] : ('Grade ' . $s->classesID);
        }
        unset($s);
        $this->data['test_schedules'] = $schedules;

        $syllabus = $this->db->get('periodic_test_syllabus')->result();
        foreach ($syllabus as &$sy) {
            $sy->class_name = isset($classMap[$sy->classesID]) ? $classMap[$sy->classesID] : ('Grade ' . $sy->classesID);
        }
        unset($sy);
        $this->data['test_syllabus'] = $syllabus;

        $this->data['classes'] = $classes;
        $this->data['plannerTypes'] = $this->plannerTypes;
        $this->data['subview'] = 'plannermanager/index';
        $this->load->view('_layout_main', $this->data);
    }

    // ---- Academic Planner -------------------------------------------------

    public function planner_add()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $eventDate = $this->input->post('event_date');
        $title = trim((string) $this->input->post('title'));
        $type = $this->input->post('type');
        $description = trim((string) $this->input->post('description'));

        if ($this->validPlanner($eventDate, $title, $type)) {
            $this->db->insert('academic_planner', array(
                'event_date'  => date('Y-m-d', strtotime($eventDate)),
                'title'       => $title,
                'type'        => $type,
                'description' => $description,
            ));
            $this->session->set_flashdata('success', 'Planner event added.');
        } else {
            $this->session->set_flashdata('error', 'Please provide a valid date, title and type.');
        }
        redirect(base_url('plannermanager/index'));
    }

    public function planner_edit($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $id = (int) $id;
        $row = $this->db->get_where('academic_planner', array('id' => $id))->row();
        if (!customCompute($row)) {
            $this->data['subview'] = 'error';
            $this->load->view('_layout_main', $this->data);
            return;
        }

        if ($this->input->post()) {
            $eventDate = $this->input->post('event_date');
            $title = trim((string) $this->input->post('title'));
            $type = $this->input->post('type');
            $description = trim((string) $this->input->post('description'));
            if ($this->validPlanner($eventDate, $title, $type)) {
                $this->db->where('id', $id)->update('academic_planner', array(
                    'event_date'  => date('Y-m-d', strtotime($eventDate)),
                    'title'       => $title,
                    'type'        => $type,
                    'description' => $description,
                ));
                $this->session->set_flashdata('success', 'Planner event updated.');
                redirect(base_url('plannermanager/index'));
                return;
            }
            $this->session->set_flashdata('error', 'Please provide a valid date, title and type.');
        }

        $this->data['event'] = $row;
        $this->data['plannerTypes'] = $this->plannerTypes;
        $this->data['subview'] = 'plannermanager/planner_edit';
        $this->load->view('_layout_main', $this->data);
    }

    public function planner_delete($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $this->db->where('id', (int) $id)->delete('academic_planner');
        $this->session->set_flashdata('success', 'Planner event deleted.');
        redirect(base_url('plannermanager/index'));
    }

    private function validPlanner($eventDate, $title, $type)
    {
        return $eventDate && strtotime($eventDate) && $title !== '' && in_array($type, $this->plannerTypes, true);
    }

    // ---- Periodic Test schedule ----------------------------------------------

    public function test_add()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $testDate = $this->input->post('test_date');
        $classesID = (int) $this->input->post('classesID');
        $subject = trim((string) $this->input->post('subject'));

        if ($testDate && strtotime($testDate) && $classesID > 0 && $subject !== '') {
            $this->db->insert('periodic_test_schedule', array(
                'test_date' => date('Y-m-d', strtotime($testDate)),
                'day'       => strtoupper(date('l', strtotime($testDate))),
                'classesID' => $classesID,
                'subject'   => $subject,
            ));
            $this->session->set_flashdata('success', 'Test schedule row added.');
        } else {
            $this->session->set_flashdata('error', 'Please provide a valid date, class and subject.');
        }
        redirect(base_url('plannermanager/index'));
    }

    public function test_edit($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $id = (int) $id;
        $row = $this->db->get_where('periodic_test_schedule', array('id' => $id))->row();
        if (!customCompute($row)) {
            $this->data['subview'] = 'error';
            $this->load->view('_layout_main', $this->data);
            return;
        }

        if ($this->input->post()) {
            $testDate = $this->input->post('test_date');
            $classesID = (int) $this->input->post('classesID');
            $subject = trim((string) $this->input->post('subject'));
            if ($testDate && strtotime($testDate) && $classesID > 0 && $subject !== '') {
                $this->db->where('id', $id)->update('periodic_test_schedule', array(
                    'test_date' => date('Y-m-d', strtotime($testDate)),
                    'day'       => strtoupper(date('l', strtotime($testDate))),
                    'classesID' => $classesID,
                    'subject'   => $subject,
                ));
                $this->session->set_flashdata('success', 'Test schedule row updated.');
                redirect(base_url('plannermanager/index'));
                return;
            }
            $this->session->set_flashdata('error', 'Please provide a valid date, class and subject.');
        }

        $this->data['schedule'] = $row;
        $this->data['classes'] = $this->classList();
        $this->data['subview'] = 'plannermanager/test_edit';
        $this->load->view('_layout_main', $this->data);
    }

    public function test_delete($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $this->db->where('id', (int) $id)->delete('periodic_test_schedule');
        $this->session->set_flashdata('success', 'Test schedule row deleted.');
        redirect(base_url('plannermanager/index'));
    }

    // ---- Periodic Test syllabus --------------------------------------------

    public function syllabus_add()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $classesID = (int) $this->input->post('classesID');
        $subject = trim((string) $this->input->post('subject'));
        $syllabus = trim((string) $this->input->post('syllabus'));

        if ($classesID > 0 && $subject !== '' && $syllabus !== '') {
            $this->db->insert('periodic_test_syllabus', array(
                'classesID' => $classesID,
                'subject'   => $subject,
                'syllabus'  => $syllabus,
            ));
            $this->session->set_flashdata('success', 'Syllabus row added.');
        } else {
            $this->session->set_flashdata('error', 'Please provide a class, subject and syllabus text.');
        }
        redirect(base_url('plannermanager/index'));
    }

    public function syllabus_edit($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $id = (int) $id;
        $row = $this->db->get_where('periodic_test_syllabus', array('id' => $id))->row();
        if (!customCompute($row)) {
            $this->data['subview'] = 'error';
            $this->load->view('_layout_main', $this->data);
            return;
        }

        if ($this->input->post()) {
            $classesID = (int) $this->input->post('classesID');
            $subject = trim((string) $this->input->post('subject'));
            $syllabus = trim((string) $this->input->post('syllabus'));
            if ($classesID > 0 && $subject !== '' && $syllabus !== '') {
                $this->db->where('id', $id)->update('periodic_test_syllabus', array(
                    'classesID' => $classesID,
                    'subject'   => $subject,
                    'syllabus'  => $syllabus,
                ));
                $this->session->set_flashdata('success', 'Syllabus row updated.');
                redirect(base_url('plannermanager/index'));
                return;
            }
            $this->session->set_flashdata('error', 'Please provide a class, subject and syllabus text.');
        }

        $this->data['syllabus'] = $row;
        $this->data['classes'] = $this->classList();
        $this->data['subview'] = 'plannermanager/syllabus_edit';
        $this->load->view('_layout_main', $this->data);
    }

    public function syllabus_delete($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $this->db->where('id', (int) $id)->delete('periodic_test_syllabus');
        $this->session->set_flashdata('success', 'Syllabus row deleted.');
        redirect(base_url('plannermanager/index'));
    }
}

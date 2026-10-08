<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin: Academic Planner events (academic_planner). Two planners — Grades 1-10 and Pre-Primary.
 * The mobile app reads the same table (api/v10/academicplanner), so changes show on next refresh.
 * Sidebar: Academic → Academic Planner (db_migration_planner_exam_menus.sql).
 */
class Academicplanner extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('academic_planner_m');
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
        $audience = (string) $this->input->get('planner');
        $type     = (string) $this->input->get('type');
        $month    = (string) $this->input->get('month');
        $audience = isset(Academic_planner_m::$audiences[$audience]) ? $audience : '';
        $type     = in_array($type, Academic_planner_m::$types, true) ? $type : '';

        $this->data['events']      = $this->academic_planner_m->get_events_filtered($audience, $type, $month);
        $this->data['hasAudience'] = $this->academic_planner_m->has_audience();
        $this->data['audiences']   = Academic_planner_m::$audiences;
        $this->data['types']       = Academic_planner_m::$types;
        $this->data['filter']      = array('planner' => $audience, 'type' => $type, 'month' => $month);
        $this->data['subview']     = 'academicplanner/index';
        $this->load->view('_layout_main', $this->data);
    }

    public function add()
    {
        $this->form(null);
    }

    public function edit($id = 0)
    {
        $event = $this->academic_planner_m->get_event($id);
        if (!$event) {
            $this->session->set_flashdata('error', 'Event not found.');
            redirect(base_url('academicplanner/index'));
            return;
        }
        $this->form($event);
    }

    public function delete($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $this->academic_planner_m->delete_event($id);
        $this->session->set_flashdata('success', 'Event deleted.');
        redirect($this->listUrl());
    }

    private function form($event)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        if ($this->input->method() === 'post') {
            $date        = (string) $this->input->post('event_date');
            $title       = trim((string) $this->input->post('title'));
            $type        = (string) $this->input->post('type');
            $description = trim((string) $this->input->post('description'));
            $audience    = (string) $this->input->post('audience');

            if ($date && strtotime($date) && $title !== '' && in_array($type, Academic_planner_m::$types, true)) {
                $this->academic_planner_m->save_event(array(
                    'event_date'  => date('Y-m-d', strtotime($date)),
                    'title'       => $title,
                    'type'        => $type,
                    'description' => $description,
                ), $audience, $event ? $event->id : null);
                $this->session->set_flashdata('success', $event ? 'Event updated.' : 'Event added.');
                redirect($this->listUrl());
                return;
            }
            $this->session->set_flashdata('error', 'Please enter a valid date, title and type.');
        }

        $this->data['event']       = $event;
        $this->data['hasAudience'] = $this->academic_planner_m->has_audience();
        $this->data['audiences']   = Academic_planner_m::$audiences;
        $this->data['types']       = Academic_planner_m::$types;
        $this->data['subview']     = 'academicplanner/form';
        $this->load->view('_layout_main', $this->data);
    }

    /** Back to the list with the filters the admin came from. */
    private function listUrl()
    {
        $back = (string) $this->input->post_get('back');
        return ($back !== '' && strpos($back, base_url('academicplanner')) === 0) ? $back : base_url('academicplanner/index');
    }
}

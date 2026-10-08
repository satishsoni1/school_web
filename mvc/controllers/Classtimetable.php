<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin: daily class timetable (classwise_timetable) — the "Timetable" page in the mobile app.
 * Works for every class, incl. Nursery / Prep. Sidebar: Academic → Class Timetable
 * (db_migration_class_timetable_menu.sql).
 */
class Classtimetable extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('classtimetable_m');
        $this->load->model('classes_m');
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

    /** Class picker, or the selected class's week (?classesID=). */
    public function index()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $classes   = $this->classList();
        $classesID = (int) $this->input->get('classesID');
        $class     = isset($classes[$classesID]) ? $classes[$classesID] : null;

        $this->data['classes'] = $classes;
        $this->data['counts']  = $this->classtimetable_m->counts_by_class();
        $this->data['class']   = $class;
        $this->data['week']    = $class ? $this->classtimetable_m->get_week($classesID) : array();
        $this->data['days']    = Classtimetable_m::$days;
        $this->data['subview'] = 'classtimetable/index';
        $this->load->view('_layout_main', $this->data);
    }

    public function add()
    {
        $this->form(null);
    }

    public function edit($id = 0)
    {
        $slot = $this->classtimetable_m->get_slot($id);
        if (!$slot) {
            $this->session->set_flashdata('error', 'Timetable slot not found.');
            redirect(base_url('classtimetable/index'));
            return;
        }
        $this->form($slot);
    }

    public function delete($id = 0)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $slot = $this->classtimetable_m->delete_slot($id);
        $this->session->set_flashdata('success', 'Slot deleted.');
        redirect($this->weekUrl($slot ? $slot->classesID : 0, $slot ? $slot->day : ''));
    }

    /** POST: copy one day's slots onto other days of the same class (replaces those days). */
    public function copy_day()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $classesID = (int) $this->input->post('classesID');
        $fromDay   = (string) $this->input->post('from_day');
        $toDays    = array_values(array_intersect((array) $this->input->post('to_days'), Classtimetable_m::$days));
        if ($classesID && in_array($fromDay, Classtimetable_m::$days, true) && $toDays) {
            $n = $this->classtimetable_m->copy_day($classesID, $fromDay, $toDays);
            $this->session->set_flashdata('success', ucfirst(strtolower($fromDay)) . "'s $n slots copied to " . implode(', ', array_map('ucfirst', array_map('strtolower', $toDays))) . '.');
        } else {
            $this->session->set_flashdata('error', 'Choose the day to copy and at least one day to copy it to.');
        }
        redirect($this->weekUrl($classesID, $fromDay));
    }

    /** POST: copy this class's whole timetable to another class (replaces that class's timetable). */
    public function copy_class()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $classes = $this->classList();
        $from    = (int) $this->input->post('classesID');
        $to      = (int) $this->input->post('to_classesID');
        if (isset($classes[$from], $classes[$to]) && $from !== $to) {
            $n = $this->classtimetable_m->copy_class($from, $to, $classes[$to]->classes);
            $this->session->set_flashdata('success', "$n slots copied to {$classes[$to]->classes}.");
            redirect($this->weekUrl($to, ''));
            return;
        }
        $this->session->set_flashdata('error', 'Choose a different class to copy to.');
        redirect($this->weekUrl($from, ''));
    }

    /** POST: remove every slot of one day for a class. */
    public function clear_day()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $classesID = (int) $this->input->post('classesID');
        $day       = (string) $this->input->post('day');
        if ($classesID && in_array($day, Classtimetable_m::$days, true)) {
            $n = $this->classtimetable_m->clear_day($classesID, $day);
            $this->session->set_flashdata('success', ucfirst(strtolower($day)) . " cleared ($n slots).");
        }
        redirect($this->weekUrl($classesID, $day));
    }

    private function form($slot)
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $classes = $this->classList();

        if ($this->input->method() === 'post') {
            $classesID = (int) $this->input->post('classesID');
            $day       = (string) $this->input->post('day');
            $slotType  = (string) $this->input->post('slot_type');
            $start     = Classtimetable_m::to_ampm((string) $this->input->post('start_time'));
            $end       = Classtimetable_m::to_ampm((string) $this->input->post('end_time'));
            $subject   = strtoupper(trim((string) $this->input->post('subject')));

            $valid = isset($classes[$classesID]) && in_array($day, Classtimetable_m::$days, true)
                && isset(Classtimetable_m::$slotTypes[$slotType]) && $start && $end && $subject !== ''
                && strtotime('2000-01-01 ' . $end) > strtotime('2000-01-01 ' . $start);
            if ($valid) {
                $this->classtimetable_m->save_slot($classesID, $classes[$classesID]->classes, $day, $slotType, $start, $end, $subject, $slot ? $slot->id : null);
                $this->session->set_flashdata('success', $slot ? 'Slot updated.' : 'Slot added.');
                if ($this->input->post('save_and_new')) {
                    // Next slot usually starts when this one ends.
                    redirect(base_url('classtimetable/add?classesID=' . $classesID . '&day=' . $day . '&start=' . urlencode(Classtimetable_m::to_24h($end))));
                    return;
                }
                redirect($this->weekUrl($classesID, $day));
                return;
            }
            $this->session->set_flashdata('error', 'Please fill class, day, type, subject, and an end time after the start time.');
        }

        $this->data['slot']      = $slot;
        $this->data['classes']   = $classes;
        $this->data['days']      = Classtimetable_m::$days;
        $this->data['slotTypes'] = Classtimetable_m::$slotTypes;
        $this->data['subview']   = 'classtimetable/form';
        $this->load->view('_layout_main', $this->data);
    }

    /** Classes keyed by classesID, without the "REMOVED" holding class. */
    private function classList()
    {
        $classes = array();
        foreach ($this->classes_m->general_get_classes() as $c) {
            if (strtoupper($c->classes) !== 'REMOVED') {
                $classes[(int) $c->classesID] = $c;
            }
        }
        return $classes;
    }

    private function weekUrl($classesID, $day)
    {
        return base_url('classtimetable/index?classesID=' . (int) $classesID) . ($day ? '#day-' . strtolower($day) : '');
    }
}

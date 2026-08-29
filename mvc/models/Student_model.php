<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Student_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    // ── Students ─────────────────────────────────────────────────────────────

    public function get_all_students() {
        return $this->db->order_by('student_name')->get('holistic_students')->result_array();
    }

    public function get_student($id) {
        return $this->db->where('id', $id)->get('holistic_students')->row_array();
    }

    public function insert_student($data) {
        $this->db->insert('holistic_students', $data);
        return $this->db->insert_id();
    }

    public function update_student($id, $data) {
        $this->db->where('id', $id)->update('holistic_students', $data);
        return $this->db->affected_rows();
    }

    public function delete_student($id) {
        return $this->db->where('id', $id)->delete('holistic_students');
    }

    // ── Attendance ────────────────────────────────────────────────────────────

    public function get_attendance($student_id) {
        return $this->db->where('student_id', $student_id)->get('holistic_attendance')->result_array();
    }

    public function save_attendance($student_id, $records) {
        // Delete existing and reinsert
        $this->db->where('student_id', $student_id)->delete('holistic_attendance');
        foreach ($records as $r) {
            $r['student_id'] = $student_id;
            $this->db->insert('holistic_attendance', $r);
        }
    }

    // ── Self Assessment ───────────────────────────────────────────────────────

    public function get_self_assessment($student_id) {
        return $this->db->where('student_id', $student_id)->get('holistic_self_assessment')->result_array();
    }

    public function save_self_assessment($student_id, $data) {
        foreach ($data as $term => $values) {
            $existing = $this->db->where(['student_id' => $student_id, 'term' => $term])->get('holistic_self_assessment')->row_array();
            $values['student_id'] = $student_id;
            $values['term'] = $term;
            if ($existing) {
                $this->db->where(['student_id' => $student_id, 'term' => $term])->update('holistic_self_assessment', $values);
            } else {
                $this->db->insert('holistic_self_assessment', $values);
            }
        }
    }

    // ── Peer Assessment ───────────────────────────────────────────────────────

    public function get_peer_assessment($student_id) {
        return $this->db->where('student_id', $student_id)->get('holistic_peer_assessment')->result_array();
    }

    public function save_peer_assessment($student_id, $data) {
        foreach ($data as $term => $values) {
            $existing = $this->db->where(['student_id' => $student_id, 'term' => $term])->get('holistic_peer_assessment')->row_array();
            $values['student_id'] = $student_id;
            $values['term'] = $term;
            if ($existing) {
                $this->db->where(['student_id' => $student_id, 'term' => $term])->update('holistic_peer_assessment', $values);
            } else {
                $this->db->insert('holistic_peer_assessment', $values);
            }
        }
    }

    // ── Competency Grades ─────────────────────────────────────────────────────

    public function get_competency_grades($student_id) {
        $rows = $this->db->where('student_id', $student_id)->get('holistic_competency_grades')->result_array();
        $grades = [];
        foreach ($rows as $r) {
            $grades[$r['term']][$r['cg_code']][$r['indicator_key']] = $r['grade'];
        }
        return $grades;
    }

    public function save_competency_grades($student_id, $grades) {
        // $grades: [term][cg_code][indicator_key] = grade
        foreach ($grades as $term => $cgs) {
            foreach ($cgs as $cg_code => $indicators) {
                foreach ($indicators as $key => $grade) {
                    $this->db->replace('holistic_competency_grades', [
                        'student_id'    => $student_id,
                        'term'          => $term,
                        'cg_code'       => $cg_code,
                        'indicator_key' => $key,
                        'grade'         => $grade
                    ]);
                }
            }
        }
    }

    // ── Activities ────────────────────────────────────────────────────────────

    public function get_activities($student_id) {
        return $this->db->where('student_id', $student_id)->get('holistic_activities')->result_array();
    }

    public function save_activities($student_id, $activities) {
        $this->db->where('student_id', $student_id)->delete('holistic_activities');
        foreach ($activities as $a) {
            if (!empty($a['activity_name'])) {
                $this->db->insert('holistic_activities', [
                    'student_id'    => $student_id,
                    'term'          => $a['term'],
                    'activity_name' => $a['activity_name']
                ]);
            }
        }
    }

    // ── Teacher Profile ───────────────────────────────────────────────────────

    public function get_teacher_profile($student_id) {
        return $this->db->where('student_id', $student_id)->get('holistic_teacher_profile')->row_array();
    }

    public function save_teacher_profile($student_id, $text) {
        $existing = $this->get_teacher_profile($student_id);
        if ($existing) {
            $this->db->where('student_id', $student_id)->update('holistic_teacher_profile', ['profile_text' => $text]);
        } else {
            $this->db->insert('holistic_teacher_profile', ['student_id' => $student_id, 'profile_text' => $text]);
        }
    }

    // ── Parent Feedback ───────────────────────────────────────────────────────

    public function get_parent_feedback($student_id) {
        return $this->db->where('student_id', $student_id)->get('holistic_parent_feedback')->result_array();
    }

    public function save_parent_feedback($student_id, $data) {
        foreach ($data as $term => $values) {
            $existing = $this->db->where(['student_id' => $student_id, 'term' => $term])->get('holistic_parent_feedback')->row_array();
            $values['student_id'] = $student_id;
            $values['term'] = $term;
            if ($existing) {
                $this->db->where(['student_id' => $student_id, 'term' => $term])->update('holistic_parent_feedback', $values);
            } else {
                $this->db->insert('holistic_parent_feedback', $values);
            }
        }
    }

    // ── Signatures ────────────────────────────────────────────────────────────

    public function get_signatures($student_id) {
        return $this->db->where('student_id', $student_id)->get('holistic_signatures')->result_array();
    }

    public function save_signatures($student_id, $data) {
        foreach ($data as $term => $values) {
            $existing = $this->db->where(['student_id' => $student_id, 'term' => $term])->get('holistic_signatures')->row_array();
            $values['student_id'] = $student_id;
            $values['term'] = $term;
            if ($existing) {
                $this->db->where(['student_id' => $student_id, 'term' => $term])->update('holistic_signatures', $values);
            } else {
                $this->db->insert('holistic_signatures', $values);
            }
        }
    }
}

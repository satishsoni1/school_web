<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Public page for parents (no login): download links for the PPGMIS app, how to sign in and use it,
 * and how to reach the school. Share as <portal>/appguide.
 */
class Appguide extends CI_Controller
{
    public function index()
    {
        // Plain CI_Controller: the database is not autoloaded here (the app's base controllers load it).
        // School name / logo are optional; the page renders with defaults if they can't be read.
        $settings = array();
        try {
            $this->load->database();
            $query = $this->db->where_in('fieldoption', array('sname', 'photo'))->get('setting');
            foreach ($query ? $query->result() : array() as $row) {
                $settings[$row->fieldoption] = $row->value;
            }
        } catch (Throwable $e) {
            log_message('error', 'appguide: ' . $e->getMessage());
        }
        $this->load->view('appguide/index', array(
            'schoolName' => isset($settings['sname']) && $settings['sname'] !== '' ? $settings['sname'] : 'P.P. Gagangiri Maharaj International School',
            'logo'       => !empty($settings['photo']) ? base_url('uploads/images/' . $settings['photo']) : '',
        ));
    }
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Public page for parents (no login): download links for the PPGMIS app, how to sign in and use it,
 * and how to reach the school. Share as <portal>/appguide.
 */
class Appguide extends CI_Controller
{
    public function index()
    {
        $settings = array();
        foreach ($this->db->where_in('fieldoption', array('sname', 'photo'))->get('setting')->result() as $row) {
            $settings[$row->fieldoption] = $row->value;
        }
        $this->load->view('appguide/index', array(
            'schoolName' => isset($settings['sname']) && $settings['sname'] !== '' ? $settings['sname'] : 'P.P. Gagangiri Maharaj International School',
            'logo'       => !empty($settings['photo']) ? base_url('uploads/images/' . $settings['photo']) : '',
        ));
    }
}

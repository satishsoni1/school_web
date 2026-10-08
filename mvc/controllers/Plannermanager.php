<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Retired: "Planner & Test Manager" was split into three admin modules —
 *   Academic → Academic Planner (academicplanner), Exam → Exam Timetable (examtimetable),
 *   Exam → Exam Portion (examportion).
 * Old links and bookmarks are redirected to the matching new page.
 */
class Plannermanager extends Admin_Controller
{
    public function _remap($method, $params = array())
    {
        $id = isset($params[0]) ? (int) $params[0] : 0;
        $map = array(
            'planner_edit'  => 'academicplanner/edit/' . $id,
            'test_edit'     => 'examtimetable/edit/' . $id,
            'syllabus_edit' => 'examportion/edit/' . $id,
            'test_add'      => 'examtimetable/add',
            'syllabus_add'  => 'examportion/add',
            'planner_add'   => 'academicplanner/add',
        );
        redirect(base_url(isset($map[$method]) ? $map[$method] : 'academicplanner/index'));
    }
}

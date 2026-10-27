<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin: student strength of an academic year — class-wise Boys / Girls / Total, new admissions and RTE,
 * with Pre-Primary and Grades sub-totals — plus total strength per class across every academic year.
 * Counts the year's studentrelation rows (the class a student was in that year); the "REMOVED" class
 * is left out. Print and CSV. Sidebar: Report → Academic Year Strength (added in Admin_Controller).
 */
class Strengthreport extends Admin_Controller
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

    /** ?schoolyearID=, or CSV with &export=csv. */
    public function index()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $years = array();
        foreach ((array) $this->schoolyear_m->get_order_by_schoolyear() as $y) {
            $years[(int) $y->schoolyearID] = $y->schoolyear;
        }
        $schoolyearID = (int) $this->input->get('schoolyearID');
        if (!isset($years[$schoolyearID])) {
            $schoolyearID = (int) $this->session->userdata('defaultschoolyearID');
        }

        // Classes in class order, without the "REMOVED" holding class.
        $classes = array();
        foreach ((array) $this->classes_m->general_get_order_by_classes() as $c) {
            if (strtoupper(trim($c->classes)) !== 'REMOVED') {
                $classes[(int) $c->classesID] = $c->classes;
            }
        }

        $strength = $this->strength($schoolyearID, $classes);
        if ($this->input->get('export') === 'csv') {
            $this->csv($strength, isset($years[$schoolyearID]) ? $years[$schoolyearID] : '');
            return;
        }

        $this->data['years']        = $years;
        $this->data['schoolyearID'] = $schoolyearID;
        $this->data['strength']     = $strength;
        $this->data['comparison']   = $this->comparison($years, $classes);
        $this->data['subview']      = 'report/strength/index';
        $this->load->view('_layout_main', $this->data);
    }

    /**
     * ['groups' => [label => ['rows' => [classesID => counts], 'total' => counts]], 'total' => counts, 'hasRte' => bool]
     * counts: name, boys, girls, other, total, new, rte.
     */
    private function strength($schoolyearID, $classes)
    {
        $hasRte = $this->db->field_exists('rte', 'student');
        $query = $this->db->query(
            "SELECT sr.srclassesID AS classesID, s.sex, COUNT(*) AS n,
                    SUM(s.createschoolyearID = ?) AS new_n, " . ($hasRte ? 'SUM(s.rte = 1)' : '0') . " AS rte_n
             FROM (SELECT srstudentID, MAX(srclassesID) AS srclassesID FROM studentrelation WHERE srschoolyearID = ? GROUP BY srstudentID) sr
             JOIN student s ON s.studentID = sr.srstudentID
             GROUP BY sr.srclassesID, s.sex",
            array($schoolyearID, $schoolyearID)
        );
        $blank = array('boys' => 0, 'girls' => 0, 'other' => 0, 'total' => 0, 'new' => 0, 'rte' => 0);
        $rows = array();
        foreach (($query ? $query->result() : array()) as $r) {
            $id = (int) $r->classesID;
            if (!isset($classes[$id])) {
                continue;
            }
            if (!isset($rows[$id])) {
                $rows[$id] = $blank;
            }
            $sex = strtolower(trim((string) $r->sex));
            $key = $sex === 'male' ? 'boys' : ($sex === 'female' ? 'girls' : 'other');
            $rows[$id][$key]  += (int) $r->n;
            $rows[$id]['total'] += (int) $r->n;
            $rows[$id]['new'] += (int) $r->new_n;
            $rows[$id]['rte'] += (int) $r->rte_n;
        }

        $result = array('groups' => array('Pre-Primary' => array('rows' => array(), 'total' => $blank), 'Grades' => array('rows' => array(), 'total' => $blank)),
            'total' => $blank, 'hasRte' => $hasRte);
        foreach ($classes as $id => $name) {
            if (!isset($rows[$id])) {
                continue;
            }
            $group = self::isPrePrimary($name) ? 'Pre-Primary' : 'Grades';
            $result['groups'][$group]['rows'][$id] = array('name' => $name) + $rows[$id];
            foreach ($blank as $k => $unused) {
                $result['groups'][$group]['total'][$k] += $rows[$id][$k];
                $result['total'][$k] += $rows[$id][$k];
            }
        }
        $result['groups'] = array_filter($result['groups'], function ($g) { return $g['rows']; });
        return $result;
    }

    /** ['years' => [schoolyearID => name], 'rows' => [classesID => [name, schoolyearID => n]], 'totals' => [schoolyearID => n]] for years that have students. */
    private function comparison($years, $classes)
    {
        $query = $this->db->query(
            "SELECT srschoolyearID AS y, srclassesID AS c, COUNT(DISTINCT srstudentID) AS n
             FROM studentrelation GROUP BY srschoolyearID, srclassesID"
        );
        $counts = array();
        foreach (($query ? $query->result() : array()) as $r) {
            if (isset($years[(int) $r->y], $classes[(int) $r->c])) {
                $counts[(int) $r->c][(int) $r->y] = (int) $r->n;
            }
        }
        $usedYears = array();
        foreach ($counts as $byYear) {
            $usedYears += array_fill_keys(array_keys($byYear), true);
        }
        // Oldest year first.
        $yearList = array_intersect_key($years, $usedYears);
        uasort($yearList, 'strnatcmp');

        $rows = array();
        $totals = array_fill_keys(array_keys($yearList), 0);
        foreach ($classes as $id => $name) {
            if (!isset($counts[$id])) {
                continue;
            }
            $rows[$id] = array('name' => $name);
            foreach ($yearList as $y => $unused) {
                $rows[$id][$y] = isset($counts[$id][$y]) ? $counts[$id][$y] : 0;
                $totals[$y] += $rows[$id][$y];
            }
        }
        return array('years' => $yearList, 'rows' => $rows, 'totals' => $totals);
    }

    public static function isPrePrimary($className)
    {
        return (bool) preg_match('/nursery|prep|kg|pre[\s-]*primary|playgroup/i', $className);
    }

    private function csv($strength, $year)
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="strength-' . $year . '.csv"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel opens it cleanly
        fputcsv($out, array('Academic Year Strength', $year));
        // "Other" only when some student's gender is not Male / Female, so Boys + Girls + Other = Total.
        $hasOther = $strength['total']['other'] > 0;
        $head = array_merge(array('Class', 'Boys', 'Girls'), $hasOther ? array('Other') : array(), array('Total', 'New Admissions'));
        if ($strength['hasRte']) {
            $head[] = 'RTE';
        }
        fputcsv($out, $head);
        $line = function ($label, $d) use ($strength, $hasOther) {
            $cells = array_merge(array($label, $d['boys'], $d['girls']), $hasOther ? array($d['other']) : array(), array($d['total'], $d['new']));
            if ($strength['hasRte']) {
                $cells[] = $d['rte'];
            }
            return $cells;
        };
        foreach ($strength['groups'] as $group => $g) {
            foreach ($g['rows'] as $row) {
                fputcsv($out, $line($row['name'], $row));
            }
            fputcsv($out, $line($group . ' Total', $g['total']));
        }
        fputcsv($out, $line('Grand Total', $strength['total']));
        fclose($out);
        exit;
    }
}

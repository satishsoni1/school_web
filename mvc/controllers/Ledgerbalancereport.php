<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin: balance (outstanding) fees per fee type ("ledger") for an academic year, with Print and CSV.
 * Same balance as the Balance Fees report: fee − discount % − paid − waiver, over the year's students
 * (studentrelation), class filter by the class they are in that year. ?feetype= lists that ledger's
 * students with dues. Sidebar: Report → Ledger-wise Balance Fees (added in Admin_Controller).
 */
class Ledgerbalancereport extends Admin_Controller
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

    /** ?schoolyearID=&classesID=&feetype=, or CSV with &export=csv. */
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

        $classes = array();
        foreach ((array) $this->classes_m->general_get_classes() as $c) {
            $classes[(int) $c->classesID] = $c->classes;
        }
        $classesID = (int) $this->input->get('classesID');
        if (!isset($classes[$classesID])) {
            $classesID = 0;
        }

        $invoices = $this->invoices($schoolyearID, $classesID);
        $report   = $this->summary($invoices);
        $feetype  = (string) $this->input->get('feetype');
        $students = isset($report['rows'][$feetype]) ? $this->studentDues($invoices, $feetype, $classes) : null;

        $filters = array(
            'year'  => isset($years[$schoolyearID]) ? $years[$schoolyearID] : '',
            'class' => $classesID ? $classes[$classesID] : 'All classes',
        );
        if ($this->input->get('export') === 'csv') {
            $students !== null ? $this->csvStudents($students, $feetype, $filters) : $this->csv($report, $filters);
            return;
        }

        $this->data['years']        = $years;
        $this->data['schoolyearID'] = $schoolyearID;
        $this->data['classes']      = $classes;
        $this->data['classesID']    = $classesID;
        $this->data['filters']      = $filters;
        $this->data['report']       = $report;
        $this->data['feetype']      = $students !== null ? $feetype : '';
        $this->data['students']     = $students;
        $this->data['subview']      = 'report/ledgerbalance/index';
        $this->load->view('_layout_main', $this->data);
    }

    /** Every live invoice of the year's students, with its ledger, paid and waived amounts. */
    private function invoices($schoolyearID, $classesID)
    {
        $sql = "SELECT i.invoiceID, i.studentID, i.amount, i.discount,
                       COALESCE(ft.feetypes, NULLIF(i.feetype, ''), 'Other') AS feetype,
                       sr.srclassesID, sr.srname, sr.srroll,
                       COALESCE(p.paid, 0) AS paid, COALESCE(w.weaver, 0) AS weaver
                FROM invoice i
                JOIN (SELECT srstudentID, MAX(srclassesID) AS srclassesID, MAX(srname) AS srname, MAX(srroll) AS srroll
                      FROM studentrelation WHERE srschoolyearID = ? GROUP BY srstudentID) sr ON sr.srstudentID = i.studentID
                LEFT JOIN feetypes ft ON ft.feetypesID = i.feetypeID
                LEFT JOIN (SELECT invoiceID, SUM(paymentamount) AS paid FROM payment GROUP BY invoiceID) p ON p.invoiceID = i.invoiceID
                LEFT JOIN (SELECT invoiceID, SUM(weaver) AS weaver FROM weaverandfine GROUP BY invoiceID) w ON w.invoiceID = i.invoiceID
                WHERE i.schoolyearID = ? AND i.deleted_at = 1";
        $params = array($schoolyearID, $schoolyearID);
        if ($classesID) {
            $sql .= ' AND sr.srclassesID = ?';
            $params[] = $classesID;
        }
        $query = $this->db->query($sql, $params);
        $rows = $query ? $query->result() : array();
        foreach ($rows as $r) {
            $r->discountAmount = (float) $r->amount * (float) $r->discount / 100;
            $r->net     = (float) $r->amount - $r->discountAmount;
            $r->balance = $r->net - (float) $r->paid - (float) $r->weaver;
        }
        return $rows;
    }

    /** ['rows' => [feetype => totals], 'totals' => totals]; totals: amount, discount, net, paid, weaver, balance, students, due. */
    private function summary($invoices)
    {
        $blank = array('amount' => 0, 'discount' => 0, 'net' => 0, 'paid' => 0, 'weaver' => 0, 'balance' => 0, 'students' => array(), 'due' => array());
        $report = array('rows' => array(), 'totals' => $blank);
        foreach ($invoices as $r) {
            if (!isset($report['rows'][$r->feetype])) {
                $report['rows'][$r->feetype] = $blank;
            }
            foreach (array(&$report['rows'][$r->feetype], &$report['totals']) as &$line) {
                $line['amount']   += (float) $r->amount;
                $line['discount'] += $r->discountAmount;
                $line['net']      += $r->net;
                $line['paid']     += (float) $r->paid;
                $line['weaver']   += (float) $r->weaver;
                $line['balance']  += $r->balance;
                $line['students'][$r->studentID] = true;
                if ($r->balance > 0.009) {
                    $line['due'][$r->studentID] = true;
                }
            }
            unset($line);
        }
        foreach (array_keys($report['rows']) as $k) {
            $report['rows'][$k]['students'] = count($report['rows'][$k]['students']);
            $report['rows'][$k]['due']      = count($report['rows'][$k]['due']);
        }
        $report['totals']['students'] = count($report['totals']['students']);
        $report['totals']['due']      = count($report['totals']['due']);
        ksort($report['rows']);
        return $report;
    }

    /** One ledger's students with a balance, largest first. */
    private function studentDues($invoices, $feetype, $classes)
    {
        $students = array();
        foreach ($invoices as $r) {
            if ($r->feetype !== $feetype) {
                continue;
            }
            if (!isset($students[$r->studentID])) {
                $students[$r->studentID] = array(
                    'name'  => $r->srname,
                    'class' => isset($classes[(int) $r->srclassesID]) ? $classes[(int) $r->srclassesID] : '',
                    'roll'  => $r->srroll,
                    'net' => 0, 'paid' => 0, 'weaver' => 0, 'balance' => 0,
                );
            }
            $students[$r->studentID]['net']     += $r->net;
            $students[$r->studentID]['paid']    += (float) $r->paid;
            $students[$r->studentID]['weaver']  += (float) $r->weaver;
            $students[$r->studentID]['balance'] += $r->balance;
        }
        $students = array_filter($students, function ($s) { return $s['balance'] > 0.009; });
        uasort($students, function ($a, $b) {
            return $b['balance'] <=> $a['balance'];
        });
        return $students;
    }

    private function csv($report, $filters)
    {
        $out = $this->csvStart('ledger-balance-fees-' . $filters['year'] . '.csv', array('Ledger-wise Balance Fees', $filters['year'], $filters['class']));
        fputcsv($out, array('Fee Type (Ledger)', 'Students', 'Fee Amount', 'Discount', 'Net Fees', 'Paid', 'Waiver', 'Balance', 'Students with Dues'));
        $line = function ($label, $d) {
            return array($label, $d['students'], $this->n($d['amount']), $this->n($d['discount']), $this->n($d['net']),
                $this->n($d['paid']), $this->n($d['weaver']), $this->n($d['balance']), $d['due']);
        };
        foreach ($report['rows'] as $feetype => $d) {
            fputcsv($out, $line($feetype, $d));
        }
        fputcsv($out, $line('Grand Total', $report['totals']));
        fclose($out);
        exit;
    }

    private function csvStudents($students, $feetype, $filters)
    {
        $out = $this->csvStart('balance-' . preg_replace('/[^a-z0-9]+/i', '-', $feetype) . '-' . $filters['year'] . '.csv', array('Balance Fees — ' . $feetype, $filters['year'], $filters['class']));
        fputcsv($out, array('Student', 'Class', 'Roll', 'Net Fees', 'Paid', 'Waiver', 'Balance'));
        foreach ($students as $s) {
            fputcsv($out, array($s['name'], $s['class'], $s['roll'], $this->n($s['net']), $this->n($s['paid']), $this->n($s['weaver']), $this->n($s['balance'])));
        }
        fclose($out);
        exit;
    }

    private function csvStart($filename, $titleRow)
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel opens it cleanly
        fputcsv($out, $titleRow);
        return $out;
    }

    private function n($v)
    {
        return number_format((float) $v, 2, '.', '');
    }
}

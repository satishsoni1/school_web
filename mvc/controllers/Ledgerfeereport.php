<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Admin: fees received per fee type ("ledger": Tuition, Admission, Books…) for a date range,
 * split by payment mode (Cash / Cheque / Online), with Print and CSV.
 * Counts fee payments (payment table, by payment date); Book Receipt sales are not included.
 * Sidebar: Report → Ledger-wise Fee Received (db_migration_ledgerfeereport_menu.sql).
 */
class Ledgerfeereport extends Admin_Controller
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

    /** ?from=Y-m-d&to=Y-m-d&classesID=, or CSV with &export=csv. Defaults to the school year so far. */
    public function index()
    {
        if (!$this->requireAdmin()) {
            return;
        }
        $schoolyear = $this->schoolyear_m->get_single_schoolyear(array('schoolyearID' => $this->session->userdata('defaultschoolyearID')));
        $from = $this->validDate($this->input->get('from'), $schoolyear ? $schoolyear->startingdate : date('Y-04-01'));
        $to   = $this->validDate($this->input->get('to'), date('Y-m-d'));
        if ($from > $to) {
            list($from, $to) = array($to, $from);
        }

        $classes = array();
        foreach ((array) $this->classes_m->general_get_classes() as $c) {
            $classes[(int) $c->classesID] = $c->classes;
        }
        $classesID = (int) $this->input->get('classesID');
        if (!isset($classes[$classesID])) {
            $classesID = 0;
        }

        $report = $this->summary($from, $to, $classesID);

        if ($this->input->get('export') === 'csv') {
            $this->csv($report, $from, $to, $classesID ? $classes[$classesID] : 'All classes');
            return;
        }

        $this->data['from']      = $from;
        $this->data['to']        = $to;
        $this->data['classes']   = $classes;
        $this->data['classesID'] = $classesID;
        $this->data['report']    = $report;
        $this->data['subview']   = 'report/ledgerfee/index';
        $this->load->view('_layout_main', $this->data);
    }

    /** ['modes' => [mode...], 'rows' => [feetype => [mode => amount, 'total', 'count']], 'totals' => [...]] */
    private function summary($from, $to, $classesID)
    {
        $this->db->select("COALESCE(feetypes.feetypes, NULLIF(invoice.feetype, ''), 'Other') AS feetype,
                           payment.paymenttype, COUNT(*) AS receipts, SUM(payment.paymentamount) AS amount", FALSE);
        $this->db->from('payment');
        $this->db->join('invoice', 'invoice.invoiceID = payment.invoiceID', 'LEFT');
        $this->db->join('feetypes', 'feetypes.feetypesID = invoice.feetypeID', 'LEFT');
        $this->db->where('payment.paymentdate >=', $from);
        $this->db->where('payment.paymentdate <=', $to);
        if ($classesID) {
            $this->db->where('invoice.classesID', $classesID);
        }
        $this->db->group_by(array('feetype', 'payment.paymenttype'));
        $result = $this->db->get();
        $rows = $result ? $result->result() : array();

        // Usual modes first, any others after.
        $modes = array('Cash', 'Cheque', 'Online');
        $report = array('rows' => array(), 'totals' => array('total' => 0, 'count' => 0));
        foreach ($rows as $r) {
            $mode = trim((string) $r->paymenttype) !== '' ? $r->paymenttype : 'Other';
            if (!in_array($mode, $modes, true)) {
                $modes[] = $mode;
            }
            $line = &$report['rows'][$r->feetype];
            $line[$mode]      = (isset($line[$mode]) ? $line[$mode] : 0) + (float) $r->amount;
            $line['total']    = (isset($line['total']) ? $line['total'] : 0) + (float) $r->amount;
            $line['count']    = (isset($line['count']) ? $line['count'] : 0) + (int) $r->receipts;
            $report['totals'][$mode] = (isset($report['totals'][$mode]) ? $report['totals'][$mode] : 0) + (float) $r->amount;
            $report['totals']['total'] += (float) $r->amount;
            $report['totals']['count'] += (int) $r->receipts;
            unset($line);
        }
        ksort($report['rows']);
        $report['modes'] = $modes;
        return $report;
    }

    private function csv($report, $from, $to, $classLabel)
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="ledger-fee-received-' . $from . '-to-' . $to . '.csv"');
        $out = fopen('php://output', 'w');
        fwrite($out, "\xEF\xBB\xBF"); // UTF-8 BOM so Excel opens it cleanly
        fputcsv($out, array('Ledger-wise Fee Received', date('d-m-Y', strtotime($from)) . ' to ' . date('d-m-Y', strtotime($to)), $classLabel));
        fputcsv($out, array_merge(array('Fee Type (Ledger)'), $report['modes'], array('Total', 'Receipts')));
        $line = function ($label, $data) use ($report) {
            $cells = array($label);
            foreach ($report['modes'] as $mode) {
                $cells[] = number_format(isset($data[$mode]) ? $data[$mode] : 0, 2, '.', '');
            }
            $cells[] = number_format($data['total'], 2, '.', '');
            $cells[] = $data['count'];
            return $cells;
        };
        foreach ($report['rows'] as $feetype => $data) {
            fputcsv($out, $line($feetype, $data));
        }
        fputcsv($out, $line('Grand Total', $report['totals']));
        fclose($out);
        exit;
    }

    private function validDate($value, $default)
    {
        $value = (string) $value;
        return (preg_match('/^\d{4}-\d{2}-\d{2}$/', $value) && strtotime($value)) ? $value : $default;
    }
}

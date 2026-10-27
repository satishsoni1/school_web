<?php
$base  = array('schoolyearID' => $schoolyearID, 'classesID' => $classesID);
$url   = function ($extra = array()) use ($base) {
    return base_url('ledgerbalancereport/index?' . http_build_query(array_merge($base, $extra)));
};
$money = function ($v) { return number_format((float) $v, 2); };
$t     = $report['totals'];
$pct   = $t['net'] > 0 ? round(($t['paid'] + $t['weaver']) / $t['net'] * 100) : 0;
?>
<style>
    .lb-stats { display: flex; flex-wrap: wrap; gap: 12px; margin: 0 0 16px; }
    .lb-stat { flex: 1 1 160px; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; }
    .lb-stat small { display: block; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
    .lb-stat b { font-size: 20px; color: #0f172a; }
    .lb-stat.due b { color: #b91c1c; }
    .lb-bar { height: 6px; margin-top: 6px; border-radius: 3px; background: #e2e8f0; overflow: hidden; }
    .lb-bar span { display: block; height: 100%; background: #2f7d6d; }
    .lb-balance { color: #b91c1c; font-weight: bold; }
</style>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-balance-scale"></i> Ledger-wise Balance Fees</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <?php if ($feetype !== '') { ?>
                <li><a href="<?=$url()?>">Ledger-wise Balance Fees</a></li>
                <li class="active"><?=html_escape($feetype)?></li>
            <?php } else { ?>
                <li class="active">Ledger-wise Balance Fees</li>
            <?php } ?>
        </ol>
    </div>

    <div class="box-body">
        <p class="text-muted">Outstanding fees per fee type (ledger) for an academic year: fee − discount − paid − waiver. Click a ledger to see the students who still owe it.</p>

        <form method="get" action="<?=base_url('ledgerbalancereport/index')?>" class="row">
            <div class="form-group col-sm-4">
                <label for="schoolyearID">Academic year</label>
                <select name="schoolyearID" id="schoolyearID" class="form-control">
                    <?php foreach ($years as $id => $name) { ?>
                        <option value="<?=$id?>" <?=$id == $schoolyearID ? 'selected' : ''?>><?=html_escape($name)?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-sm-4">
                <label for="classesID">Class</label>
                <select name="classesID" id="classesID" class="form-control">
                    <option value="0">All classes</option>
                    <?php foreach ($classes as $id => $name) { ?>
                        <option value="<?=$id?>" <?=$id == $classesID ? 'selected' : ''?>><?=html_escape($name)?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-sm-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-success btn-block"><i class="fa fa-search"></i> Get Report</button>
            </div>
        </form>

        <?php if ($report['rows']) { ?>
        <div class="lb-stats">
            <div class="lb-stat"><small>Net fees</small><b>&#8377;<?=$money($t['net'])?></b></div>
            <div class="lb-stat"><small>Paid + waived</small><b>&#8377;<?=$money($t['paid'] + $t['weaver'])?></b>
                <div class="lb-bar"><span style="width:<?=min(100, max(0, $pct))?>%"></span></div><small style="margin-top:4px; text-transform:none;"><?=$pct?>% collected</small></div>
            <div class="lb-stat due"><small>Balance</small><b>&#8377;<?=$money($t['balance'])?></b></div>
            <div class="lb-stat"><small>Students with dues</small><b><?=(int) $t['due']?></b> <span class="text-muted">of <?=(int) $t['students']?></span></div>
        </div>
        <?php } ?>

        <div style="margin:5px 0 15px;">
            <?php if ($feetype !== '') { ?>
                <a href="<?=$url()?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> All ledgers</a>
            <?php } ?>
            <button type="button" class="btn btn-default" id="printReport" <?=$report['rows'] ? '' : 'disabled'?>><i class="fa fa-print"></i> Print</button>
            <a href="<?=$url(array('feetype' => $feetype, 'export' => 'csv'))?>" class="btn btn-default <?=$report['rows'] ? '' : 'disabled'?>"><i class="fa fa-file-excel-o"></i> Download CSV</a>
        </div>

        <div id="printablediv">
            <h4 class="page-header" style="margin-top:0;">
                <?=$feetype !== '' ? html_escape($feetype) . ' — students with dues' : 'Balance by ledger'?>
                <small><?=html_escape($filters['year'])?> · <?=html_escape($filters['class'])?></small>
            </h4>

            <?php if (!$report['rows']) { ?>
                <div class="callout callout-danger"><p><b class="text-info">No fee invoices for this year<?=$classesID ? ' and class' : ''?>.</b></p></div>

            <?php } elseif ($feetype !== '') { ?>
                <?php if (!$students) { ?>
                    <div class="callout callout-success"><p><b>Nobody owes <?=html_escape($feetype)?>.</b></p></div>
                <?php } else { $sum = array('net' => 0, 'paid' => 0, 'weaver' => 0, 'balance' => 0); ?>
                <div id="hide-table">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr><th>#</th><th>Student</th><th>Class</th><th>Roll</th><th class="text-right">Net Fees</th><th class="text-right">Paid</th><th class="text-right">Waiver</th><th class="text-right">Balance</th></tr>
                        </thead>
                        <tbody>
                        <?php $i = 0; foreach ($students as $s) { $i++; foreach ($sum as $k => $v) { $sum[$k] += $s[$k]; } ?>
                            <tr>
                                <td data-title="#"><?=$i?></td>
                                <td data-title="Student"><b><?=html_escape($s['name'])?></b></td>
                                <td data-title="Class"><?=html_escape($s['class'])?></td>
                                <td data-title="Roll"><?=html_escape($s['roll'])?></td>
                                <td data-title="Net Fees" class="text-right"><?=$money($s['net'])?></td>
                                <td data-title="Paid" class="text-right"><?=$money($s['paid'])?></td>
                                <td data-title="Waiver" class="text-right"><?=$money($s['weaver'])?></td>
                                <td data-title="Balance" class="text-right lb-balance"><?=$money($s['balance'])?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr style="background:#f1f5f9;">
                                <th colspan="4">Total (<?=$i?> students)</th>
                                <th class="text-right"><?=$money($sum['net'])?></th>
                                <th class="text-right"><?=$money($sum['paid'])?></th>
                                <th class="text-right"><?=$money($sum['weaver'])?></th>
                                <th class="text-right"><?=$money($sum['balance'])?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <?php } ?>

            <?php } else { ?>
                <div id="hide-table">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Fee Type (Ledger)</th>
                                <th class="text-right">Students</th>
                                <th class="text-right">Fee Amount</th>
                                <th class="text-right">Discount</th>
                                <th class="text-right">Net Fees</th>
                                <th class="text-right">Paid</th>
                                <th class="text-right">Waiver</th>
                                <th class="text-right">Balance</th>
                                <th class="text-right">Students with Dues</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($report['rows'] as $name => $d) { ?>
                            <tr>
                                <td data-title="Fee Type"><a href="<?=$url(array('feetype' => $name))?>"><b><?=html_escape($name)?></b></a></td>
                                <td data-title="Students" class="text-right"><?=(int) $d['students']?></td>
                                <td data-title="Fee Amount" class="text-right"><?=$money($d['amount'])?></td>
                                <td data-title="Discount" class="text-right"><?=$money($d['discount'])?></td>
                                <td data-title="Net Fees" class="text-right"><?=$money($d['net'])?></td>
                                <td data-title="Paid" class="text-right"><?=$money($d['paid'])?></td>
                                <td data-title="Waiver" class="text-right"><?=$money($d['weaver'])?></td>
                                <td data-title="Balance" class="text-right <?=$d['balance'] > 0.009 ? 'lb-balance' : ''?>"><?=$money($d['balance'])?></td>
                                <td data-title="Students with Dues" class="text-right">
                                    <?php if ($d['due']) { ?><a href="<?=$url(array('feetype' => $name))?>"><?=(int) $d['due']?></a><?php } else { ?>0<?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr style="background:#f1f5f9;">
                                <th>Grand Total<?=!empty($siteinfos->currency_code) ? ' (' . html_escape($siteinfos->currency_code) . ')' : ''?></th>
                                <th class="text-right"><?=(int) $t['students']?></th>
                                <th class="text-right"><?=$money($t['amount'])?></th>
                                <th class="text-right"><?=$money($t['discount'])?></th>
                                <th class="text-right"><?=$money($t['net'])?></th>
                                <th class="text-right"><?=$money($t['paid'])?></th>
                                <th class="text-right"><?=$money($t['weaver'])?></th>
                                <th class="text-right"><?=$money($t['balance'])?></th>
                                <th class="text-right"><?=(int) $t['due']?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#printReport').on('click', function () {
        var w = window.open('', '_blank');
        w.document.write('<html><head><title>Ledger-wise Balance Fees</title><style>'
            + 'body{font-family:Arial,sans-serif;font-size:12px;color:#000;margin:20px;}'
            + 'h2{text-align:center;margin:0 0 4px;} .sub{text-align:center;margin:0 0 16px;color:#444;}'
            + 'h4{margin:0 0 8px;font-size:14px;} h4 small{font-weight:normal;color:#555;margin-left:6px;}'
            + 'table{width:100%;border-collapse:collapse;} th,td{border:1px solid #888;padding:4px 6px;text-align:left;}'
            + 'th{background:#eee;} .text-right{text-align:right;} a{color:#000;text-decoration:none;}'
            + '</style></head><body>'
            + '<h2><?=html_escape($siteinfos->sname)?></h2><p class="sub">Ledger-wise Balance Fees</p>'
            + document.getElementById('printablediv').innerHTML
            + '</body></html>');
        w.document.close();
        w.focus();
        w.print();
    });
</script>

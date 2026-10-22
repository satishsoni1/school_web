<?php
$query = http_build_query(array('from' => $from, 'to' => $to, 'classesID' => $classesID));
$money = function ($v) { return number_format((float) $v, 2); };
$period = date('d M Y', strtotime($from)) . ' – ' . date('d M Y', strtotime($to));
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-book"></i> Ledger-wise Fee Received</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Ledger-wise Fee Received</li>
        </ol>
    </div>

    <div class="box-body">
        <p class="text-muted">Fees received per fee type (ledger) by payment date, split by payment mode. Book Receipt sales are not included.</p>

        <form method="get" action="<?=base_url('ledgerfeereport/index')?>" class="row">
            <div class="form-group col-sm-3">
                <label for="from">From</label>
                <input type="date" name="from" id="from" class="form-control" value="<?=html_escape($from)?>" required>
            </div>
            <div class="form-group col-sm-3">
                <label for="to">To</label>
                <input type="date" name="to" id="to" class="form-control" value="<?=html_escape($to)?>" required>
            </div>
            <div class="form-group col-sm-3">
                <label for="classesID">Class</label>
                <select name="classesID" id="classesID" class="form-control">
                    <option value="0">All classes</option>
                    <?php foreach ($classes as $id => $name) { ?>
                        <option value="<?=$id?>" <?=$id == $classesID ? 'selected' : ''?>><?=html_escape($name)?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-sm-3">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-success btn-block"><i class="fa fa-search"></i> Get Report</button>
            </div>
        </form>

        <div style="margin:5px 0 15px;">
            <button type="button" class="btn btn-default" id="printReport" <?=$report['rows'] ? '' : 'disabled'?>><i class="fa fa-print"></i> Print</button>
            <a href="<?=base_url('ledgerfeereport/index?' . $query . '&export=csv')?>" class="btn btn-default <?=$report['rows'] ? '' : 'disabled'?>"><i class="fa fa-file-excel-o"></i> Download CSV</a>
        </div>

        <div id="printablediv">
            <h4 class="page-header" style="margin-top:0;">
                <?=$period?> <small><?=$classesID ? html_escape($classes[$classesID]) : 'All classes'?> · <?=(int) $report['totals']['count']?> receipts</small>
            </h4>
            <?php if (!$report['rows']) { ?>
                <div class="callout callout-danger"><p><b class="text-info">No fees received in this period.</b></p></div>
            <?php } else { ?>
                <div id="hide-table">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Fee Type (Ledger)</th>
                                <?php foreach ($report['modes'] as $mode) { ?><th class="text-right"><?=html_escape($mode)?></th><?php } ?>
                                <th class="text-right">Total</th>
                                <th class="text-right">Receipts</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($report['rows'] as $feetype => $data) { ?>
                            <tr>
                                <td data-title="Fee Type"><b><?=html_escape($feetype)?></b></td>
                                <?php foreach ($report['modes'] as $mode) { ?>
                                    <td data-title="<?=html_escape($mode)?>" class="text-right"><?=$money(isset($data[$mode]) ? $data[$mode] : 0)?></td>
                                <?php } ?>
                                <td data-title="Total" class="text-right"><b><?=$money($data['total'])?></b></td>
                                <td data-title="Receipts" class="text-right"><?=(int) $data['count']?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr style="background:#f1f5f9;">
                                <th>Grand Total<?=!empty($siteinfos->currency_code) ? ' (' . html_escape($siteinfos->currency_code) . ')' : ''?></th>
                                <?php foreach ($report['modes'] as $mode) { ?>
                                    <th class="text-right"><?=$money(isset($report['totals'][$mode]) ? $report['totals'][$mode] : 0)?></th>
                                <?php } ?>
                                <th class="text-right"><?=$money($report['totals']['total'])?></th>
                                <th class="text-right"><?=(int) $report['totals']['count']?></th>
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
        w.document.write('<html><head><title>Ledger-wise Fee Received</title><style>'
            + 'body{font-family:Arial,sans-serif;font-size:12px;color:#000;margin:20px;}'
            + 'h2{text-align:center;margin:0 0 4px;} .sub{text-align:center;margin:0 0 16px;color:#444;}'
            + 'h4{margin:0 0 8px;font-size:14px;} h4 small{font-weight:normal;color:#555;margin-left:6px;}'
            + 'table{width:100%;border-collapse:collapse;} th,td{border:1px solid #888;padding:4px 6px;text-align:left;}'
            + 'th{background:#eee;} .text-right{text-align:right;}'
            + '</style></head><body>'
            + '<h2><?=html_escape($siteinfos->sname)?></h2><p class="sub">Ledger-wise Fee Received</p>'
            + document.getElementById('printablediv').innerHTML
            + '</body></html>');
        w.document.close();
        w.focus();
        w.print();
    });
</script>

<?php
$t        = $strength['total'];
$hasRte   = $strength['hasRte'];
$hasOther = $t['other'] > 0;
$yearName = isset($years[$schoolyearID]) ? $years[$schoolyearID] : '';
$cells = function ($d, $tag = 'td') use ($hasRte, $hasOther) {
    $html = "<$tag class=\"text-right\">" . (int) $d['boys'] . "</$tag><$tag class=\"text-right\">" . (int) $d['girls'] . "</$tag>";
    if ($hasOther) {
        $html .= "<$tag class=\"text-right\">" . (int) $d['other'] . "</$tag>";
    }
    $html .= "<$tag class=\"text-right st-total\">" . (int) $d['total'] . "</$tag><$tag class=\"text-right\">" . (int) $d['new'] . "</$tag>";
    if ($hasRte) {
        $html .= "<$tag class=\"text-right\">" . (int) $d['rte'] . "</$tag>";
    }
    return $html;
};
$colspan = 5 + ($hasOther ? 1 : 0) + ($hasRte ? 1 : 0);
?>
<style>
    .st-stats { display: flex; flex-wrap: wrap; gap: 12px; margin: 0 0 16px; }
    .st-stat { flex: 1 1 140px; padding: 12px 14px; border: 1px solid #e2e8f0; border-radius: 8px; background: #fff; }
    .st-stat small { display: block; color: #64748b; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; }
    .st-stat b { font-size: 22px; color: #0f172a; }
    .st-total { font-weight: bold; }
    .st-sub td, .st-sub th { background: #f1f5f9 !important; font-weight: bold; }
    .st-group th { background: #e6f2ef !important; color: #2f7d6d; }
</style>

<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-users"></i> Academic Year Strength</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Academic Year Strength</li>
        </ol>
    </div>

    <div class="box-body">
        <p class="text-muted">Class-wise number of students in an academic year (the class each student was in that year). Students in the "REMOVED" class are not counted.</p>

        <form method="get" action="<?=base_url('strengthreport/index')?>" class="row">
            <div class="form-group col-sm-4">
                <label for="schoolyearID">Academic year</label>
                <select name="schoolyearID" id="schoolyearID" class="form-control" onchange="this.form.submit()">
                    <?php foreach ($years as $id => $name) { ?>
                        <option value="<?=$id?>" <?=$id == $schoolyearID ? 'selected' : ''?>><?=html_escape($name)?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-sm-8">
                <label>&nbsp;</label>
                <div>
                    <button type="button" class="btn btn-default" id="printReport" <?=$t['total'] ? '' : 'disabled'?>><i class="fa fa-print"></i> Print</button>
                    <a href="<?=base_url('strengthreport/index?schoolyearID=' . $schoolyearID . '&export=csv')?>" class="btn btn-default <?=$t['total'] ? '' : 'disabled'?>"><i class="fa fa-file-excel-o"></i> Download CSV</a>
                </div>
            </div>
        </form>

        <?php if ($t['total']) { ?>
        <div class="st-stats">
            <div class="st-stat"><small>Total strength</small><b><?=(int) $t['total']?></b></div>
            <div class="st-stat"><small>Boys</small><b><?=(int) $t['boys']?></b></div>
            <div class="st-stat"><small>Girls</small><b><?=(int) $t['girls']?></b></div>
            <div class="st-stat"><small>New admissions</small><b><?=(int) $t['new']?></b></div>
            <?php if ($hasRte) { ?><div class="st-stat"><small>RTE</small><b><?=(int) $t['rte']?></b></div><?php } ?>
        </div>
        <?php } ?>

        <div id="printablediv">
            <h4 class="page-header" style="margin-top:0;">Strength <?=html_escape($yearName)?></h4>
            <?php if (!$t['total']) { ?>
                <div class="callout callout-danger"><p><b class="text-info">No students in <?=html_escape($yearName)?>.</b></p></div>
            <?php } else { ?>
            <div id="hide-table">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th class="text-right">Boys</th>
                            <th class="text-right">Girls</th>
                            <?php if ($hasOther) { ?><th class="text-right">Other</th><?php } ?>
                            <th class="text-right">Total</th>
                            <th class="text-right">New Admissions</th>
                            <?php if ($hasRte) { ?><th class="text-right">RTE</th><?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($strength['groups'] as $group => $g) { ?>
                        <tr class="st-group"><th colspan="<?=$colspan + 1?>"><?=html_escape($group)?></th></tr>
                        <?php foreach ($g['rows'] as $row) { ?>
                            <tr><td><?=html_escape($row['name'])?></td><?=$cells($row)?></tr>
                        <?php } ?>
                        <tr class="st-sub"><td><?=html_escape($group)?> Total</td><?=$cells($g['total'])?></tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr style="background:#0f172a; color:#fff;"><th>Grand Total</th><?=$cells($t, 'th')?></tr>
                    </tfoot>
                </table>
            </div>
            <?php } ?>

            <?php if (count($comparison['years']) > 0 && $comparison['rows']) { ?>
            <h4 class="page-header" style="margin-top:30px;">Year-wise comparison <small>total students per class</small></h4>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <?php foreach ($comparison['years'] as $y => $name) { ?>
                                <th class="text-right" <?=$y == $schoolyearID ? 'style="background:#e6f2ef;"' : ''?>><?=html_escape($name)?></th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($comparison['rows'] as $row) { ?>
                        <tr>
                            <td><?=html_escape($row['name'])?></td>
                            <?php foreach ($comparison['years'] as $y => $unused) { ?>
                                <td class="text-right" <?=$y == $schoolyearID ? 'style="background:#f3faf8; font-weight:bold;"' : ''?>><?=$row[$y] ?: '–'?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr style="background:#f1f5f9;">
                            <th>Total</th>
                            <?php foreach ($comparison['totals'] as $n) { ?><th class="text-right"><?=(int) $n?></th><?php } ?>
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
        w.document.write('<html><head><title>Academic Year Strength</title><style>'
            + 'body{font-family:Arial,sans-serif;font-size:12px;color:#000;margin:20px;}'
            + 'h2{text-align:center;margin:0 0 4px;} .sub{text-align:center;margin:0 0 16px;color:#444;}'
            + 'h4{margin:14px 0 8px;font-size:14px;} h4 small{font-weight:normal;color:#555;margin-left:6px;}'
            + 'table{width:100%;border-collapse:collapse;margin-bottom:10px;} th,td{border:1px solid #888;padding:4px 6px;text-align:left;}'
            + 'th{background:#eee;} .text-right{text-align:right;} .st-sub td,.st-group th{background:#f1f1f1;font-weight:bold;}'
            + 'tfoot tr{background:#ddd !important;color:#000 !important;}'
            + '</style></head><body>'
            + '<h2><?=html_escape($siteinfos->sname)?></h2><p class="sub">Academic Year Strength — <?=html_escape($yearName)?></p>'
            + document.getElementById('printablediv').innerHTML
            + '</body></html>');
        w.document.close();
        w.focus();
        w.print();
    });
</script>

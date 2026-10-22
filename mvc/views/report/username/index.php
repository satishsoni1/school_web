<?php
$query = http_build_query(array('classesID' => $classesID, 'status' => $status));
$statusLabels = array('active' => 'Active students', 'inactive' => 'Inactive students', 'all' => 'All students');
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-user-secret"></i> Username List</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Username List</li>
        </ol>
    </div>

    <div class="box-body">
        <p class="text-muted">Class-wise student and parent logins (username and password) for <?=$schoolyear ? html_escape($schoolyear->schoolyear) : 'the current school year'?>.</p>

        <form method="get" action="<?=base_url('usernamereport/index')?>" class="row">
            <div class="form-group col-sm-4">
                <label for="classesID">Class</label>
                <select name="classesID" id="classesID" class="form-control">
                    <option value="0">All classes</option>
                    <?php foreach ($classes as $c) { ?>
                        <option value="<?=$c->classesID?>" <?=$c->classesID == $classesID ? 'selected' : ''?>><?=html_escape($c->classes)?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-sm-4">
                <label for="status">Students</label>
                <select name="status" id="status" class="form-control">
                    <?php foreach ($statusLabels as $k => $label) { ?>
                        <option value="<?=$k?>" <?=$k === $status ? 'selected' : ''?>><?=$label?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group col-sm-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-success btn-block"><i class="fa fa-search"></i> Get Report</button>
            </div>
        </form>

        <?php if ($generated) { ?>
            <div style="margin:5px 0 15px;">
                <button type="button" class="btn btn-default" id="printReport" <?=$total ? '' : 'disabled'?>><i class="fa fa-print"></i> Print</button>
                <a href="<?=base_url('usernamereport/index?' . $query . '&export=csv')?>" class="btn btn-default <?=$total ? '' : 'disabled'?>"><i class="fa fa-file-excel-o"></i> Download CSV</a>
                <span class="text-muted" style="margin-left:10px;"><?=$total?> student<?=$total == 1 ? '' : 's'?> · <?=$statusLabels[$status]?></span>
            </div>

            <div id="printablediv">
            <?php if (!$total) { ?>
                <div class="callout callout-danger"><p><b class="text-info">No students found for this selection.</b></p></div>
            <?php } ?>
            <?php foreach ($grouped as $cid => $list) { ?>
                <div class="username-class">
                    <h4 class="page-header" style="margin-top:10px;">
                        <?=html_escape(Usernamereport::className($list[0], $classes))?>
                        <small><?=count($list)?> student<?=count($list) == 1 ? '' : 's'?></small>
                    </h4>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Roll</th>
                                    <th>Student Name</th>
                                    <th>Student Username</th>
                                    <th>Student Password</th>
                                    <th>Parent Name</th>
                                    <th>Parent Username</th>
                                    <th>Parent Password</th>
                                    <th>Parent Phone</th>
                                    <?php if ($status === 'all') { ?><th>Status</th><?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 1; foreach ($list as $r) { ?>
                                <tr>
                                    <td data-title="#"><?=$i++?></td>
                                    <td data-title="Roll"><?=html_escape($r->srroll)?></td>
                                    <td data-title="Student Name"><?=html_escape($r->srname)?></td>
                                    <td data-title="Student Username"><b><?=html_escape($r->username)?></b></td>
                                    <td data-title="Student Password"><?=html_escape($r->password)?></td>
                                    <td data-title="Parent Name"><?=$r->parent_name !== null ? html_escape($r->parent_name) : '<span class="text-muted">No parent linked</span>'?></td>
                                    <td data-title="Parent Username"><b><?=html_escape($r->parent_username)?></b></td>
                                    <td data-title="Parent Password"><?=html_escape($r->parent_password)?></td>
                                    <td data-title="Parent Phone"><?=html_escape($r->parent_phone)?></td>
                                    <?php if ($status === 'all') { ?>
                                        <td data-title="Status"><?=$r->active ? '<span class="label label-success">Active</span>' : '<span class="label label-default">Inactive</span>'?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
            </div>
        <?php } ?>
    </div>
</div>

<script type="text/javascript">
    $('#printReport').on('click', function () {
        var title = 'Username List<?=$schoolyear ? ' — ' . html_escape($schoolyear->schoolyear) : ''?>';
        var w = window.open('', '_blank');
        w.document.write('<html><head><title>' + title + '</title><style>'
            + 'body{font-family:Arial,sans-serif;font-size:12px;color:#000;margin:20px;}'
            + 'h2{text-align:center;margin:0 0 4px;} .sub{text-align:center;margin:0 0 16px;color:#444;}'
            + 'h4{margin:18px 0 6px;font-size:14px;} h4 small{font-weight:normal;color:#555;margin-left:6px;}'
            + 'table{width:100%;border-collapse:collapse;} th,td{border:1px solid #888;padding:4px 6px;text-align:left;}'
            + 'th{background:#eee;} .label{font-weight:normal;} .username-class{page-break-after:always;}'
            + '.username-class:last-child{page-break-after:auto;}'
            + '</style></head><body>'
            + '<h2><?=html_escape($siteinfos->sname)?></h2><p class="sub">' + title + '</p>'
            + document.getElementById('printablediv').innerHTML
            + '</body></html>');
        w.document.close();
        w.focus();
        w.print();
    });
</script>

<?php
$typeLabels = array('holiday' => 'danger', 'activity' => 'info', 'sports' => 'default', 'exam' => 'success', 'test' => 'warning', 'event' => 'primary');
$currentUrl = current_url() . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '');
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> Academic Planner</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Academic Planner</li>
        </ol>
    </div>

    <div class="box-body">
        <h5 class="page-header">
            <a href="<?=base_url('academicplanner/add?back=' . urlencode($currentUrl))?>"><i class="fa fa-plus"></i> Add New Event</a>
        </h5>

        <form class="form-inline" method="get" action="<?=base_url('academicplanner/index')?>" style="margin-bottom:15px;">
            <?php if ($hasAudience) { ?>
            <div class="form-group">
                <label>Planner</label>
                <select class="form-control" name="planner">
                    <option value="">Both planners</option>
                    <?php foreach ($audiences as $key => $label) { ?>
                        <option value="<?=$key?>" <?=$filter['planner'] === $key ? 'selected' : ''?>><?=html_escape($label)?></option>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>
            <div class="form-group">
                <label>Type</label>
                <select class="form-control" name="type">
                    <option value="">All types</option>
                    <?php foreach ($types as $t) { ?>
                        <option value="<?=$t?>" <?=$filter['type'] === $t ? 'selected' : ''?>><?=ucfirst($t)?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Month</label>
                <input type="month" class="form-control" name="month" value="<?=html_escape($filter['month'])?>">
            </div>
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Show</button>
            <a href="<?=base_url('academicplanner/index')?>" class="btn btn-link">Clear</a>
        </form>

        <div id="hide-table">
            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                    <tr>
                        <th class="col-sm-1">#</th>
                        <th class="col-sm-2">Date</th>
                        <th>Title</th>
                        <th class="col-sm-1">Type</th>
                        <?php if ($hasAudience) { ?><th class="col-sm-2">Planner</th><?php } ?>
                        <th class="col-sm-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (customCompute($events)) { $i = 1; foreach ($events as $ev) { ?>
                    <tr>
                        <td data-title="#"><?=$i++?></td>
                        <td data-title="Date"><?=date('d M Y', strtotime($ev->event_date))?> <small class="text-muted"><?=date('D', strtotime($ev->event_date))?></small></td>
                        <td data-title="Title">
                            <?=html_escape($ev->title)?>
                            <?php if (!empty($ev->description)) { ?><br><small class="text-muted"><?=html_escape($ev->description)?></small><?php } ?>
                        </td>
                        <td data-title="Type"><span class="label label-<?=isset($typeLabels[$ev->type]) ? $typeLabels[$ev->type] : 'default'?>"><?=ucfirst(html_escape($ev->type))?></span></td>
                        <?php if ($hasAudience) { ?><td data-title="Planner"><?=html_escape(isset($audiences[$ev->audience]) ? $audiences[$ev->audience] : $ev->audience)?></td><?php } ?>
                        <td data-title="Action">
                            <a class="btn btn-warning btn-xs mrg" href="<?=base_url('academicplanner/edit/' . $ev->id . '?back=' . urlencode($currentUrl))?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-danger btn-xs mrg" href="<?=base_url('academicplanner/delete/' . $ev->id . '?back=' . urlencode($currentUrl))?>" onclick="return confirm('Delete this event?');" data-toggle="tooltip" title="Delete"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                <?php }} else { ?>
                    <tr><td colspan="6" class="text-center text-muted">No events for this selection.</td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

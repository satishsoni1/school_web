<?php $currentUrl = current_url() . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : ''); ?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-clock-o"></i> Exam Timetable</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Exam Timetable</li>
        </ol>
    </div>

    <div class="box-body">
        <h5 class="page-header">
            <a href="<?=base_url('examtimetable/add?grade=' . (int) $filter['grade'] . '&exam=' . urlencode($filter['exam']) . '&back=' . urlencode($currentUrl))?>"><i class="fa fa-plus"></i> Add New Paper</a>
        </h5>

        <form class="form-inline" method="get" action="<?=base_url('examtimetable/index')?>" style="margin-bottom:10px;">
            <div class="form-group">
                <label>Exam</label>
                <select class="form-control" name="exam">
                    <option value="">All exams</option>
                    <?php foreach ($exams as $e) { ?>
                        <option value="<?=html_escape($e)?>" <?=$filter['exam'] === $e ? 'selected' : ''?>><?=html_escape($e)?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="form-group">
                <label>Grade</label>
                <select class="form-control" name="grade">
                    <option value="">All grades</option>
                    <?php foreach ($grades as $g) { ?>
                        <option value="<?=$g?>" <?=$filter['grade'] === $g ? 'selected' : ''?>>Grade <?=$g?></option>
                    <?php } ?>
                </select>
            </div>
            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Show</button>
            <a href="<?=base_url('examtimetable/index')?>" class="btn btn-link">Clear</a>
        </form>
        <p class="text-muted">One timetable per grade — every section (A/B/C) of a grade sees the same papers in the app.</p>

        <div id="hide-table">
            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                    <tr>
                        <th class="col-sm-1">#</th>
                        <th class="col-sm-2">Exam</th>
                        <th class="col-sm-2">Date</th>
                        <th class="col-sm-1">Day</th>
                        <th class="col-sm-1">Grade</th>
                        <th>Subject</th>
                        <th class="col-sm-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (customCompute($rows)) { $i = 1; foreach ($rows as $r) { ?>
                    <tr>
                        <td data-title="#"><?=$i++?></td>
                        <td data-title="Exam"><span class="label label-success"><?=html_escape($r->exam)?></span></td>
                        <td data-title="Date"><?=date('d M Y', strtotime($r->test_date))?></td>
                        <td data-title="Day"><?=ucfirst(strtolower(html_escape($r->day)))?></td>
                        <td data-title="Grade"><?=html_escape($r->class_name)?></td>
                        <td data-title="Subject"><?=html_escape($r->subject)?></td>
                        <td data-title="Action">
                            <a class="btn btn-warning btn-xs mrg" href="<?=base_url('examtimetable/edit/' . $r->id . '?back=' . urlencode($currentUrl))?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-danger btn-xs mrg" href="<?=base_url('examtimetable/delete/' . $r->id . '?back=' . urlencode($currentUrl))?>" onclick="return confirm('Delete this paper from the timetable?');" data-toggle="tooltip" title="Delete"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                <?php }} else { ?>
                    <tr><td colspan="7" class="text-center text-muted">No timetable rows for this selection.</td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

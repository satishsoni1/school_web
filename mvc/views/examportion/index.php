<?php $currentUrl = current_url() . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : ''); ?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-book"></i> Exam Portion</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Exam Portion</li>
        </ol>
    </div>

    <div class="box-body">
        <h5 class="page-header">
            <a href="<?=base_url('examportion/add?grade=' . (int) $filter['grade'] . '&exam=' . urlencode($filter['exam']) . '&back=' . urlencode($currentUrl))?>"><i class="fa fa-plus"></i> Add New Portion</a>
        </h5>

        <form class="form-inline" method="get" action="<?=base_url('examportion/index')?>" style="margin-bottom:10px;">
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
            <a href="<?=base_url('examportion/index')?>" class="btn btn-link">Clear</a>
        </form>
        <p class="text-muted">One portion per grade, exam and subject — every section (A/B/C) of a grade sees it in the app.</p>

        <div id="hide-table">
            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                <thead>
                    <tr>
                        <th class="col-sm-1">#</th>
                        <th class="col-sm-1">Exam</th>
                        <th class="col-sm-1">Grade</th>
                        <th class="col-sm-2">Subject</th>
                        <th>Portion</th>
                        <th class="col-sm-1">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (customCompute($rows)) { $i = 1; foreach ($rows as $r) { ?>
                    <tr>
                        <td data-title="#"><?=$i++?></td>
                        <td data-title="Exam"><span class="label label-success"><?=html_escape($r->exam)?></span></td>
                        <td data-title="Grade"><?=html_escape($r->class_name)?></td>
                        <td data-title="Subject"><b><?=html_escape($r->subject)?></b></td>
                        <td data-title="Portion"><?=nl2br(html_escape($r->syllabus))?></td>
                        <td data-title="Action">
                            <a class="btn btn-warning btn-xs mrg" href="<?=base_url('examportion/edit/' . $r->id . '?back=' . urlencode($currentUrl))?>" data-toggle="tooltip" title="Edit"><i class="fa fa-edit"></i></a>
                            <a class="btn btn-danger btn-xs mrg" href="<?=base_url('examportion/delete/' . $r->id . '?back=' . urlencode($currentUrl))?>" onclick="return confirm('Delete this portion?');" data-toggle="tooltip" title="Delete"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                <?php }} else { ?>
                    <tr><td colspan="6" class="text-center text-muted">No portion rows for this selection.</td></tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

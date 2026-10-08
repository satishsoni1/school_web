<?php
$isEdit = !empty($row);
// Posted value (failed validation) > saved value > query-string preset (Add from a filtered list).
$val = function ($field, $default = '') use ($row) {
    $posted = $this->input->post($field);
    if ($posted !== null) {
        return $posted;
    }
    if ($row && isset($row->$field)) {
        return $row->$field;
    }
    $preset = $this->input->get($field);
    return ($preset !== null && $preset !== '') ? $preset : $default;
};
$back = (string) $this->input->get('back');
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-book"></i> <?=$isEdit ? 'Edit Exam Portion' : 'Add New Portion'?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url('examportion/index')?>">Exam Portion</a></li>
            <li class="active"><?=$isEdit ? 'Edit' : 'Add'?></li>
        </ol>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-9">
                <form class="form-horizontal" method="post">
                    <input type="hidden" name="back" value="<?=html_escape($back)?>">

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Exam <span class="text-red">*</span></label>
                        <div class="col-sm-4">
                            <select class="form-control" name="exam" required>
                                <?php foreach ($exams as $e) { ?>
                                    <option value="<?=html_escape($e)?>" <?=$val('exam', 'PT-1') === $e ? 'selected' : ''?>><?=html_escape($e)?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Grade <span class="text-red">*</span></label>
                        <div class="col-sm-4">
                            <select class="form-control" name="grade" required>
                                <option value="">Select grade</option>
                                <?php foreach ($grades as $g) { ?>
                                    <option value="<?=$g?>" <?=(int) $val('grade') === $g ? 'selected' : ''?>>Grade <?=$g?></option>
                                <?php } ?>
                            </select>
                            <p class="help-block">Applies to every section of the grade (A/B/C).</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Subject <span class="text-red">*</span></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="subject" maxlength="50" value="<?=html_escape($val('subject'))?>" placeholder="e.g. ENGLISH" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Portion <span class="text-red">*</span></label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="syllabus" rows="8" placeholder="Lessons, poems, grammar and topics — one per line" required><?=html_escape($val('syllabus'))?></textarea>
                            <p class="help-block">Line breaks are kept in the app. Hindi / Marathi / Sanskrit text is supported.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?=$isEdit ? 'Update' : 'Add'?></button>
                            <?php if (!$isEdit) { ?>
                                <button type="submit" name="save_and_new" value="1" class="btn btn-primary"><i class="fa fa-plus"></i> Add &amp; next subject</button>
                            <?php } ?>
                            <a href="<?=html_escape($back !== '' ? $back : base_url('examportion/index'))?>" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

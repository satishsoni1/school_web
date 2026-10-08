<?php
$isEdit = !empty($slot);
// Posted value (failed validation) > saved slot > query-string preset (Add from a class/day).
$val = function ($field, $default = '') use ($slot) {
    $posted = $this->input->post($field);
    if ($posted !== null) {
        return $posted;
    }
    if ($slot && isset($slot->$field)) {
        return $slot->$field;
    }
    $preset = $this->input->get($field);
    return ($preset !== null && $preset !== '') ? $preset : $default;
};
$classesID = (int) $val('classesID');
$startVal  = $this->input->post('start_time') ?: ($slot ? Classtimetable_m::to_24h($slot->start_time) : (string) $this->input->get('start'));
$endVal    = $this->input->post('end_time') ?: ($slot ? Classtimetable_m::to_24h($slot->end_time) : '');
$backUrl   = base_url('classtimetable/index' . ($classesID ? '?classesID=' . $classesID : ''));
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-table"></i> <?=$isEdit ? 'Edit Slot' : 'Add Timetable Slot'?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url('classtimetable/index')?>">Class Timetable</a></li>
            <li class="active"><?=$isEdit ? 'Edit' : 'Add'?></li>
        </ol>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-8">
                <form class="form-horizontal" method="post">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Class <span class="text-red">*</span></label>
                        <div class="col-sm-5">
                            <select class="form-control" name="classesID" required>
                                <option value="">Select class</option>
                                <?php foreach ($classes as $c) { ?>
                                    <option value="<?=$c->classesID?>" <?=$classesID === (int) $c->classesID ? 'selected' : ''?>><?=html_escape($c->classes)?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Day <span class="text-red">*</span></label>
                        <div class="col-sm-5">
                            <select class="form-control" name="day" required>
                                <?php foreach ($days as $d) { ?>
                                    <option value="<?=$d?>" <?=strtoupper($val('day', 'MONDAY')) === $d ? 'selected' : ''?>><?=ucfirst(strtolower($d))?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Time <span class="text-red">*</span></label>
                        <div class="col-sm-3">
                            <input type="time" class="form-control" name="start_time" value="<?=html_escape($startVal)?>" required>
                            <p class="help-block">Start</p>
                        </div>
                        <div class="col-sm-3">
                            <input type="time" class="form-control" name="end_time" value="<?=html_escape($endVal)?>" required>
                            <p class="help-block">End</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Type <span class="text-red">*</span></label>
                        <div class="col-sm-5">
                            <?php foreach ($slotTypes as $key => $label) { ?>
                                <label class="radio-inline"><input type="radio" name="slot_type" value="<?=$key?>" <?=$val('slot_type', 'PERIOD') === $key ? 'checked' : ''?>> <?=$label?></label>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Subject <span class="text-red">*</span></label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="subject" maxlength="50" list="ppg-subjects" value="<?=html_escape($val('subject'))?>" placeholder="e.g. ENGLISH, CIRCLE TIME, ASSEMBLY, SHORT BREAK" required>
                            <datalist id="ppg-subjects">
                                <?php foreach (array('ASSEMBLY', 'SHORT BREAK', 'LONG BREAK', 'ENGLISH', 'HINDI', 'MARATHI', 'MATH', 'EVS', 'G.K.', 'ART', 'MUSIC', 'DANCE', 'YOGA', 'PT', 'COMPUTER', 'LIBRARY', 'STORY TIME', 'CIRCLE TIME', 'PLAY TIME', 'RHYMES', 'ACTIVITY', 'DISPERSAL') as $s) { ?>
                                    <option value="<?=$s?>">
                                <?php } ?>
                            </datalist>
                            <p class="help-block">For Assembly / breaks choose type "Break / Assembly". Periods are numbered automatically by start time.</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?=$isEdit ? 'Update' : 'Add'?></button>
                            <?php if (!$isEdit) { ?>
                                <button type="submit" name="save_and_new" value="1" class="btn btn-primary"><i class="fa fa-plus"></i> Add &amp; next slot</button>
                            <?php } ?>
                            <a href="<?=$backUrl?>" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

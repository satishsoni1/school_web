<?php
$isEdit = !empty($event);
// Re-show what was typed if validation failed, else the saved values.
$val = function ($field, $default = '') use ($event) {
    $posted = $this->input->post($field);
    if ($posted !== null) {
        return $posted;
    }
    return ($event && isset($event->$field)) ? $event->$field : $default;
};
$back = (string) $this->input->get('back');
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$isEdit ? 'Edit Event' : 'Add New Event'?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url('academicplanner/index')?>">Academic Planner</a></li>
            <li class="active"><?=$isEdit ? 'Edit' : 'Add'?></li>
        </ol>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-sm-8">
                <form class="form-horizontal" method="post">
                    <input type="hidden" name="back" value="<?=html_escape($back)?>">

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Date <span class="text-red">*</span></label>
                        <div class="col-sm-5">
                            <input type="date" class="form-control" name="event_date" value="<?=html_escape($val('event_date') ? date('Y-m-d', strtotime($val('event_date'))) : '')?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Title <span class="text-red">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="title" maxlength="255" value="<?=html_escape($val('title'))?>" placeholder="e.g. Ganesh Chaturthi" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Type <span class="text-red">*</span></label>
                        <div class="col-sm-5">
                            <select class="form-control" name="type">
                                <?php foreach ($types as $t) { ?>
                                    <option value="<?=$t?>" <?=$val('type', 'activity') === $t ? 'selected' : ''?>><?=ucfirst($t)?></option>
                                <?php } ?>
                            </select>
                            <p class="help-block">Holiday shows red in the app, Exam green, Test orange, Sports grey, Activity/Event blue.</p>
                        </div>
                    </div>

                    <?php if ($hasAudience) { ?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Planner <span class="text-red">*</span></label>
                        <div class="col-sm-5">
                            <select class="form-control" name="audience">
                                <?php foreach ($audiences as $key => $label) { ?>
                                    <option value="<?=$key?>" <?=$val('audience', 'grade') === $key ? 'selected' : ''?>><?=html_escape($label)?></option>
                                <?php } ?>
                                <?php if (!$isEdit) { ?><option value="both" <?=$val('audience') === 'both' ? 'selected' : ''?>>Both planners</option><?php } ?>
                            </select>
                            <?php if (!$isEdit) { ?><p class="help-block">"Both planners" adds the event to each.</p><?php } ?>
                        </div>
                    </div>
                    <?php } ?>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="description" rows="3" placeholder="Optional"><?=html_escape($val('description'))?></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-8">
                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> <?=$isEdit ? 'Update Event' : 'Add Event'?></button>
                            <a href="<?=html_escape($back !== '' ? $back : base_url('academicplanner/index'))?>" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

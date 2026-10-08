<?php
$isPrep = function ($name) {
    return (bool) preg_match('/nursery|prep|kg|pre[\s-]*primary/i', $name);
};
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-table"></i> Class Timetable<?= $class ? ' — ' . html_escape($class->classes) : ''; ?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url('dashboard/index')?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <?php if ($class) { ?>
                <li><a href="<?=base_url('classtimetable/index')?>">Class Timetable</a></li>
                <li class="active"><?=html_escape($class->classes)?></li>
            <?php } else { ?>
                <li class="active">Class Timetable</li>
            <?php } ?>
        </ol>
    </div>

    <div class="box-body">
    <?php if (!$class) { ?>
        <!-- ============ Class picker ============ -->
        <p class="text-muted">Daily timetable shown in the mobile app's <b>Timetable</b> page. Choose a class to view or edit its week.</p>
        <?php
        $groups = array('Pre-Primary' => array(), 'Grades' => array());
        foreach ($classes as $c) {
            $groups[$isPrep($c->classes) ? 'Pre-Primary' : 'Grades'][] = $c;
        }
        foreach ($groups as $label => $list) { if (!$list) { continue; } ?>
            <h5 class="page-header" style="margin-top:15px;"><?=$label?></h5>
            <div class="row">
            <?php foreach ($list as $c) { $n = isset($counts[$c->classesID]) ? (int) $counts[$c->classesID] : 0; ?>
                <div class="col-sm-3 col-xs-6" style="margin-bottom:12px;">
                    <a href="<?=base_url('classtimetable/index?classesID=' . $c->classesID)?>" class="btn btn-default btn-block" style="text-align:left; padding:10px 14px;">
                        <b><?=html_escape($c->classes)?></b><br>
                        <?php if ($n) { ?>
                            <small class="text-success"><i class="fa fa-check"></i> <?=$n?> slots</small>
                        <?php } else { ?>
                            <small class="text-danger"><i class="fa fa-exclamation-circle"></i> No timetable</small>
                        <?php } ?>
                    </a>
                </div>
            <?php } ?>
            </div>
        <?php } ?>

    <?php } else { ?>
        <!-- ============ One class's week ============ -->
        <div class="row" style="margin-bottom:10px;">
            <div class="col-sm-6">
                <a href="<?=base_url('classtimetable/add?classesID=' . $class->classesID)?>" class="btn btn-success"><i class="fa fa-plus"></i> Add Slot</a>
                <a href="<?=base_url('classtimetable/index')?>" class="btn btn-default"><i class="fa fa-th-large"></i> All classes</a>
            </div>
            <div class="col-sm-6">
                <form class="form-inline pull-right" method="post" action="<?=base_url('classtimetable/copy_class')?>"
                      onsubmit="return confirm('Replace the selected class\'s whole timetable with a copy of <?=html_escape($class->classes)?>?');">
                    <input type="hidden" name="classesID" value="<?=$class->classesID?>">
                    <label>Copy whole week to</label>
                    <select name="to_classesID" class="form-control input-sm" required>
                        <option value="">Select class</option>
                        <?php foreach ($classes as $c) { if ($c->classesID == $class->classesID) { continue; } ?>
                            <option value="<?=$c->classesID?>"><?=html_escape($c->classes)?><?=isset($counts[$c->classesID]) ? '' : ' (empty)'?></option>
                        <?php } ?>
                    </select>
                    <button type="submit" class="btn btn-default btn-sm"><i class="fa fa-copy"></i> Copy</button>
                </form>
            </div>
        </div>

        <?php foreach ($days as $day) { $slots = $week[$day]; $dayLabel = ucfirst(strtolower($day)); ?>
            <div class="box box-solid" id="day-<?=strtolower($day)?>" style="border:1px solid #e2e8f0; margin-bottom:15px;">
                <div class="box-header with-border" style="background:#f8fafc;">
                    <h3 class="box-title"><b><?=$dayLabel?></b> <small><?=count($slots)?> slots</small></h3>
                    <div class="box-tools pull-right">
                        <a href="<?=base_url('classtimetable/add?classesID=' . $class->classesID . '&day=' . $day)?>" class="btn btn-success btn-xs"><i class="fa fa-plus"></i> Add</a>
                        <?php if ($slots) { ?>
                            <button type="button" class="btn btn-default btn-xs" onclick="$('#copy-<?=$day?>').toggle();"><i class="fa fa-copy"></i> Copy to other days</button>
                            <form method="post" action="<?=base_url('classtimetable/clear_day')?>" style="display:inline;" onsubmit="return confirm('Remove all <?=$dayLabel?> slots for <?=html_escape($class->classes)?>?');">
                                <input type="hidden" name="classesID" value="<?=$class->classesID?>">
                                <input type="hidden" name="day" value="<?=$day?>">
                                <button type="submit" class="btn btn-danger btn-xs"><i class="fa fa-eraser"></i> Clear day</button>
                            </form>
                        <?php } ?>
                    </div>
                </div>

                <?php if ($slots) { ?>
                <div id="copy-<?=$day?>" style="display:none; padding:10px 15px; background:#fffbeb; border-bottom:1px solid #fde68a;">
                    <form method="post" action="<?=base_url('classtimetable/copy_day')?>" class="form-inline"
                          onsubmit="return confirm('The chosen days will be replaced with a copy of <?=$dayLabel?>. Continue?');">
                        <input type="hidden" name="classesID" value="<?=$class->classesID?>">
                        <input type="hidden" name="from_day" value="<?=$day?>">
                        Copy <b><?=$dayLabel?></b> to:
                        <?php foreach ($days as $d) { if ($d === $day) { continue; } ?>
                            <label style="font-weight:normal; margin:0 8px;"><input type="checkbox" name="to_days[]" value="<?=$d?>"> <?=ucfirst(strtolower($d))?></label>
                        <?php } ?>
                        <button type="submit" class="btn btn-warning btn-xs">Copy</button>
                    </form>
                </div>
                <?php } ?>

                <div class="box-body no-padding">
                    <table class="table table-condensed table-hover" style="margin:0;">
                        <?php if ($slots) { ?>
                        <thead><tr><th class="col-sm-1">#</th><th class="col-sm-3">Time</th><th>Subject</th><th class="col-sm-2">Type</th><th class="col-sm-1">Action</th></tr></thead>
                        <tbody>
                        <?php foreach ($slots as $s) { $isBreak = ($s->slot_type === 'BREAK'); ?>
                            <tr<?=$isBreak ? ' style="background:#f8fafc;"' : ''?>>
                                <td><?=$isBreak ? '—' : (int) $s->period_no?></td>
                                <td><?=html_escape($s->start_time)?> – <?=html_escape($s->end_time)?></td>
                                <td><?=$isBreak ? '<i>' . html_escape($s->subject) . '</i>' : '<b>' . html_escape($s->subject) . '</b>'?></td>
                                <td><span class="label label-<?=$isBreak ? 'default' : 'success'?>"><?=$isBreak ? 'Break' : 'Period'?></span></td>
                                <td>
                                    <a class="btn btn-warning btn-xs mrg" href="<?=base_url('classtimetable/edit/' . $s->id)?>" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a class="btn btn-danger btn-xs mrg" href="<?=base_url('classtimetable/delete/' . $s->id)?>" onclick="return confirm('Delete this slot?');" title="Delete"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                        <?php } else { ?>
                        <tbody><tr><td class="text-center text-muted" style="padding:14px;">No slots on <?=$dayLabel?>. <a href="<?=base_url('classtimetable/add?classesID=' . $class->classesID . '&day=' . $day)?>">Add the first slot</a></td></tr></tbody>
                        <?php } ?>
                    </table>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
    </div>
</div>

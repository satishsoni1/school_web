<?php
// Report card template per class: [label, add/edit action, view action].
$templates = array(
    'nursery' => array('Nursery/Primary', 'add_information',   'generate_report_1', array(1, 2, 12, 3, 17)),
    'grade12' => array('Grade 1-2',       'add_information_4', 'generate_report_4', array(4, 11, 23, 5, 13, 24)),
    'grade3'  => array('Grade 3',         'add_information_5', 'generate_report_5', array(6, 15)),
    'grade45' => array('Grade 4-5',       'add_information_5', 'generate_report_6', array(7, 8, 16, 20)),
);
$templateFor = function ($classesID) use ($templates) {
    foreach ($templates as $t) {
        if (in_array((int) $classesID, $t[3])) {
            return $t;
        }
    }
    return null;
};
?>
<?php if (!$isRunningYear) { ?>
<div class="alert alert-info" style="margin-bottom:10px;">
    <i class="fa fa-lock"></i> Past academic year — reports are view-only. Switch to the current year to create or edit.
</div>
<?php } ?>
<?php if(customCompute($students)) { ?>
<table class="table table-bordered table-striped table-condensed">
    <thead>
        <tr>
            <th>#</th>
            <th>Student</th>
            <th>Roll</th>
            <th>Section</th>
            <th>Report</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; foreach($students as $student) {
            // Template from the class being listed (that year's class), not the student's current class.
            $template = $templateFor($classesID);
            $hasReport = isset($savedReports[$student->srstudentID]);
        ?>
        <tr>
            <td><?= $i; ?></td>
            <td><?= $student->name; ?></td>
            <td><?= $student->roll; ?></td>
            <td><?= $student->section; ?></td>
            <td>
                <?php if ($hasReport) { ?>
                    <span class="label label-success">Saved</span>
                    <small class="text-muted" style="display:block;"><?= date('d M Y', strtotime($savedReports[$student->srstudentID])); ?></small>
                <?php } else { ?>
                    <span class="label label-default">Not created</span>
                <?php } ?>
            </td>
            <td>
                <?php if ($template) { ?>
                    <span style="display:block; margin:5px 0; font-weight:bold;"><?= $template[0]; ?></span>
                    <?php if ($isRunningYear) { ?>
                        <a href="<?= base_url('holisticreport/'.$template[1].'/'.$student->srstudentID.'/'.$classesID); ?>" class="btn btn-xs btn-primary">
                            <?= $hasReport ? 'Edit' : 'Create Report'; ?>
                        </a>
                    <?php } ?>
                    <?php if ($hasReport || $isRunningYear) { ?>
                        <a href="<?= base_url('holisticreport/'.$template[2].'/'.$student->srstudentID.'/'.$classesID.'/'.$schoolyearID); ?>" target="_blank" class="btn btn-xs btn-success">View Report</a>
                    <?php } ?>
                <?php } ?>
            </td>
        </tr>
        <?php $i++; } ?>
    </tbody>
</table>
<?php } else { ?>
<div class="alert alert-warning">No students found for selected filters.</div>
<?php } ?>

<?php if(customCompute($students)) { ?>
<table class="table table-bordered table-striped table-condensed">
    <thead>
        <tr>
            <th>#</th>
            <th>Student</th>
            <th>Roll</th>
            <th>Section</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php $i = 1; foreach($students as $student) { ?>
        <tr>
            <td><?= $i; ?></td>
            <td><?= $student->name; ?></td>
            <td><?= $student->roll; ?></td>
            <td><?= $student->section; ?></td>
            <td>
                <a href="<?= base_url('holisticreport/add_information/'.$student->srstudentID.'/'.$classesID); ?>" class="btn btn-xs btn-primary">Add / Edit</a>
                <a href="<?= base_url('holisticreport/generate_report_1/'.$student->srstudentID.'/'.$classesID); ?>" target="_blank" class="btn btn-xs btn-success">View Report</a>
            </td>
        </tr>
        <?php $i++; } ?>
    </tbody>
</table>
<?php } else { ?>
<div class="alert alert-warning">No students found for selected filters.</div>
<?php } ?>

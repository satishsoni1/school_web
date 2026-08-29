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
                
                <!-- for nursery and primary classses -->
                <?php if(in_array($student->classesID,[1,2,12,3,17])) { ?>
                    <span style="display:block; margin:5px 0; font-weight:bold;">Nursery/Primary</span>
                    <a href="<?= base_url('holisticreport/add_information/'.$student->srstudentID.'/'.$classesID); ?>" class="btn btn-xs btn-primary">Add / Edit</a>
                    <a href="<?= base_url('holisticreport/generate_report_1/'.$student->srstudentID.'/'.$classesID); ?>" target="_blank" class="btn btn-xs btn-success">View Report</a>
                <?php } else if(in_array($student->classesID,[4,11,23,5,13,24])) { ?>
                    <span style="display:block; margin:5px 0; font-weight:bold;">Grade 1-2</span>
                    <a href="<?= base_url('holisticreport/add_information_4/'.$student->srstudentID.'/'.$classesID); ?>" class="btn btn-xs btn-primary">Add / Edit</a>
                    <a href="<?= base_url('holisticreport/generate_report_4/'.$student->srstudentID.'/'.$classesID); ?>" target="_blank" class="btn btn-xs btn-success">View Report</a>
                <?php } else if(in_array($student->classesID,[6,15])) { ?>
                    <span style="display:block; margin:5px 0; font-weight:bold;">Grade 3</span>
                    <a href="<?= base_url('holisticreport/add_information_5/'.$student->srstudentID.'/'.$classesID); ?>" class="btn btn-xs btn-primary">Add / Edit</a>
                    <a href="<?= base_url('holisticreport/generate_report_5/'.$student->srstudentID.'/'.$classesID); ?>" target="_blank" class="btn btn-xs btn-success">View Report</a>
                <?php } else if(in_array($student->classesID,[7,8,16,20])) { ?>
                    <span style="display:block; margin:5px 0; font-weight:bold;">Grade 4-5</span>
                    <a href="<?= base_url('holisticreport/add_information_5/'.$student->srstudentID.'/'.$classesID); ?>" class="btn btn-xs btn-primary">Add / Edit</a>
                    <a href="<?= base_url('holisticreport/generate_report_6/'.$student->srstudentID.'/'.$classesID); ?>" target="_blank" class="btn btn-xs btn-success">View Report</a>
                <?php } ?>
            </td>
        </tr>
        <?php $i++; } ?>
    </tbody>
</table>
<?php } else { ?>
<div class="alert alert-warning">No students found for selected filters.</div>
<?php } ?>

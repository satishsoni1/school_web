<div class="card">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title"><i class="fa icon-student"></i> <?= $this->lang->line('panel_title') ?></h3>
            <h1 class="page-title">👨‍🎓 Student List</h1>
            <a href="<?= base_url('holisticstudent/add') ?>" class="btn btn-primary">+ Add New Student</a>
        </div><!-- /.box-header -->
        <!-- form start -->
        <div class="box-body">
            <?php if (empty($students)): ?>
                <div class="empty-state">
                    <div class="empty-icon">📚</div>
                    <p>No students added yet.</p>
                    <a href="<?= base_url('holisticstudent/add') ?>" class="btn btn-primary">Add First Student</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Student Name</th>
                                <th>Class</th>
                                <th>Section</th>
                                <th>Contact</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $i => $s): ?>
                                <tr>
                                    <td><?= $i + 1 ?></td>
                                    <td><strong><?= $s['student_name'] ?></strong></td>
                                    <td><span class="badge badge-blue"><?= $s['class'] ?></span></td>
                                    <td><?= $s['section'] ?></td>
                                    <td><?= $s['contact_number'] ?></td>
                                    <td class="actions">
                                        <!-- <a href="<?= base_url('holisticstudent/view/' . $s['id']) ?>" class="btn btn-sm btn-info" title="View">👁 View</a> -->
                                        <a href="<?= base_url('holisticstudent/edit/' . $s['id']) ?>" class="btn btn-sm btn-warning" title="Edit">✏️ Edit</a>
                                        <a href="<?= base_url('holisticresult/generate/' . $s['id']) ?>" class="btn btn-sm btn-success" title="Report" target="_blank">🧾 Report</a>
                                        <a href="<?= base_url('holisticstudent/delete/' . $s['id']) ?>" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Delete this student?')" title="Delete">🗑 Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
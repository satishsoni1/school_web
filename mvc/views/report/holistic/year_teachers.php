<div class="row">
    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Holistic Report — Class Teachers for <?= customCompute($schoolyear) ? html_escape($schoolyear->schoolyear) : 'year ' . (int) $schoolyearID; ?></h3>
            </div>
            <div class="box-body">
                <?php if ($applied) { ?>
                    <div class="alert alert-success"><i class="fa fa-check"></i> Applied. The teacher names and a copy of each signature are now saved in these report cards and will not change if the teacher master changes.</div>
                <?php } else { ?>
                    <div class="alert alert-info">
                        <i class="fa fa-eye"></i> Preview — nothing has been changed yet. Each class's saved report cards for this year will print the teachers below, with a <b>copy</b> of their signature from the teacher master.
                    </div>
                <?php } ?>

                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Teacher(s) printed on report</th>
                            <th>Signature</th>
                            <th>Reports</th>
                            <?php if ($applied) { ?><th>Updated</th><?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($plan as $row) { ?>
                        <tr>
                            <td><b><?= html_escape($row['class']); ?></b></td>
                            <td>
                                <?php foreach ($row['teachers'] as $t) { ?>
                                    <div><?= html_escape($t['name']); ?> <small class="text-muted">(#<?= (int) $t['teacherID']; ?> <?= html_escape($t['master']); ?>)</small></div>
                                <?php } ?>
                            </td>
                            <td>
                                <?php foreach ($row['teachers'] as $t) { ?>
                                    <div style="margin-bottom:4px;">
                                        <?php if ($t['missing']) { ?>
                                            <span class="label label-danger">file missing</span>
                                        <?php } else { ?>
                                            <img src="<?= base_url($t['copy'] ?: $t['source']); ?>" style="height:34px; border:1px solid #eee; background:#fff;" alt="">
                                            <?php if ($t['blank']) { ?><span class="label label-warning">blank — no signature uploaded</span><?php } ?>
                                        <?php } ?>
                                    </div>
                                <?php } ?>
                            </td>
                            <td><?= (int) $row['reports']; ?></td>
                            <?php if ($applied) { ?><td><?= (int) $row['updated']; ?><?= $row['created'] ? ' <small class="text-muted">(' . (int) $row['created'] . ' new)</small>' : ''; ?></td><?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>

                <?php if (!$applied && customCompute($plan)) { ?>
                    <form method="post" action="<?= base_url('holisticreport/year_teachers/' . (int) $schoolyearID); ?>"
                          onsubmit="return confirm('Save these teachers and signatures into all listed report cards?');">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Apply to report cards</button>
                        <a href="<?= base_url('holisticreport/index'); ?>" class="btn btn-default">Back</a>
                    </form>
                <?php } else { ?>
                    <a href="<?= base_url('holisticreport/index'); ?>" class="btn btn-default">Back to Holistic Report</a>
                <?php } ?>
                <p class="text-muted" style="margin-top:12px;">The class → teacher list is in <code>mvc/config/holistic_year_teachers.php</code>. To fix a signature, upload it on the teacher's profile, then apply again.</p>
            </div>
        </div>
    </div>
</div>

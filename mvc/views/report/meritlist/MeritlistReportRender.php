<div class="row">
    <div class="col-sm-12" style="margin-bottom: 10px;">
        <button class="btn btn-primary pull-right" onclick="printDiv('printableArea')">
            <i class="fa fa-print"></i> Print Extract
        </button>
    </div>
</div>

<div class="box" id="printableArea">
    <div class="box-header" style="background-color: #fff; text-align: center; border-bottom: 1px solid #eee; padding-bottom: 15px;">
        <h3 class="box-title" style="color: #000; font-weight: bold; font-size: 22px;">
            Final Merit List Extract
        </h3>
        <p style="font-size: 16px; margin-top: 5px;">
            Class: <strong><?= isset($classes[$classesID]) ? $classes[$classesID] : '' ?></strong>
            <?php if ($sectionID > 0): ?>
                | Section: <strong><?= isset($sections[$sectionID]) ? $sections[$sectionID] : '' ?></strong>
            <?php else: ?>
                | Section: <strong>All Sections</strong>
            <?php endif; ?>
        </p>
    </div>
    
    <div class="box-body" style="padding: 20px;">
        <table class="table table-bordered table-striped" style="width: 100%;">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th style="width: 8%; text-align: center;">Rank</th>
                    <th style="width: 10%; text-align: center;">Roll No</th>
                    <th style="width: 10%; text-align: center;">Photo</th>
                    <th>Student Name</th>
                    <th style="width: 15%; text-align: center;">Section</th>
                    <th style="width: 15%; text-align: center;">Grand Total</th>
                </tr>
            </thead>
            <tbody>
                <?php if(customCompute($merit_list)): ?>
                    <?php foreach($merit_list as $student): ?>
                        <tr>
                            <td class="text-center font-bold" style="vertical-align: middle; font-size: 16px;">
                                <?= $student['rank'] ?>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <?= $student['roll'] ?>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <img src="<?= pdfimagelink($student['photo']) ?>" style="width: 40px; height: 40px; border-radius: 50%;">
                            </td>
                            <td style="vertical-align: middle;">
                                <?= $student['name'] ?>
                            </td>
                            <td class="text-center" style="vertical-align: middle;">
                                <?= isset($sections[$student['sectionID']]) ? $sections[$student['sectionID']] : '' ?>
                            </td>
                            <td class="text-center font-bold" style="vertical-align: middle; font-size: 15px;">
                                <?= $student['total'] ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    
    // Reload to restore JS events after printing
    setTimeout(function() {
        location.reload();
    }, 500);
}
</script>
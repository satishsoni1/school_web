<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header" style="background-color: #fff; text-align: center;">
                <h3 class="box-title" style="color: #000;">
                    Merit List Report 
                    <br> Class: <?= isset($classes[$classesID]) ? $classes[$classesID] : '' ?>
                    <?= ($sectionID > 0) ? ' | Section: ' . $sections[$sectionID] : '' ?>
                </h3>
            </div>
            
            <div class="box-body" style="padding: 20px;">
                <table class="table table-bordered table-striped" style="width: 100%;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="width: 10%; text-align: center;">Rank</th>
                            <th style="width: 10%; text-align: center;">Roll No</th>
                            <th style="width: 10%; text-align: center;">Photo</th>
                            <th>Student Name</th>
                            <th style="width: 15%; text-align: center;">Total Marks</th>
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
                                    <td class="text-center">
                                        <img src="<?= pdfimagelink($student['photo']) ?>" style="width: 40px; height: 40px; border-radius: 50%;">
                                    </td>
                                    <td style="vertical-align: middle;">
                                        <?= $student['name'] ?>
                                    </td>
                                    <td class="text-center font-bold" style="vertical-align: middle;">
                                        <?= $student['total'] ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
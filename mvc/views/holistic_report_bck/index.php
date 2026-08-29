<div class="row">
    <div class="col-sm-12">
        <div class="box box-info">
            <div class="box-header with-border"><h3 class="box-title">Holistic Report</h3></div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <label>Class</label>
                        <select id="classesID" class="form-control">
                            <option value="0">Please Select Class</option>
                            <?php foreach($classes as $class) { ?>
                                <option value="<?= $class->classesID; ?>"><?= $class->classes; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <label>Section</label>
                        <select id="sectionID" class="form-control">
                            <option value="0">Please Select Section</option>
                        </select>
                    </div>
                    <div class="col-sm-4" style="margin-top:24px;">
                        <button id="loadStudents" class="btn btn-primary">Load Students</button>
                    </div>
                </div>
                <hr>
                <div id="studentListWrap"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
(function() {
    var base = "<?= base_url(); ?>";

    $('#classesID').on('change', function() {
        $.post(base + 'holisticreport/getSection', {classesID: $(this).val()}, function(html) {
            $('#sectionID').html(html);
        });
    });

    $('#loadStudents').on('click', function() {
        $.post(base + 'holisticreport/getStudentList', {
            classesID: $('#classesID').val(),
            sectionID: $('#sectionID').val()
        }, function(resp) {
            try {
                var data = JSON.parse(resp);
                $('#studentListWrap').html(data.render || '');
            } catch (e) {
                $('#studentListWrap').html('<div class="alert alert-danger">Failed to load student list.</div>');
            }
        });
    });
})();
</script>

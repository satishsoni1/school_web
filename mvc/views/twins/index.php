<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-print"></i> Twin/Sibling Invoice Printing</h3>
    </div>
    <div class="box-body">
        <form action="<?=base_url('twins/search')?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Class</label>
                        <select id="classesID" class="form-control select2">
                            <option value="0">Select Class</option>
                            <?php foreach($classes as $c) { echo "<option value='".$c->classesID."'>".$c->classes."</option>"; } ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Student</label>
                        <select name="studentID" id="studentID" class="form-control select2" required>
                            <option value="0">Select Student</option>
                        </select>
                    </div>
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-search"></i> Find Invoices
            </button>
        </form>
    </div>
</div>

<script>
$('.select2').select2();
$('#classesID').change(function() {
    var id = $(this).val();
    $.post("<?=base_url('twins/getStudent')?>", {classesID:id}, function(data){
        $('#studentID').html(data);
    });
});
</script>
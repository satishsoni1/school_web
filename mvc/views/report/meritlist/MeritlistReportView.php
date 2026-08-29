<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-trophy"></i> Merit List Report</h3>
        <ol class="breadcrumb">
            <li><a href="<?= base_url("dashboard/index") ?>"><i class="fa fa-laptop"></i> Dashboard</a></li>
            <li class="active">Merit List Report</li>
        </ol>
    </div><div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                
                <div class="form-group col-sm-4" id="classesDiv">
                    <label>Class <span class="text-red">*</span></label>
                    <?php
                        $classesArray['0'] = "Please Select";
                        if(customCompute($classes)) {
                            foreach ($classes as $class) {
                                $classesArray[$class->classesID] = $class->classes;
                            }
                        }
                        echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                    ?>
                </div>

                <div class="col-sm-12">
                    <button id="get_meritlistreport" class="btn btn-success" style="margin-top: 10px;">Get Report</button>
                </div>

            </div>
        </div></div></div><div id="load_meritlistreport"></div>

<script type="text/javascript">
    // Initialize Select2 for better dropdown styling
    $('.select2').select2();

    // AJAX to fetch sections when a class is selected
    $(document).on('change', '#classesID', function() {
        $('#load_meritlistreport').html('');
        var classesID = $(this).val();
        if(classesID == '0') {
            $('#sectionID').html('<option value="0">Please Select</option>');
            $('#sectionID').val('0');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('meritlistreport/getSection') ?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                    $('#sectionID').html(data);
                }
            });
        }
    });

    // Clear report div if inputs change
    $(document).on('change', '#sectionID, #examID', function() {
        $('#load_meritlistreport').html('');
    });

    // AJAX to submit the form and fetch the Merit List table
    $(document).on('click', '#get_meritlistreport', function() {
        var classesID = $('#classesID').val();
        var sectionID = $('#sectionID').val();
        var error     = 0;

        // Simple frontend validation
        if(classesID == 0 || classesID == null) {
            error++;
            toastr["error"]("The Class field is required.");
        }

        // If no errors, submit via AJAX
        if(error === 0) {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('meritlistreport/getMeritlistreport') ?>",
                data: {
                    "classesID" : classesID, 
                    "sectionID" : sectionID
                },
                dataType: "json",
                success: function(data) {
                    if(data.status === true) {
                        // Inject the rendered table into the div
                        $('#load_meritlistreport').html(data.render);
                    } else {
                        // Display backend validation errors using Toastr
                        $.each(data, function(index, value) {
                            if(index != 'status') {
                                toastr["error"](value);
                            }
                        });
                        $('#load_meritlistreport').html('');
                    }
                }
            });
        }
    });
</script>
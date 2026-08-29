
<div class="row">
    <div class="col-sm-6  col-md-offset-2 align-items-center justify-content-center mx-auto">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa icon-invoice"></i> Create Book Receipt</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form role="form" method="post" enctype="multipart/form-data" id="invoiceDataForm"> 

                    <div class="classesDiv form-group <?=form_error('classesID') ? 'has-error' : '' ?>" >
                        <label for="classesID">
                            <?=$this->lang->line("invoice_classesID")?> <span class="text-red">*</span>
                        </label>
                            <?php
                                $classesArray = array('0' => $this->lang->line("invoice_select_classes"));
                                if(customCompute($classes)) {
                                    foreach ($classes as $classa) {
                                        $classesArray[$classa->classesID] = $classa->classes;
                                    }
                                }
                                echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                            ?>
                        <span class="text-red">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <div class="studentDiv form-group <?=form_error('studentID') ? 'has-error' : '' ?>" >
                        <label for="studentID">
                            <?=$this->lang->line("invoice_studentID")?> <span class="text-red">*</span>
                        </label>
                            <?php
                                $studentArray = array('0' => $this->lang->line("invoice_all_student"));
                                if(customCompute($students)) {
                                    foreach ($students as $student) {
                                        // $studentArray[$student->studentID] = $student->name;
                                        $studentArray[$student->name] = $student->name;
                                    }
                                }
                                echo form_dropdown("studentID", $studentArray, set_value("studentID"), "id='studentID' class='form-control select2'");
                            ?>
                        <span class="text-red">
                            <?php echo form_error('studentID'); ?>
                        </span>
                    </div>

                    <div class="dateDiv form-group <?=form_error('date') ? 'has-error' : '' ?>" >
                        <label for="date">
                            <?=$this->lang->line("invoice_date")?> <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control" id="date" name="date" value="<?=set_value('date')?>" >
                        <span class="text-red">
                            <?php echo form_error('date'); ?>
                        </span>
                    </div>
                    <div class="amountDiv form-group <?=form_error('amount') ? 'has-error' : '' ?>" >
                        <label for="amount">
                            Amount <span class="text-red">*</span>
                        </label>
                            <input type="number" class="form-control" id="amount" name="amount" value="<?=set_value('amount')?>" >
                        <span class="text-red">
                            <?php echo form_error('amount'); ?>
                        </span>
                    </div>

                    <input id="addInvoiceButton" type="button" class="btn btn-success" value="<?=$this->lang->line("add_invoice")?>" >
                </form>
            </div>
        </div>
    </div>


</div>

<script type="text/javascript">
  $('.select2').select2();
    $('#date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
    });
    $(document).on('click', '#addInvoiceButton', function() {
        var error=0;;
        var field = {
            'classesID'           : $('#classesID').val(), 
            'studentID'           : $('#studentID').val(), 
            'date'                : $('#date').val(),
            'amount'            : $('#amount').val(), 
        };
        
        if(field['classesID'] === '0') {
            $('.classesDiv').addClass('has-error');
            error++;
        } else {
            $('.classesDiv').removeClass('has-error');
        }

        if(field['date'] === '') {
            $('.dateDiv').addClass('has-error');
            error++;
        } else {
            $('.dateDiv').removeClass('has-error');
        }

        if(field['amount'] != "") {
            $('.amountDiv').removeClass('has-error');
        }else{
            $('.amountDiv').addClass('has-error');
            error++;
        }

        if(error === 0) {
            $(this).attr('disabled', 'disabled');
            var formData = new FormData($('#invoiceDataForm')[0]);
            formData.append("editID", 0);
            makingPostDataPreviousofAjaxCall(formData);
        }
    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('bookinvoice/saveinvoice')?>",
            data: passData,
            async: true,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                errrorLoader(response);
            },
            cache: false,
            contentType: false,
            processData: false
        });
    }
    $('#classesID').change(function(event) {
        var classesID = $(this).val();

        if(classesID === '0') {
            $('#studentID').html('<option value="0"><?=$this->lang->line('invoice_all_student')?></option>');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('bookinvoice/getstudent')?>",
                data: {'classesID' : classesID},
                dataType: "html",
                success: function(data) {
                    $('#studentID').html(data);
                }
            });
        }
    });
    function errrorLoader(response) {
        if(response.status) {
            window.location = "<?=base_url("bookinvoice/index")?>";
        } else {
            $('#addInvoiceButton').removeAttr('disabled');
            $.each(response.error, function(index, val) {
                toastr["error"](val)
                toastr.options = {
                    "closeButton": true,
                    "debug": false,
                    "newestOnTop": false,
                    "progressBar": false,
                    "positionClass": "toast-top-right",
                    "preventDuplicates": false,
                    "onclick": null,
                    "showDuration": "500",
                    "hideDuration": "500",
                    "timeOut": "5000",
                    "extendedTimeOut": "1000",
                    "showEasing": "swing",
                    "hideEasing": "linear",
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                }
            });
        }
    }

</script>


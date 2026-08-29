<?php if ($siteinfos->note==1) { ?>
    <div class="callout callout-danger">
        <p><b>Note:</b> Create exam, class, section & subject before add mark</p>
    </div>
<?php } ?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> Teacher Input</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("markteacherinput/index")?>">Teacher Input</a></li>
            <li class="active">Add Teacher Input</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <form method="POST">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                
                                <div class="col-md-3">
                                    <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="classesID" class="control-label">
                                            <?=$this->lang->line('mark_classes')?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                            $array = array("0" => $this->lang->line("mark_select_classes"));
                                            foreach ($classes as $classa) {
                                                $array[$classa->classesID] = $classa->classes;
                                            }
                                            echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2 classesID'");
                                        ?>
                                    </div>
                                </div>
                                

                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group" >
                                        <button type="submit" class="btn btn-success col-md-12 col-xs-12" style="margin-top: 20px;">Add Teacher Input</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>


                <?php if(customCompute($sendClasses)) { ?>
                    <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                        <?php
                            echo '<h5><center>Attendance Details</center></h5>';
                            echo '<h5><center>'.$this->lang->line('mark_classes').' : '. $sendClasses->classes.'</center></h5>';
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12">
                <?php if(customCompute($students)) { ?>
                    <div id="hide-table">
                    <!-- <div class="col-md-12 col-xs-12">
                            <div class="row">
                                <div class="col-md-4 col-xs-4">
                                    <div class="form-group" >
                                    <label class="control-label">Total Days <span class="text-red">*</span></label>
                                         <input class='form-control' type='number' name='total_days' value='' min='0' max='1000' />
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-4">
                                    <div class="form-group" >
                                    <label class="control-label">School Re-opens on <span class="text-red">*</span></label>
                                         <input class='form-control' type='text' id="reopen_date" name='reopen_date' value=''/>
                                    </div>
                                </div>
                                <div class="col-md-4 col-xs-4">
                                    <div class="form-group" >
                                    <label class="control-label">Date <span class="text-red">*</span></label>
                                         <input class='form-control' id="date" type='text' name='date' value=''/>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    
                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('slno')?></th>
                                    <th><?=$this->lang->line('mark_photo')?></th>
                                    <th><?=$this->lang->line('mark_name')?></th>
                                    <th><?=$this->lang->line('mark_roll')?></th>
                                    <!-- <th>Present Days</th> -->
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                
                                if(customCompute($students)) {$i = 1; foreach($students as $student) { 
                                    
                                    ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_photo')?>">
                                            <?=profileproimage($student->photo)?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_name')?>">
                                            <?php echo $student->name; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('mark_roll')?>">
                                            <?php echo $student->roll; ?>
                                        </td>
                                        <!-- <td data-title='attendance'> -->
                                        <input class='form-control' type='hidden' name='attendance_<?= $student->studentID ?>' studentID='<?= $student->studentID ?>' value=<?= isset($markPluck[$student->studentID])?$markPluck[$student->studentID]->attendance:'' ?> min='0' max='1000' />
                                        <!-- </td> -->
                                        <td data-title='remarks'>
                                        <input class='form-control' type='text' name='remark_<?= $student->studentID ?>' studentID='<?= $student->studentID ?>' value="<?= isset($markPluck[$student->studentID])?$markPluck[$student->studentID]->remarks:'' ?>" />
                                        </td>

                                    </tr>
                                <?php $i++;  }} ?>
                            </tbody>
                        </table>
                    </div>
                    <input type="button" class="btn btn-success col-sm-2 col-xs-12" id="add_mark" name="add_mark" value="Add Teacher Input" />

                    <script type="text/javascript">
                        $(function(){
                         $('#date').datepicker({
                                autoclose: true,
                                format: 'dd-mm-yyyy',
                                startDate:'<?=$schoolyearsessionobj->startingdate?>',
                                endDate:'<?=$schoolyearsessionobj->endingdate?>',
                            });
                        $('#reopen_date').datepicker({
                                autoclose: true,
                                format: 'dd-mm-yyyy',
                                startDate:'<?=$schoolyearsessionobj->startingdate?>',
                                endDate:'<?=$schoolyearsessionobj->endingdate?>',
                            });
                        });
                        window.addEventListener('load', function() {
                            setTimeout(lazyLoad, 1000);
                        });

                        function lazyLoad() {
                            var card_images = document.querySelectorAll('.card-image');
                            card_images.forEach(function(card_image) {
                                var image_url = card_image.getAttribute('data-image-full');
                                var content_image = card_image.querySelector('img');
                                content_image.src = image_url;
                                content_image.addEventListener('load', function() {
                                    card_image.style.backgroundImage = 'url(' + image_url + ')';
                                    card_image.className = card_image.className + ' is-loaded';
                                });
                            });
                        }
                        
                        $(document).on("keyup", ".mark", function() {
                            if(parseInt($(this).val())) {
                                var val = parseInt($(this).val());
                                var minMark = parseInt($(this).attr('min'));
                                var maxMark = parseInt($(this).attr('max'));
                                if(minMark > val || val > maxMark) {
                                    $(this).val('');
                                }
                            } else {
                                if($(this).val() == '0') {
                                } else {
                                    $(this).val('');
                                }
                            }
                        });

                        $("#add_mark").click(function() {
                            var inputs = "";
                            var inputs_value = "";
                            var studentinfo = $('input[name^=attendance]').map(function(){
                                var student_id = $(this).attr("studentID");
                                return { studentID:student_id , attendance: this.value,remarks:$('input[name=remark_'+student_id+']').val(),total_days:$('#total_days').val(),reopen_date:$('#reopen_date').val(),date:$('#date').val()};
                            }).get();

                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('markteacherinput/mark_send')?>",
                                data: {"classesID" : "<?=$set_classes?>", "studentinfo" : studentinfo},
                                dataType: "html",
                                success: function(data) {
                                    var response = jQuery.parseJSON(data);
                                    if(response.status) {
                                        toastr["success"](response.message)
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
                                    } else {
                                        if(response.inputs) {
                                            toastr["error"](response.inputs)
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
                                        }
                                    }
                                }
                            });
                        });
                    </script>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    $('.select2').select2();
    $("#classesID").change(function() {
        var classesID = $(this).val();
        if(parseInt(classesID)) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('markteacherinput/examcall')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#examID').html(data);
                }
            });
        }
    });

</script>

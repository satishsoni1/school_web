<style>
    .mark-input { min-width: 60px; max-width: 80px; padding: 4px; }
    .grade-select { min-width: 70px; padding: 4px; }
    
    /* 1. Add max-height to enable vertical scrolling */
    .table-container { 
        max-height: 65vh; /* Takes up 65% of the screen height */
        overflow: auto; /* Enables both X and Y scrolling */
        white-space: nowrap; 
    }
    
    /* 2. Freeze all Header Cells */
    thead th {
        position: -webkit-sticky;
        position: sticky;
        z-index: 10;
        background-color: #fff; /* Solid background prevents text bleeding underneath */
        /* Use inset box-shadow instead of standard borders for cleaner sticky rendering */
        box-shadow: inset 0 -1px 0 #ddd, inset -1px 0 0 #ddd; 
    }
    
    /* 3. First Row Header (Subjects) sticks to the very top */
    thead tr:nth-child(1) th {
        top: 0;
    }
    
    /* Maintain Bootstrap's bg-primary color on the sticky subject headers */
    thead tr:nth-child(1) th.bg-primary {
        background-color: #337ab7; /* Change this hex code if your theme's primary color is different */
        color: white;
    }

    /* 4. Second Row Header (PT1, PT2, etc.) sticks below the first row */
    thead tr:nth-child(2) th {
        /* IMPORTANT: If there is a gap or overlap, adjust this px value to match the exact height of your first row */
        top: 42px; 
    }

    /* 5. Freeze the First Column (Student Name) */
    .sticky-col {
        position: -webkit-sticky;
        position: sticky;
        left: 0;
        background-color: #f9f9f9;
        z-index: 11; /* Keep above scrolling data cells */
        box-shadow: 2px 0px 5px rgba(0,0,0,0.1);
    }
    
    /* 6. Top-Left Corner (Student Name Header) */
    /* Intersection of frozen header & frozen column needs the highest z-index */
    thead th.sticky-col { 
        z-index: 15; 
        background-color: #fff; 
        top: 0; 
    }
</style>
<?php if ($siteinfos->note==1) { ?>
<div class="callout callout-danger">
    <p><b>Note:</b> Create exam, class, section & subject before add mark</p>
</div>
<?php } ?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i>
                    <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("mark/index")?>"><?=$this->lang->line('menu_mark')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_mark')?></li>
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
                                    <div
                                        class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label for="classesID" class="control-label">
                                            <?=$this->lang->line('mark_classes')?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                            $array = array("0" => $this->lang->line("mark_select_classes"));
                                            foreach ($classes as $classa) {
                                                if ($classa->classesID != 21)
                                                    $array[$classa->classesID] = $classa->classes;
                                            }
                                            echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2 classesID'");
                                        ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div
                                        class="<?php echo form_error('examID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label for="examID" class="control-label">
                                            <?=$this->lang->line('mark_exam')?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                            $array = array("0" => $this->lang->line("mark_select_exam"));
                                            foreach ($exams as $exam) {
                                                $array[$exam->examID] = $exam->exam;
                                            }
                                            echo form_dropdown("examID", $array, set_value("examID"), "id='examID' class='form-control select2 examID'");
                                        ?>
                                    </div>
                                </div>

                                <!-- <div class="col-md-3">
                                    <div
                                        class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label class="control-label"><?=$this->lang->line('mark_section')?> <span
                                                class="text-red">*</span></label>
                                        <?php
                                            $arraysection = array('0' => $this->lang->line("mark_select_section"));
                                            if(customCompute($sections)) {
                                                foreach ($sections as $section) {
                                                    $arraysection[$section->sectionID] = $section->section;
                                                }
                                            }
                                            echo form_dropdown("sectionID", $arraysection, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div> -->
                                <div class="col-md-3">
                                    <div
                                        class="<?php echo form_error('subjectID') ? 'form-group has-error' : 'form-group'; ?>">
                                        <label for="subjectID" class="control-label">
                                            <?=$this->lang->line('mark_subject')?> <span class="text-red">*</span>
                                        </label>
                                        <?php
                                            $subjectArray = array("0" => $this->lang->line("mark_select_subject"));
                                            if(customCompute($subjects)) {
                                                foreach ($subjects as $subject) {
                                                    $subjectArray[$subject->subjectID] = $subject->subject;
                                                }
                                            }
                                            echo form_dropdown("subjectID", $subjectArray, set_value("subjectID"), "id='subjectID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success col-md-12 col-xs-12"
                                            style="margin-top: 20px;"><?=$this->lang->line('add_mark')?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>


                <?php if(customCompute($sendExam) && customCompute($sendClasses) && customCompute($sendSection) && customCompute($sendSubject)) { ?>
                <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                    <?php
                            echo '<h5><center>'.$this->lang->line('mark_details').'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('mark_exam').' : '.$sendExam->exam.'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('mark_classes').' : '. $sendClasses->classes.'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('mark_section').' : '. $sendSection->section.'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('mark_subject').' : '. $sendSubject->subject.'</center></h5>';
                        ?>
                </div>
                <?php } ?>
            </div>
            <div class="col-sm-12">
                <?php if(customCompute($students)) { ?>
                <div id="hide-table" class="table-container">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('mark_photo')?></th>
                                <th><?=$this->lang->line('mark_name')?></th>
                                <th><?=$this->lang->line('mark_roll')?></th>
                                <?php
                                        foreach ($markpercentages as $data) {
                                            echo "<th>$data->markpercentagetype ($data->percentage)</th>";
                                        }
                                    ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(customCompute($students)) {$i = 1; foreach($students as $student) { foreach ($marks as $mark) { if($student->studentID == $mark->studentID) {   ?>
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
                                <?php
                                        
                                            foreach ($markpercentages as $data) {
                                                echo "<td data-title='$data->markpercentagetype'>";
                                                    echo "<input class='form-control mark' type='text' name='mark-".$markwr[$student->studentID][$data->markpercentageID]."' id='".$data->markpercentageID."' value='".$markRelations[$student->studentID][$data->markpercentageID]."'/>";
                                                echo "</td>";
                                            }
                                        ?>

                            </tr>
                            <?php $i++;  }}}} ?>
                        </tbody>
                    </table>
                </div>
                <input type="button" class="btn btn-success col-sm-2 col-xs-12" id="add_mark" name="add_mark"
                    value="<?=$this->lang->line("add_sub_mark")?>" />

                <script type="text/javascript">
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
                    // if (parseInt($(this).val())) {
                    //     var val = parseInt($(this).val());
                    //     var minMark = parseInt($(this).attr('min'));
                    //     var maxMark = parseInt($(this).attr('max'));
                    //     if (minMark > val || val > maxMark) {
                    //         $(this).val('');
                    //     }
                    // } else {
                    //     if ($(this).val() == '0') {} else {
                    //         $(this).val('');
                    //     }
                    // }
                });

                $("#add_mark").click(function() {
                    var inputs = "";
                    var inputs_value = "";
                    var mark = $('input[name^=mark]').map(function() {
                        return {
                            markpercentageid: this.id,
                            mark: this.name,
                            value: this.value
                        };
                    }).get();

                    $.ajax({
                        type: 'POST',
                        url: "<?=base_url('mark/mark_send')?>",
                        data: {
                            "examID": "<?=$set_exam?>",
                            "classesID": "<?=$set_classes?>",
                            "subjectID": "<?=$set_subject?>",
                            "inputs": mark
                        },
                        dataType: "html",
                        success: function(data) {
                            var response = jQuery.parseJSON(data);
                            if (response.status) {
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
                                if (response.inputs) {
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
    if (parseInt(classesID)) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('mark/examcall')?>",
            data: {
                "classesID": classesID
            },
            dataType: "html",
            success: function(data) {
                $('#examID').html(data);
            }
        });

        $.ajax({
            type: 'POST',
            url: "<?=base_url('mark/subjectcall')?>",
            data: {
                "id": classesID
            },
            dataType: "html",
            success: function(data) {
                $('#subjectID').html(data);
            }
        });

        /*$.ajax({
            type: 'POST',
            url: "<?=base_url('mark/sectioncall')?>",
            data: {
                "id": classesID
            },
            dataType: "html",
            success: function(data) {
                $('#sectionID').html(data);
            }
        });*/
    }
});
</script>
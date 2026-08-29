<?php if (isset($siteinfos->note) && $siteinfos->note == 1) { ?>
    <div class="callout callout-danger" style="border-radius: 4px; margin-bottom: 20px;">
        <p><b>Note:</b> There are two types of attendance, day wise and class wise. You can select your institute attendance system in <a href="<?=base_url('setting')?>" class="text-blue" style="font-weight: bold; text-decoration: underline;">settings.</a></p>
    </div>
<?php } ?>

<div class="box box-primary" style="border-top: 3px solid #3c8dbc; box-shadow: 0 1px 3px rgba(0,0,0,0.15); border-radius: 4px;">
    <div class="box-header" style="border-bottom: 1px solid #f4f4f4; padding: 15px 20px;">
        <h3 class="box-title" style="font-size: 19px; font-weight: 600; color: #444;"><i class="fa icon-sattendance" style="margin-right: 8px; color: #3c8dbc;"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb" style="background: transparent; margin-bottom: 0; padding: 0; margin-top: 4px;">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("sattendance/index")?>"><?=$this->lang->line('menu_sattendance')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_sattendance')?></li>
        </ol>
    </div><!-- /.box-header -->
    
    <div class="box-body" style="padding: 25px 20px;">
        <div class="row">
            <div class="col-sm-12">
                
                <!-- Attendance Parameter Filter Form -->
                <form method="POST" style="background: #fdfdfd; border: 1px solid #e9e9e9; padding: 20px; border-radius: 6px; margin-bottom: 30px;">
                    <div class="row" style="display: flex; flex-wrap: wrap; align-items: flex-end;">
                        
                        <div class="col-md-3 col-sm-6">
                            <div class="form-group <?=form_error('classesID') ? 'has-error' : ''?>" style="margin-bottom: 15px;">
                                <label class="control-label" style="font-weight: 600; color: #555; margin-bottom: 7px;"><?=$this->lang->line('attendance_classes')?> <span class="text-red">*</span></label>
                                <?php
                                    $classArray = array("0" => $this->lang->line("attendance_select_classes"));
                                    if(customCompute($classes)) {
                                        foreach ($classes as $classa) {
                                            $classArray[$classa->classesID] = $classa->classes;
                                        }
                                    }
                                    echo form_dropdown("classesID", $classArray, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                                ?>
                            </div>
                        </div>

                        <?php if($setting->attendance == "subject"){ ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="form-group <?=form_error('subjectID') ? 'has-error' : ''?>" style="margin-bottom: 15px;">
                                    <label class="control-label" style="font-weight: 600; color: #555; margin-bottom: 7px;"><?=$this->lang->line('attendance_subject')?> <span class="text-red">*</span></label>
                                    <?php
                                        $subjectArray = array('0' => $this->lang->line("attendance_select_subject"));
                                        if(customCompute($subjects)) {
                                            foreach ($subjects as $subject) {
                                                $subjectArray[$subject->subjectID] = $subject->subject;
                                            }
                                        }
                                        echo form_dropdown("subjectID", $subjectArray, set_value("subjectID", $subjectID), "id='subjectID' class='form-control select2'");
                                    ?>
                                </div>
                            </div>
                        <?php } ?>

                        <div class="col-md-3 col-sm-6">
                            <div class="form-group <?=form_error('date') ? 'has-error' : ''?>" style="margin-bottom: 15px;">
                                <label class="control-label" style="font-weight: 600; color: #555; margin-bottom: 7px;"><?=$this->lang->line('attendance_date')?> <span class="text-red">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-addon" style="background: #f4f4f4; border-color: #d2d6de;"><i class="fa fa-calendar"></i></div>
                                    <input type="text" class="form-control" name="date" id="date" value="<?=set_value("date", $date)?>" style="border-radius: 0 4px 4px 0;">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-3 col-sm-6" style="margin-bottom: 15px; display: flex; gap: 8px;">
                            <button type="submit" class="btn btn-primary" style="flex: 1; font-weight: 600; height: 40px; box-shadow: 0 2px 4px rgba(60,141,188,0.2);"><i class="fa fa-search"></i> Load Roster</button>
                            <?php if(customCompute($students) && ($setting->attendance == "subject" || $usertypeID == 1)) { ?>
                                <button type="button" class="btn btn-danger" id="clear_day_attendance" title="Clear All Attendance" style="height: 40px; width: 42px; padding: 0;"><i class="fa fa-trash-o" style="font-size: 16px;"></i></button>
                            <?php } ?>
                        </div>

                    </div>
                </form>

                <!-- Active Filter Context Summary Card -->
                <?php if(customCompute($sattendanceinfo)) { ?>
                    <div class="box-layout-fame" style="background: #edf4f8; border-left: 4px solid #3c8dbc; padding: 15px 25px; border-radius: 0 6px 6px 0; margin-bottom: 30px; display: flex; flex-wrap: wrap; gap: 25px; justify-content: space-between; align-items: center;">
                        <h4 style="margin: 0; font-size: 15px; font-weight: 700; color: #2b546e; text-transform: uppercase; letter-spacing: 0.5px;"><i class="fa fa-info-circle"></i> <?=$this->lang->line('attendance_details')?></h4>
                        <div style="display: flex; flex-wrap: wrap; gap: 20px; color: #4e6e82; font-size: 14px;">
                            <span><strong><?=$this->lang->line('attendance_classes')?>:</strong> <span class="label label-info" style="font-size:12px;"><?=$sattendanceinfo['class']?></span></span>
                            <?php if($setting->attendance == "subject") { ?>
                                <span><strong><?=$this->lang->line('attendance_subject')?>:</strong> <span class="label label-warning" style="font-size:12px;"><?=$sattendanceinfo['subject']?></span></span>
                            <?php } ?>
                            <span><strong>Day:</strong> <?=$sattendanceinfo['day']?></span>
                            <span><strong>Date:</strong> <span class="text-primary" style="font-weight:600;"><?=$sattendanceinfo['date']?></span></span>
                        </div>
                    </div>
                <?php } ?>

            </div>

            <!-- Student Records Selection Area -->
            <div class="col-sm-12">
                <?php if(customCompute($students)) { ?>
                <div id="hide-table" style="box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-radius: 6px; overflow: hidden; margin-bottom: 25px;">
                    <table class="table table-hover" style="margin-bottom: 0; background: #fff;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 2px solid #eef2f5; color: #64748b;">
                                <th style="width: 70px; text-align: center; padding: 12px;"><?=$this->lang->line('slno')?></th>
                                <th style="width: 90px; text-align: center; padding: 12px;"><?=$this->lang->line('attendance_photo')?></th>
                                <th style="padding: 12px;"><?=$this->lang->line('attendance_name')?></th>
                                <th style="padding: 12px;"><?=$this->lang->line('attendance_email')?></th>
                                <th style="width: 100px; text-align: center; padding: 12px;"><?=$this->lang->line('attendance_roll')?></th>
                                <th style="width: 280px; text-align: center; padding: 12px;"><?=$this->lang->line('attendance_attendance')?></th>
                            </tr>
                        </thead>
                        <tbody id="list" style="color: #334155;">
                            <?php $i = 1; foreach($students as $student) { if(isset($attendances[$student->studentID])) { ?>
                                <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.15s ease;">
                                    <td style="text-align: center; vertical-align: middle; font-weight: bold; color: #94a3b8;" data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                    <td style="text-align: center; vertical-align: middle;" data-title="<?=$this->lang->line('attendance_photo')?>">
                                        <div style="width: 42px; height: 42px; border-radius: 50%; overflow: hidden; display: inline-block; border: 2px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                                            <?=profileproimage($student->photo)?>
                                        </div>
                                    </td>
                                    <td style="vertical-align: middle; font-weight: 600; color: #1e293b;" data-title="<?=$this->lang->line('attendance_name')?>"><?=$student->srname?></td>
                                    <td style="vertical-align: middle; color: #64748b;" data-title="<?=$this->lang->line('attendance_email')?>"><?=$student->email?></td>
                                    <td style="text-align: center; vertical-align: middle; font-weight: 600;" data-title="<?=$this->lang->line('attendance_roll')?>"><span class="badge" style="background: #64748b; padding: 5px 10px; border-radius: 4px;"><?=$student->srroll?></span></td>
                                    <td style="text-align: center; vertical-align: middle;" class="studentID" data-studentid="<?=$student->studentID?>" data-title="<?=$this->lang->line('attendance_attendance')?>">
                                        <?php
                                            $aday = "a".abs($day);
                                            if(isset($attendances[$student->studentID])) {
                                                $isValidRow = false;
                                                if($setting->attendance == "subject") {
                                                    if($monthyear == $attendances[$student->studentID]->monthyear && $attendances[$student->studentID]->studentID == $student->srstudentID && $attendances[$student->studentID]->classesID == $student->srclassesID && $attendances[$student->studentID]->subjectID == $subjectID) {
                                                        $isValidRow = true;
                                                    }
                                                } else {
                                                    if ($monthyear == $attendances[$student->studentID]->monthyear && $attendances[$student->studentID]->studentID == $student->srstudentID && $attendances[$student->studentID]->classesID == $student->srclassesID) {
                                                        $isValidRow = true;
                                                    }
                                                }

                                                if($isValidRow) {
                                                    $pmethod = ($attendances[$student->studentID]->$aday == "P") ? "checked" : "";
                                                    $amethod = ($attendances[$student->studentID]->$aday == "A" || empty($attendances[$student->studentID]->$aday)) ? "checked" : "";
                                                    
                                                    // Dynamic interactive segmented button controls instead of default plain markup radio lists
                                                    echo '<div class="btn-group attendance-toggle-group" data-toggle="buttons" style="display: inline-flex; background: #f1f5f9; padding: 4px; border-radius: 6px; border: 1px solid #e2e8f0;">';
                                                    
                                                    echo '<label class="btn btn-default '.($pmethod ? 'active btn-success' : '').'" style="border: 0 !important; font-weight: bold; padding: 6px 20px; transition: all 0.2s; border-radius: 4px !important; margin: 0 2px;">';
                                                    echo '<input type="radio" class="attendance present" name="attendance'.$attendances[$student->studentID]->attendanceID.'" value="P" '.$pmethod.' style="position: absolute; clip: rect(0,0,0,0); visibility: hidden;"> '.$this->lang->line('sattendance_present');
                                                    echo '</label>';
                                                    
                                                    echo '<label class="btn btn-default '.($amethod ? 'active btn-danger' : '').'" style="border: 0 !important; font-weight: bold; padding: 6px 20px; transition: all 0.2s; border-radius: 4px !important; margin: 0 2px;">';
                                                    echo '<input type="radio" class="attendance absent" name="attendance'.$attendances[$student->studentID]->attendanceID.'" value="A" '.$amethod.' style="position: absolute; clip: rect(0,0,0,0); visibility: hidden;"> '.$this->lang->line('sattendance_absent');
                                                    echo '</label>';
                                                    
                                                    echo '</div>';
                                                }
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

                <div class="row" style="margin-top: 25px; margin-bottom: 15px;">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-success btn-lg pull-right save_attendance" style="padding: 10px 35px; font-weight: bold; font-size: 15px; box-shadow: 0 4px 6px rgba(40,167,69,0.25); border-radius: 5px;"><i class="fa fa-save"></i> <?=$this->lang->line('sattendance_submit')?></button>
                    </div>
                </div>
                <?php } ?>
            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<style type="text/css">
    /* Table hover row background enhancement */
    .table-hover tbody tr:hover { background-color: #f8fafc !important; }
    /* Beautiful circular images adjustments */
    #hide-table img { width: 100%; height: 100%; object-fit: cover; }
    /* Custom design segment overrides for state changes */
    .attendance-toggle-group .btn-success, .attendance-toggle-group .btn-success:hover { background-color: #2ec4b6 !important; color: #fff !important; box-shadow: 0 2px 4px rgba(46,196,182,0.2); }
    .attendance-toggle-group .btn-danger, .attendance-toggle-group .btn-danger:hover { background-color: #e63946 !important; color: #fff !important; box-shadow: 0 2px 4px rgba(230,57,70,0.2); }
    .attendance-toggle-group .btn-default { background: transparent; color: #64748b; border: none; }
    .attendance-toggle-group .btn-default:hover { background: #e2e8f0; color: #334155; }
</style>

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

    // Interactive button switcher visualization triggers
    $(document).on('change', '.attendance-toggle-group input[type="radio"]', function() {
        var group = $(this).closest('.attendance-toggle-group');
        group.find('label').removeClass('active btn-success btn-danger');
        
        if($(this).val() == 'P') {
            $(this).closest('label').addClass('active btn-success');
        } else {
            $(this).closest('label').addClass('active btn-danger');
        }
    });

    $('.save_attendance').click(function(){
        var attendance = {};
        $('.attendance').each(function(i){
            var name = $(this).attr('name');
            if($("input:radio[name="+name+"]").is(":checked")) {
                var val = $('input:radio[name='+name+']:checked').val();
            } else {
                var val = 'A';
            }
            attendance[name] = val;
        });

        var day = "<?=$day?>";
        var classes = "<?=$set?>";
        var monthyear = "<?=$monthyear?>";
        var subjectID = "<?=($setting->attendance == 'subject') ? $subjectID : 0?>";

        if(parseInt(classes) && parseInt(day)) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('sattendance/save_attendace')?>",
                data: {"day" : day, "classes" : classes,  "subject" : subjectID , "monthyear" : monthyear , "attendance" : attendance },
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if(response.status == true) {
                        toastr["success"](response.message);
                    } else {
                        $.each(response, function(index, value) {
                            if(index != 'status') { toastr["error"](value); }
                        });
                    }
                }
            });
        }
    });

    $('.select2').select2();

    $('#clear_day_attendance').click(function() {
        var classesID = "<?=$set?>";
        var subjectID = "<?=$subjectID?>";
        var date      = "<?=$date?>";

        if(confirm("Warning: This will set all attendance to 'N/A' for every student in this class for " + date + ". Do you want to proceed?")) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('sattendance/delete_day_attendance')?>",
                data: { 'classesID' : classesID, 'subjectID' : subjectID, 'date' : date },
                dataType: "json",
                success: function(data) {
                    if(data.status == true) {
                        toastr["success"](data.message);
                        location.reload(); 
                    } else {
                        toastr["error"](data.message);
                    }
                }
            });
        }
    });

    $('#date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
        daysOfWeekDisabled: "<?=$siteinfos->weekends?>",
        datesDisabled: ["<?=$get_all_holidays;?>"],       
    });

    $("#classesID").change(function() {
        var id = $(this).val();
        if(parseInt(id)) {
            <?php if($setting->attendance=="subject"){ ?>
            if(id === '0') {
                $('#subjectID').val(0);
            } else {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('sattendance/subjectall')?>",
                    data: {"id" : id},
                    dataType: "html",
                    success: function(data) {
                       $('#subjectID').html(data);
                    }
                });
            }
            <?php } ?>
        }
    });
</script>
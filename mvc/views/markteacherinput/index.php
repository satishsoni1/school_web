<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> Teacher Input</h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active">Teacher Input</li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if((($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) || ($this->session->userdata('usertypeID') != 3)) { ?>
                    <h5 class="page-header">
                        <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID') || $this->session->userdata('usertypeID') == 1)) { ?>
                            <?php if(permissionChecker('markteacherinput_add')) { ?>
                                <a href="<?php echo base_url('markteacherinput/add') ?>">
                                    <i class="fa fa-plus"></i>
                                    Add Teacher Input
                                </a>
                            <?php } ?>
                        <?php } ?>

                        <?php if($this->session->userdata('usertypeID') != 3) { ?>
                            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                                <?php
                                    $classArray = array("0" => $this->lang->line("mark_select_classes"));
                                    if(customCompute($classes)) {
                                        foreach ($classes as $classa) {
                                            $classArray[$classa->classesID] = $classa->classes;
                                        }
                                    }
                                    echo form_dropdown("classesID", $classArray, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                                ?>
                            </div>
                        <?php } ?>
                    </h5>
                <?php } ?>

                <?php 
                
                if(customCompute($students) > 0 ) { 
                    ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("mark_all_students")?></a></li>
                            <?php foreach ($sections as $key => $section) {
                                echo '<li class=""><a data-toggle="tab" href="#'. spClean($section->section.$section->sectionID).' " aria-expanded="false">'. $this->lang->line("mark_section")." ".$section->section. " ( ". $section->category." )".'</a></li>';
                            } ?>
                        </ul>

                        <div class="tab-content">
                            <div id="all" class="tab-pane active">
                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('mark_name')?></th>
                                                <th class="col-sm-2">Todal Days</th>
                                                <th class="col-sm-2">Attendance</th>
                                                <th class="col-sm-2">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(customCompute($students)) {$i = 1; foreach($students as $student) { ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?php echo $i ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('mark_name')?>">
                                                        <?php echo $student->name; ?>
                                                    </td>
                                                    <td data-title="Todal Days">
                                                        <?php echo $marks[$student->studentID]->total_days; ?>
                                                    </td>
                                                    <td data-title="Attendance">
                                                        <?php echo $marks[$student->studentID]->attendance; ?>
                                                    </td>
                                                    <td data-title="Remarks">
                                                        <?php echo $marks[$student->studentID]->remarks; ?>
                                                    </td>
                                               </tr>
                                            <?php $i++; }} ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                            <?php foreach ($sections as $key => $section) { ?>
                                    <div id="<?=spClean($section->section.$section->sectionID)?>" class="tab-pane">
                                        <div id="hide-table">
                                            <table class="table table-striped table-bordered table-hover dataTable no-footer">
                                                <thead>
                                                    <tr>
                                                    <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                                    <th class="col-sm-2"><?=$this->lang->line('mark_name')?></th>
                                                    <th class="col-sm-2">Todal Days</th>
                                                    <th class="col-sm-2">Attendance</th>
                                                    <th class="col-sm-2">Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if(isset($allsection[$section->sectionID]) && customCompute($allsection[$section->sectionID])) { $i = 1; foreach($allsection[$section->sectionID] as $student) { ?>
                                                        <tr>
                                                            <td data-title="<?=$this->lang->line('mark_roll')?>">
                                                                <?php echo $i ?>
                                                            </td>
                                                            <td data-title="<?=$this->lang->line('mark_name')?>">
                                                                <?php echo $student->name; ?>
                                                            </td>
                                                            <td data-title="Todal Days">
                                                                <?php echo $marks[$student->studentID]->total_days; ?>
                                                            </td>
                                                            <td data-title="Attendance">
                                                                <?php echo $marks[$student->studentID]->attendance; ?>
                                                            </td>
                                                            <td data-title="Remarks">
                                                                <?php echo $marks[$student->studentID]->remarks; ?>
                                                            </td>
                                                       </tr>
                                                    <?php $i++; } } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                            <?php } ?>
                        </div>
                    </div> <!-- nav-tabs-custom -->
                <?php } else { ?>
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" href="#all" aria-expanded="true"><?=$this->lang->line("mark_all_students")?></a></li>
                        </ul>
                        <div class="tab-content">
                            <div id="all" class="tab-pane active">
                                <div id="hide-table">
                                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                            <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                                    <th class="col-sm-2"><?=$this->lang->line('mark_name')?></th>
                                                    <th class="col-sm-2">Todal Days</th>
                                                    <th class="col-sm-2">Attendance</th>
                                                    <th class="col-sm-2">Remarks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(customCompute($students)) {$i = 1; foreach($students as $student) { ?>
                                                <tr>
                                                <td data-title="<?=$this->lang->line('mark_roll')?>">
                                                                <?php echo $i ?>
                                                            </td>
                                                            <td data-title="<?=$this->lang->line('mark_name')?>">
                                                                <?php echo $student->name; ?>
                                                            </td>
                                                            <td data-title="Todal Days">
                                                                <?php echo $marks[$student->studentID]->total_days; ?>
                                                            </td>
                                                            <td data-title="Attendance">
                                                                <?php echo $marks[$student->studentID]->attendance; ?>
                                                            </td>
                                                            <td data-title="Remarks">
                                                                <?php echo $marks[$student->studentID]->remarks; ?>
                                                            </td>
                                               </tr>
                                            <?php $i++; }} ?>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div> <!-- nav-tabs-custom -->
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('markteacherinput/mark_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>

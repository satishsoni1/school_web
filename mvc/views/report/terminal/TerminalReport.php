<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('terminalreport/pdf/'.$examID.'/'.$classesID.'/'.$sectionID.'/'.$studentIDD);
            echo btn_printReport('terminalreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('terminalreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_sentToMailReport('terminalreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
        <?=$this->lang->line('terminalreport_report_for')?> <?=$this->lang->line('terminalreport_terminal')?> - <?=$examName?> <?=isset($classes[$classesID]) ? "( ".$classes[$classesID]." ) " : ''?> 
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <style type="text/css">
            * {
    margin: 0px;
    padding: 0px;
    font-family: Arial;
}

body {
	font-family: 'Arial';
	color:#000;
	background: #fff;
    font-size:12px;
}

address {
    font-size: 17px;
    font-style: normal;
}

.profileArea {
    border: 2px solid #ddd;
    padding-bottom: 10px;
    height: 85%;
    border-bottom: none;
}

.borderless{
    border: 0px !important;
    border-left: 0px !important;
    border-right: 0px !important;
}
.headerArea {
    padding: 20px;
    margin-bottom: 20px;
    border-bottom: 1px solid #ddd;
}

.siteLogo {
    float: left;
    width: 25%;
    padding-top: 10px;
    padding-left: 10px
}

.siteTile {
    float: right;
    width: 75%;
}

.siteTitle h2 {
    font-size: 30px;
    margin: 0px;
    padding-top: 5px;
    line-height: 25px;
    font-family: Helvetica;
}

.siteTitle p {
    margin: 0px;
    padding: 0px;
    font-size: 17px;
    font-family: Helvetica;
}

.siteLogoimg {
    width: 120px;
    height: 120px;
}


.areaTop {
    font-family: Helvetica;
}

.studentImage {
    width: 25%;
    float: left;
    text-align: center;
}

.studentProfile {
    width: 75%;
    float: right;
}

.studentImg {
    width: 100px;
    height: 100px;
    border: 1px solid #ddd;
    padding: 5px;
    border-radius: 10%;
    overflow: hidden;
}

.singleItem {
    width: 100%;
    line-height: 25px;
    font-size: 16px;
    font-family: Helvetica;
}

.single_label {
    width: 25%;
    float: left;
    font-weight: bold;
}

.single_value {
    width: 75%;
    float: left;
}

.label {
    width: 40%;
    float: left;
    font-weight: bold;
}

.value {
    width: 58%;
    float: left;
}

.markArea {
    padding: 0px 30px;
    padding-top: 20px;
    font-size: 16px
}

.footerArea {
    text-align: center;
    border: 2px solid #ddd;
    border-top: none;
    padding-bottom: 10px;
}

.flogo {
    width: 40px;
    height: 40px;
}

.copyright {
    margin: 0px;
    font-size: 14px;
}

.markArea h4 {
    font-size: 16px;
    margin: 0px;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}         

.singleExam {
    border: 1px solid #ddd;
    margin-bottom: 20px;
    padding-bottom: 20px;
}

.singleExam p {
    font-size: 14px;
    padding-left: 10px;
}

.text-red {
    color: #FF766C;
}

.text-bold {
    font-weight: bold;
}

.table {
    border-collapse: collapse !important;
    width: 100%;
    max-width: 100%;
    margin-bottom: 10px;
    text-align: left;
    font-family: arial;
}


.table td,
.table th {
    background-color: #fff !important;
    padding: 6px;
    border: 1px solid #ddd !important;
    line-height: 1.22857143;
    vertical-align: top;
}
.table td {
    font-weight: bold; /* Adjust the weight as needed */
}
.table-bordered {
    border: 1px solid #ddd;
}

.table-bordered>tr>th,
.table-bordered>tr>td,
.table-bordered>thead>tr>th,
.table-bordered>thead>tr>td {
    border-bottom-width: 2px;
}
.table-nobordered {
    border: 0px solid #ddd;
}
.table-nobordered>tr>th,
.table-nobordered>tr>td,
.table-nobordered>thead>tr>th,
.table-nobordered>thead>tr>td, 
.table-nobordered>tr{
    border: 0px;
    border-width: 0px;
}

.pull-right {
    text-align: right !important;
}
.pull-left {
    text-align: left !important;
}

.tablePadding {
    margin: 10px;
}
.w-20{
    width: 20%;
}
.w-50{
    width: 50%;
}
.text-center{
text-align: center;
}
.cell-padding-0{
padding: 0px;
}
.f-12{
    font-size: 12px;
}
.f-16{
    font-size: 16px;
}
            .mainterminalreport{
                margin: 0px;
                overflow: hidden;
                border:1px solid #ddd;
                max-width:794px;
                margin: 0px auto;
                margin-bottom: 10px;
                padding:30px;
            }

            .terminal-headers{
                border-bottom: 1px solid #ddd;
                overflow: hidden;
                padding-bottom: 10px;
                vertical-align: middle;
                margin-bottom: 4px;
            }

            .terminal-logo {
                float: left;
            }

            .terminal-headers img{
                width: 60px;
                height: 60px;
            }

            .school-name h2{
                padding-left: 20px;
                padding-top: 7px;
                font-weight: bold;
                float: left;
            }

            .terminal-infos {
                width: 100%;
                overflow: hidden;
            }

            .terminal-infos h3{
                padding: 2px 0px;
                margin: 0px;
            }

            .terminal-infos p{
                margin-bottom: 3px;
                font-size: 15px;
            }

            .school-address{
                float: left;
                width: 40%;
            }

            .student-profile {
                float: left;
                width: 40%;

            }

            .student-profile-img {
                float: left;
                width: 20%;
                text-align: right;
            }

            .student-profile-img img {
                width: 120px;
                height: 120px;
                border: 1px solid #ddd;
                margin-top: 5px;
                margin-right: 2px;
            }

            @media screen and (max-width: 480px) {
                .school-name h2{
                    padding-left: 0px;
                    float: none;
                }

                .school-address {
                    width: 100%;
                }

                .student-profile {
                    width: 100%;
                } 

                .student-profile-img  {
                    margin-top: 10px;
                    width: 100%;
                }

                .student-profile-img img {
                    width: 100%;
                    height: 100%;
                    margin: 10px 0px;
                }
            }

            .terminal-contents {
                width: 100%;
                overflow: hidden;
            }

            .terminal-contents table {
                width: 100%;
            }

            .terminal-contents table tr,.terminal-contents table td,.terminal-contents table th {
                border:1px solid #ddd;
                padding: 8px 2px;
                font-size: 14px;
                text-align: center;
            }

            @media print {
                .mainterminalreport{
                    border:0px solid #ddd;
                    padding: 0px 20px;
                }

                .student-profile-img img {
                    margin-right: 5px !important;
                }

                .terminal-contents table td,.terminal-contents table th {
                    font-size: 12px;
                }
            }
        </style>
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                <?php if(customCompute($studentLists)) { foreach($studentLists as $student) { ?>
                    <div class="mainterminalreport">
                    <table width="100%">
                                    <tbody><tr class="borderless">
                                        <th class="text-center borderless f-12"  colspan=3>Khalapur Taluka Shikshan Prasarak Mandal's</th>
                                    </tr>
                                    <tr class="borderless">
                                        <th class="text-center f-16" colspan=3>P.P. GAGAN GIRI MAHARAJ INTERNATIONAL SCHOOL, KHOPOLI.</th>
                                    </tr>
                                    <tr class="borderless">
                                        <td class="text-left borderless"><b>SCHOOL CODE.:31384</b></td>
                                        <td class="text-center borderless"><b>CBSE AFFILIATION NO.:1131395</b></td>
                                        <td class="text-right borderless"><b>UDISE NO.:27240312802</b></td>
                                    </tr>
                                    <tr class="borderless">
                                        
                                        <td class="text-center borderless" colspan=3><br/><b>PROGRESS REPORT CARD (A.Y.2024-2025)</b></td>
                                    </tr>
                                </tbody></table>
                                <br/>
                                <br/>
                        <div class="terminal-infos">
                            <div class="school-address">
                                <h4><b><?=$siteinfos->sname?></b></h4>
                                <p><?=$siteinfos->address?></p>
                                <p><?=$this->lang->line('terminalreport_phone')?> : <?=$siteinfos->phone?></p>
                                <p><?=$this->lang->line('terminalreport_email')?> : <?=$siteinfos->email?></p>
                            </div>
                            <div class="student-profile">
                                <h4><b><?=$student->srname?></b></h4>
                                <p><?=$this->lang->line('terminalreport_academic_year')?> : <b><?=$schoolyearsessionobj->schoolyear;?></b>
                                <!-- <p><?=$this->lang->line('terminalreport_position_in_class')?> :  <b><?=customCompute($studentPosition['studentClassPositionArray']) ? addOrdinalNumberSuffix((int)array_search($student->srstudentID, array_keys($studentPosition['studentClassPositionArray'])) + 1) : ''?></b></p> -->
                                <p><?=$this->lang->line('terminalreport_reg_no')?> : <b><?=$student->srregisterNO?></b>, <?=$this->lang->line('terminalreport_class')?> : <b><?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?></b></p>
                                <p><?=$this->lang->line('terminalreport_section')?> : <b><?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?></b>, <?=$this->lang->line('terminalreport_roll_no')?> : <b><?=$student->srroll?></b></p>  
                                <p><?=$this->lang->line('terminalreport_group')?> : <b><?=isset($groups[$student->srstudentgroupID]) ? $groups[$student->srstudentgroupID] : ''?></b></p> 
                                <p><?=$this->lang->line('terminalreport_exam')?> : <b><?=$examName?></b></p>  
                            </div>
                            <div class="student-profile-img">
                                <img src="<?=imagelink($student->photo)?>" alt="">
                            </div>
                        </div>
                        <div class="terminal-contents terminalreporttable">
                            <h4><b><?=$examName?> Report</b></b></h4>
                            <table>
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('terminalreport_subjects');?></th>
                                        <?php
                                            reset($markpercentagesArr);
                                            $firstindex          = key($markpercentagesArr);
                                            $uniquepercentageArr = isset($markpercentagesArr[$firstindex]) ? $markpercentagesArr[$firstindex] : [];
                                            $markpercentages     = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                            if(customCompute($markpercentages)) {
                                                foreach($markpercentages as $markpercentageID) {
                                                    $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                                    echo "<th>".$markpercentage."</th>";
                                            } }
                                        ?>
                                        <th><?=$this->lang->line('terminalreport_total')?></th>
                                        <th><?=$this->lang->line('terminalreport_grade');?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(customCompute($subjects)) { foreach($subjects as $subject) { ?>
                                        <?php if(isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID])) {
                                                $uniquepercentageArr =  isset($markpercentagesArr[$subject->subjectID]) ? $markpercentagesArr[$subject->subjectID] : [];
                                            ?>
                                            <tr>
                                                <td><?=$subject->subject?></td>
                                                <?php 
                                                $percentageMark  = 0;
                                                if(customCompute($markpercentages)) {
                                                    foreach($markpercentages as $markpercentageID) {
                                                        $f = false;
                                                        if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                                            $f = true;
                                                            $percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
                                                        } 
                                                        ?>
                                                        <td>
                                                            <?php 
                                                                if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {
                                                                    echo $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID];
                                                                } else {
                                                                    if($f) {
                                                                        echo 'N/A';
                                                                    }
                                                                }
                                                            ?>    
                                                        </td>
                                                <?php } } ?>
                                                <td><?=isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0'?></td>
                                                <?php
                                                    $subjectMark = isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';
                                                $subjectMark = markCalculationView($subjectMark, $subject->finalmark, $percentageMark);
                                                if(customCompute($grades)) { foreach($grades as $grade) {
                                                    if(($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark)) { ?>
                                                        <td><?=$grade->grade?></td>
                                                <?php } } } ?>
                                            </tr>
                                        <?php } ?>
                                    <?php } }  ?>
                                    <tr>
                                        <td><b><?=$this->lang->line('terminalreport_total')?></b></td>
                                        <?php if(customCompute($markpercentages)) {
                                            foreach($markpercentages as $markpercentageID) { ?>
                                                <td><b><?=isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID]) ? $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] : '0'?></b></td>
                                        <?php } } ?>
                                        <td><b><?=isset($studentPosition[$student->srstudentID]['totalSubjectMark']) ? $studentPosition[$student->srstudentID]['totalSubjectMark'] : '0'?></b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    
                                    <tr>
                                        <td colspan="<?=($col-4)?>"><b><?=$this->lang->line('terminalreport_mark_average')?> : <?=isset($studentPosition[$student->srstudentID]['classPositionMark']) ? ini_round($studentPosition[$student->srstudentID]['classPositionMark']) : '0.00'?></b></td>
                                        <td colspan="4"><b><?=$this->lang->line('terminalreport_class_average')?> :
                                            <?php 
                                                if(isset($studentPosition[$student->srstudentID]['classPositionMark']) && $studentPosition[$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition['totalStudentMarkAverage'])) {
                                                    echo ini_round($studentPosition['totalStudentMarkAverage'] / $studentPosition[$student->srstudentID]['classPositionMark']);
                                                } else {
                                                    echo "0.00";
                                                }
                                            ?></b>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><?=$this->lang->line('terminalreport_attendance');?></td>
                                        <td colspan="<?=($col-2)?>"><?=isset($attendance[$student->srstudentID]) ? $attendance[$student->srstudentID] : '0'?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">Class Teacher Remarks</td>
                                        <td colspan="<?=($col-2)?>"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="<?=$col?>"><?=$this->lang->line('terminalreport_interpretation')?> :
                                            <b>
                                                <?php if(customCompute($grades)) { $i = 1; foreach($grades as $grade) {
                                                    if(customCompute($grades) == $i) {
                                                        echo $grade->gradefrom.'-'.$grade->gradeupto." = ".$grade->point." [".$grade->grade."]";
                                                    } else {
                                                        echo $grade->gradefrom.'-'.$grade->gradeupto." = ".$grade->point." [".$grade->grade."], ";
                                                    }
                                                    $i++;
                                                }}?>
                                            </b>
                                        </td>
                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <p style="page-break-after: always;">&nbsp;</p>
                    <?php } } else { ?>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('terminalreport_data_not_found')?></b></p>
                        </div>
                    <?php } ?>
                </div>
            </div><!-- row -->
        </div>
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('terminalreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('terminalreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('terminalreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("terminalreport_to")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php
                    if(form_error('subject'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="subject" class="col-sm-2 control-label">
                        <?=$this->lang->line("terminalreport_subject")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="subject_error">
                    </span>

                </div>

                <?php
                    if(form_error('message'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="message" class="col-sm-2 control-label">
                        <?=$this->lang->line("terminalreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("terminalreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">

    $('.terminalreporttable').mCustomScrollbar({
        axis:"x"
    });
    
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('terminalreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $('#send_pdf').click(function() {
        var field = {
            'to'         : $('#to').val(), 
            'subject'    : $('#subject').val(), 
            'message'    : $('#message').val(),
            'examID'     : '<?=$examID?>',
            'classesID'  : '<?=$classesID?>',
            'sectionID'  : '<?=$sectionID?>',
            'studentID'  : '<?=$studentIDD?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('terminalreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('terminalreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('terminalreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if( response.to) {
                            $("#to_error").html("<?=$this->lang->line('terminalreport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        }

                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('terminalreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
                        }
                        
                        if(response.message) {
                            toastr["error"](response.message)
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
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
</script>
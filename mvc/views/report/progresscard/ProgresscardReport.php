<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
        //$pdf_preview_uri = base_url('progresscardreport/pdf/' . $classesID . '/' . $sectionID . '/' . $studentID);
        $pdf_preview_uri = base_url('progresscardreport/print_preview_all/' . $classesID . '/' . $sectionID . '/' . $studentID . '/' . $termID);
        
        echo btn_pdfPreviewReport('progresscardreport', $pdf_preview_uri, $this->lang->line('report_pdf_preview'));
        // echo btn_sentToMailReport('progresscardreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
            <?= $this->lang->line('progresscardreport_report_for') ?> - <?= $this->lang->line('progresscardreport_progresscard') ?></h3>
    </div><div id="printablediv">
        <style type="text/css">
            .mainprogresscardreport {
                margin: 0px;
                overflow: hidden;
                border: 1px solid #ddd;
                max-width: 900px;
                margin: 0px auto;
                margin-bottom: 10px;
                padding: 30px;
            }

            .progresscard-headers {
                border-bottom: 1px solid #ddd;
                overflow: hidden;
                padding-bottom: 10px;
                vertical-align: middle;
                margin-bottom: 4px;
            }

            .progresscard-logo {
                float: left;
            }

            .progresscard-headers img {
                width: 60px;
                height: 60px;
            }

            .school-name h2 {
                float: left;
                padding-left: 20px;
                padding-top: 7px;
                font-weight: bold;
            }

            .progresscard-infos {
                width: 100%;
                overflow: hidden;
            }

            .progresscard-infos h3 {
                padding: 2px 0px;
                margin: 0px;
            }

            .progresscard-infos p {
                margin-bottom: 3px;
                font-size: 15px;
            }

            .school-address {
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
                .school-name h2 {
                    padding-left: 0px;
                    float: none;
                }

                .school-address {
                    width: 100%;
                }

                .student-profile {
                    width: 100%;
                }

                .student-profile-img {
                    margin-top: 10px;
                    width: 100%;
                }

                .student-profile-img img {
                    width: 100%;
                    height: 100%;
                    margin: 10px 0px;
                }
            }

            .progresscard-contents {
                width: 100%;
                overflow: hidden;
                margin-top: 10px;
            }

            .progresscard-contents table {
                width: 100%;
            }

            .progresscard-contents table tr,
            .progresscard-contents table td,
            .progresscard-contents table th {
                border: 1px solid #ddd;
                padding: 8px 1px;
                font-size: 14px;
                text-align: center;
            }

            @media print {
                .mainprogresscardreport {
                    border: 0px solid #ddd;
                    padding: 0px 20px;
                }

                .student-profile-img img {
                    margin-right: 5px !important;
                }

                .progresscard-contents table td,
                .progresscard-contents table th {
                    font-size: 12px;
                }
            }

            * {
                margin: 0px;
                padding: 0px;
                font-family: Arial;
            }

            body {
                font-family: 'Arial';
                color: #000;
                background: #fff;
                font-size: 12px;
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

            .borderless {
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
                table-layout:fixed;
            }


            .table td,
            .table th {
                background-color: #fff !important;
                padding: 3px;
                font-size: 9px;
                border: 1px solid #ddd !important;
                line-height: 1.22857143;
                vertical-align: top;
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
            .table-nobordered>tr {
                border: 0px;
                border-width: 0px;
            }

            .pull-right {
                text-align: right !important;
            }

            .text-left {
                text-align: left !important;
            }

            .mainprogresscardreport{
                
            }
            .tablePadding {
                margin: 10px;
            }

            .w-20 {
                width: 20%;
            }

            .w-50 {
                width: 50%;
            }

            .text-center {
                text-align: center;
            }

            .cell-padding-0 {
                padding: 0px;
            }

            .f-12 {
                font-size: 12px;
            }

            .f-16 {
                font-size: 16px;
            }
        </style>
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?php if (customCompute($students)) {
                        foreach ($students as $student) {

                            $queryArray = [
                                'classesID'    => $student->srclassesID,
                                'sectionID'    => $student->srsectionID,
                                'studentID'    => $student->srstudentID,
                                'schoolyearID' => $schoolyearID,
                            ];
                            $CI = &get_instance();
                            $marks             = $CI->mark_m->student_all_mark_array($queryArray);
                            $marks_optional             = $CI->mark_m->student_all_optional_mark_array($queryArray);
                            $retMark = [];
                            if (customCompute($marks)) {
                                foreach ($marks as $mark) {
                                    $retMark[$mark->examID][$mark->subjectID][$mark->markpercentageID] = $mark->mark;
                                }
                            }
                            $retOptionalMark = [];
                            if (customCompute($marks_optional)) {
                                foreach ($marks_optional as $mark) {
                                    $retOptionalMark[$mark->examID][$mark->subjectID] = $mark->grade;
                                }
                            }
                    ?>
                            <div class="mainprogresscardreport">
                                <table>
                                    <tr class="borderless">
                                        <td rowspan=4 class="w-20 borderless"><img class="studentImg" src="<?= pdfimagelink($siteinfos->photo, 'uploads/images/') ?>"></td>
                                        <th class="text-center borderless f-12">Khalapur Taluka Shikshan Prasarak Mandal's</th>
                                        <td rowspan=4 class="w-20 borderless"><img class="studentImg" src="<?= pdfimagelink($student->photo) ?>"></td>
                                    </tr>
                                    <tr class="borderless">
                                        <th class="text-center f-16">P.P. GAGAN GIRI MAHARAJ INTERNATIONAL SCHOOL, KHOPOLI.</th>
                                    </tr>
                                    <tr class="borderless">
                                        <td class="text-center borderless"><b>UDISE NO.:27240312802</b></td>
                                    </tr>
                                    <tr class="borderless">
                                        <td class="text-center borderless"><b>PROGRESS REPORT CARD (A.Y.<?= $schoolyearsessionobj->schoolyear ?>)</b></td>
                                    </tr>
                                </table>
                                <p style="padding-top:1rem;">&nbsp;</p>
                                <table class="table table-bordered">
                                    <tr>
                                        <td colspan="2" class="w-50 text-center">STUDENT PROFILE</td>
                                        <td class="text-center">GRADING SCALE FOR CO-SCHOLASTIC AREAS</td>
                                    </tr>
                                    <tr>
                                        <td class="w-20">1. Student Name</td>
                                        <td class="w-30"><?= $student->srname ?></td>
                                        <td class="w-50 text-center" rowspan="7">
                                            <p>Grade are awarded on a 3-point frading as follows:</p><br />
                                            <table class="table">

                                                <tr>
                                                    <td class="text-center">Marks</td>
                                                    <td class="text-center">Grade</td>
                                                <tr>
                                                    <td class="text-center">3</td>
                                                    <td class="text-center">A</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">2</td>
                                                    <td class="text-center">B</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">1</td>
                                                    <td class="text-center">C</td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>2. Mother's Name</td>
                                        <td><?= $student->mother_name ?></td>
                                    </tr>
                                    <tr>
                                        <td>3. Father's Name</td>
                                        <td><?= $student->father_name ?></td>
                                    </tr>
                                    <tr>
                                        <td>4. Grade & Section</td>
                                        <td><?= isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : '' ?> (<?= isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : '' ?>) Roll No.: <?= $student->srroll ?></td>
                                    </tr>
                                    <tr>
                                        <td>5. G.R. No.</td>
                                        <td><?= $student->srregisterNO ?></td>
                                    </tr>
                                    <tr>
                                        <td>6. Date of Birth</td>
                                        <td><?= date('d-m-Y', strtotime($student->dob)) ?></td>
                                    </tr>
                                    <tr>
                                        <td>7. Contact No.</td>
                                        <td><?= $student->phone ?></td>
                                    </tr>
                                    <tr>
                                        <td>ATTENDANCE</td>
                                        <td colspan=2>Total Attendance of the student:_______Total Working Days_______.</td>
                                    </tr>
                                </table>
                                <table class="table table-bordered">
                                    <tr>
                                        <th class="text-left">
                                            Sholastic Area
                                        </th>
                                        <?php
                                        if (customCompute($markpercentagesclassArr[1])) {
                                        ?>
                                            <th colspan=7>
                                                Term-1 (100 Marks)
                                            </th>
                                        <?php
                                        }
                                        if (customCompute($markpercentagesclassArr[2])) {
                                        ?>
                                            <th colspan=7>
                                                Term-2 (100 Marks)
                                            </th>
                                        <?php
                                        }
                                        ?>
                                        <th>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th class="text-left">
                                            SUBJECT
                                        </th>
                                        <?php
                                        if (customCompute($markpercentagesclassArr[1])) {
                                            $text = "";
                                            foreach ($markpercentagesclassArr[1] as $subjectID => $markpercentageArr) {

                                                foreach ($markpercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'] as $markpercentageID) {
                                                    $text .= "<th class=' text-center'>";
                                                    $text .= (isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype . " (" . $percentageArr[$markpercentageID]->percentage . ")" : '');
                                                    $text .= "</th>";
                                                }
                                                break;
                                            }
                                            echo $text;
                                        ?>
                                            <th class="text-left">
                                                Mark Obtained (100)
                                            </th>
                                            <th class="text-left">
                                                Grade
                                            </th>
                                        <?php
                                        }
                                        if (customCompute($markpercentagesclassArr[2])) {
                                            $text = "";
                                            foreach ($markpercentagesclassArr[2] as $subjectID => $markpercentageArr) {

                                                foreach ($markpercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'] as $markpercentageID) {
                                                    $text .= "<th class=' text-center'>";
                                                    $text .= (isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype . " (" . $percentageArr[$markpercentageID]->percentage . ")" : '');
                                                    $text .= "</th>";
                                                }
                                                break;
                                            }
                                            echo $text;
                                        ?>
                                            <th class="text-left">
                                                Mark Obtained (100)
                                            </th>
                                            <th class="text-left">
                                                Grade
                                            </th>
                                        <?php
                                        }
                                        ?>
                                        <th class="text-left">
                                            Overall Grade
                                        </th>
                                    </tr>
                                        <?php

                                        $totalAllSubjectMark      = 0;
                                        $totalAllSubjectFinalMark = 0;
                                        $total_gpa_point = 0;
                                        if (customCompute($mandatorySubjects)) {
                                            foreach ($mandatorySubjects  as $mandatorySubject) {
                                                $totalSubjectMark = 0;
                                                $totalGradeSubjectMark = 0 ?>
                                                <tr>
                                                    <td><?= $mandatorySubject->subject ?></td>
                                                    <?php
                                                    if (customCompute($settingExam)) {
                                                        foreach ($settingExam as $examID) {
                                                            $examTotalSubjectMark = 0;

                                                            $uniquepercentageArr = isset($markpercentagesclassArr[$examID][$mandatorySubject->subjectID]) ? $markpercentagesclassArr[$examID][$mandatorySubject->subjectID] : [];
                                                            $markpercentages     = [];
                                                            if (customCompute($uniquepercentageArr)) {
                                                                $markpercentages = $uniquepercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'];
                                                            }

                                                            $percentageMark      = 0;
                                                            if (customCompute($markpercentages)) {
                                                                foreach ($markpercentages as $markpercentageID) {

                                                                    if (isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                                                        $percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
                                                                    }

                                                    ?>
                                                                    <td>
                                                                        <?php
                                                                        $mark = 0;
                                                                        if (isset($markArray[$examID][$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID])) {
                                                                            $mark = $markArray[$examID][$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
                                                                        }
                                                                        echo ($mark) ? $mark : '';
                                                                        
                                                                        // ADDED is_numeric check to prevent AB crashing calculations
                                                                        if (is_numeric($mark)) {
                                                                            $totalSubjectMark     += $mark;
                                                                            $examTotalSubjectMark += $mark;
                                                                        }
                                                                        ?>
                                                                    </td>
                                                    <?php }
                                                            }
                                                            ?>
                                                            <td><?= $examTotalSubjectMark ?></td>
                                                            <?php
                                                            if (customCompute($grades)) {
                                                        foreach ($grades as $grade) {
                                                            if (($grade->gradefrom <= floor($examTotalSubjectMark)) && ($grade->gradeupto >= floor($examTotalSubjectMark))) { ?>
                                                                <td><?= $grade->grade ?></td>
                                                    <?php }
                                                        }
                                                    } ?>
                                                            <?php
                                                            $totalGradeSubjectMark += markCalculationView($examTotalSubjectMark, $mandatorySubject->finalmark, $percentageMark);
                                                        }
                                                    } ?>
                                                    <?php
                                                    $totalAllSubjectMark      += $totalSubjectMark;
                                                    $subjectGradeMark          = $totalGradeSubjectMark / customCompute($settingExam);

                                                    if (customCompute($grades)) {
                                                        foreach ($grades as $grade) {
                                                            if (($grade->gradefrom <= floor($subjectGradeMark)) && ($grade->gradeupto >= floor($subjectGradeMark))) { ?>
                                                                <td><?= $grade->grade ?></td>
                                                                
                                                    <?php }
                                                        }
                                                    } ?>
                                                </tr>
                                            <?php } ?>
                                            
                                            <tr>
                                                <th colspan=16>
                                                </th>
                                            </tr>
                                            <tr>
                                                <?php
                                                if (customCompute($markpercentagesclassArr[1])) {
                                                ?>
                                                    <th class="text-left" colspan=7>Co-Scholastic Area Term-1 (On a 3-point A-C) grading scale</th>
                                                    <th class="text-left">Grade</th>
                                                <?php
                                                }
                                                if (customCompute($markpercentagesclassArr[2])) {
                                                ?>
                                                    <th class="text-left" colspan=7>Co-Scholastic Area Term-2 (On a 3-point A-C) grading scale</th>
                                                    <th class="text-left">Grade</th>
                                                <?php } ?>
                                            </tr>
                                            <?php

                                            $optional_marks = $retOptionalMark;
                                            foreach ($optionalSubjects as $subjectID => $subject) {

                                            ?>
                                                <tr>
                                                    <td colspan=7><?= $subject->subject ?></td>
                                                    <?php
                                                    $examID = 1;
                                                    if (customCompute($markpercentagesclassArr[1])) {
                                                    ?>
                                                        <td><?= isset($optional_marks[$examID][$subjectID]) ? $optional_marks[$examID][$subjectID] : '' ?></td>
                                                    <?php
                                                    }
                                                    ?>
                                                    <td colspan=7><?= $subject->subject ?></td>
                                                    <?php
                                                    $examID = 2;
                                                    if (customCompute($markpercentagesclassArr[2])) {
                                                    ?>
                                                        <td><?= isset($optional_marks[$examID][$subjectID]) ? $optional_marks[$examID][$subjectID] : '' ?></td>
                                                    <?php } ?>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                            <tr>
                                                <th colspan=16>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-left" colspan=8>
                                                    <?php
                                                    // Make sure totalMark and totalFinalMark are available here
                                                    // Defaulting to 0 if not defined previously to prevent undefined variable errors
                                                    $t_mark = isset($totalMark) ? $totalMark : 0;
                                                    $t_final = isset($totalFinalMark) ? $totalFinalMark : 0;
                                                    $totalmarkpercentage  = markCalculationView($t_mark, $t_final);
                                                    ?>

                                                    Overall Percentage: <?= ini_round($totalmarkpercentage) ?>
                                                </th>
                                                <th class="text-left" colspan=8>
                                                    Sign. of Parent
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-left" colspan=16>
                                                    Class Teacher's Remark: _________________________________
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-left" colspan=8>
                                                    Result: <?= (ini_round($totalmarkpercentage) >= 36) ? "Passed & PROMOTED TO NEXT CLASS " : "Failed" ?>
                                                </th>
                                                <th class="text-left" colspan=8>
                                                    Sign. of Class Teacher
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-left" colspan=16>
                                                    Schhol Re-opens on: <?= isset($re_open_date) ? date('d-m-Y', strtotime($re_open_date)) : 'N/A' ?>
                                                </th>
                                            </tr>
                                            <tr>
                                                <th class="text-left" colspan=8>
                                                    Date: <?= isset($generate_date) ? date('d-m-Y', strtotime($generate_date)) : 'N/A' ?>
                                                </th>
                                                <th class="text-left" colspan=8>
                                                    Sign. of Principal
                                                </th>
                                            </tr>
                                </table>
                                <p>Grade scale for scholastic areas:</p>
                                <p>Grades are awarded on a 8-point grading scale as follows</p>
                                <br />
                                <table class="table table-bordered">
                                    <tr>
                                        <th class="text-center">Marks Range</th>
                                        <th class="text-center">91-100</th>
                                        <th class="text-center">81-90</th>
                                        <th class="text-center">71-80</th>
                                        <th class="text-center">61-70</th>
                                        <th class="text-center">51-60</th>
                                        <th class="text-center">41-50</th>
                                        <th class="text-center">33-40</th>
                                        <th class="text-center">32 and below</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">Grade</th>
                                        <th class="text-center">A1</th>
                                        <th class="text-center">A2</th>
                                        <th class="text-center">B1</th>
                                        <th class="text-center">B2</th>
                                        <th class="text-center">C1</th>
                                        <th class="text-center">C2</th>
                                        <th class="text-center">D</th>
                                        <th class="text-center">E</th>
                                    </tr>
                                </table>
                            </div>
                        <?php } ?>
                        <p style="page-break-after: always;">&nbsp;</p>
                    <?php }
                    } else { ?>
                    <div class="callout callout-danger">
                        <p><b class="text-info"><?= $this->lang->line('progresscardreport_data_not_found') ?></b></p>
                    </div>
                <?php } ?>
                </div>
            </div></div></div>
</div>


<form class="form-horizontal" role="form" action="<?= base_url('progresscardreport/send_pdf_to_mail'); ?>" method="post">
    <div class="modal fade" id="mail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?= $this->lang->line('progresscardreport_close') ?></span></button>
                    <h4 class="modal-title"><?= $this->lang->line('progresscardreport_mail') ?></h4>
                </div>
                <div class="modal-body">

                    <?php
                    if (form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?= $this->lang->line("progresscardreport_to") ?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?= set_value('to') ?>">
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php
                if (form_error('subject'))
                    echo "<div class='form-group has-error' >";
                else
                    echo "<div class='form-group' >";
                ?>
                <label for="subject" class="col-sm-2 control-label">
                    <?= $this->lang->line("progresscardreport_subject") ?> <span class="text-red">*</span>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="subject" name="subject" value="<?= set_value('subject') ?>">
                </div>
                <span class="col-sm-4 control-label" id="subject_error">
                </span>

            </div>

            <?php
            if (form_error('message'))
                echo "<div class='form-group has-error' >";
            else
                echo "<div class='form-group' >";
            ?>
            <label for="message" class="col-sm-2 control-label">
                <?= $this->lang->line("progresscardreport_message") ?>
            </label>
            <div class="col-sm-6">
                <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?= set_value('message') ?>"></textarea>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?= $this->lang->line('close') ?></button>
        <input type="button" id="send_pdf" class="btn btn-success" value="<?= $this->lang->line("progresscardreport_send") ?>" />
    </div>
    </div>
    </div>
    </div>
</form>
<script type="text/javascript">
    $('.progresscardreporttable').mCustomScrollbar({
        axis: "x"
    });

    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?= $this->lang->line('progresscardreport_mail_valid') ?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $('#send_pdf').click(function() {
        var field = {
            'to': $('#to').val(),
            'subject': $('#subject').val(),
            'message': $('#message').val(),
            'classesID': '<?= $classesID ?>',
            'sectionID': '<?= $sectionID ?>',
            'studentID': '<?= $studentID ?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if (to == "" || to == null) {
            error++;
            $("#to_error").html("<?= $this->lang->line('progresscardreport_mail_to') ?>").css("text-align", "left").css("color", 'red');
        } else {
            if (check_email(to) == false) {
                error++
            }
        }

        if (subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?= $this->lang->line('progresscardreport_mail_subject') ?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if (error == 0) {
            $('#send_pdf').attr('disabled', 'disabled');
            $.ajax({
                type: 'POST',
                url: "<?= base_url('progresscardreport/send_pdf_to_mail') ?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if (response.to) {
                            $("#to_error").html("<?= $this->lang->line('progresscardreport_mail_to') ?>").css("text-align", "left").css("color", 'red');
                        }

                        if (response.subject) {
                            $("#subject_error").html("<?= $this->lang->line('progresscardreport_mail_subject') ?>").css("text-align", "left").css("color", 'red');
                        }

                        if (response.message) {
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
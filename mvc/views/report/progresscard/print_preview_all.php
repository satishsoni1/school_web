<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
</head>
 
<body>
    <?php if(customCompute($students)) { 
        $count_student = count($students); 
        $student_counting = 0;
        foreach($students as $student_list) { ?>
        <table>
        <tr class="borderless">
            <td rowspan=4 class="w-10 borderless"><img class="studentImg" src="<?= pdfimagelink($siteinfos->photo, 'uploads/images/') ?>"></td>
            <th colspan="3" class="text-center borderless f-12">Khalapur Taluka Shikshan Prasarak Mandal's</th>
            <td rowspan=4 class="w-10 borderless"><img class="studentImg" src="<?= pdfimagelink($student[$student_list->studentID]->photo) ?>"></td>
        </tr>
        <tr class="borderless">
            <th colspan="3" class="text-center f-16">P.P. GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL, KHOPOLI.</th>
        </tr>
        <tr class="borderless">
            <th class="text-center borderless">AFFILIATION NO.:1131395</th>
            <th class="text-center borderless">UDISE NO.:27240312802</th>
            <th class="text-center borderless">SCHOOL CODE:31384</th>
        </tr>
        <tr class="borderless">
            <th colspan="3" class="text-center borderless" style="font-size: 16px;">PROGRESS REPORT CARD (A.Y.<?= $schoolyear->schoolyear ?>)</th>
        </tr>
    </table>
    <table class="table table-bordered">
        <tr>
            <td colspan="3" class="w-50 text-center"><span style="font-size:11px">STUDENT PROFILE</span></td>
            <td class="text-center"><span style="font-size:11px">GRADING SCALE FOR CO-SCHOLASTIC AREAS</span></td>
        </tr>
        <tr>
            <td class="w-20"><span style="font-size:11px">1. Student Name</span></td>
            <td class="w-30" colspan="2"><span style="font-size:11px"><?= $student[$student_list->studentID]->srname ?></span></td>
            <td class="w-50 text-center" rowspan="7">
                <p><span style="font-size:11px">Grade are awarded on a 3-point grading as follows:</span></p><br />
                <table class="table">

                    <tr>
                        <td class="text-center"><span style="font-size:11px">Marks</span></td>
                        <td class="text-center"><span style="font-size:11px">Grade</span></td>
                    <tr>
                        <td class="text-center"><span style="font-size:11px">3</span></td>
                        <td class="text-center"><span style="font-size:11px">A</span></td>
                    </tr>
                    <tr>
                        <td class="text-center"><span style="font-size:11px">2</span></td>
                        <td class="text-center"><span style="font-size:11px">B</span></td>
                    </tr>
                    <tr>
                        <td class="text-center"><span style="font-size:11px">1</span></td>
                        <td class="text-center"><span style="font-size:11px">C</span></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td><span style="font-size:11px">2. Mother's Name</span></td>
            <td colspan="2"><span style="font-size:11px"><?= $student[$student_list->studentID]->mother_name ?></span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px">3. Father's Name</span></td>
            <td colspan="2"><span style="font-size:11px"><?= $student[$student_list->studentID]->father_name ?></span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px">4. Grade & Section</span></td>
            <td colspan="2"><?= customCompute($classes) ? $classes->classes : '' ?></span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px">5. Roll No.</span></td>
            <td colspan="2"><span style="font-size:11px"><?= $student[$student_list->studentID]->srroll ?></span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px">6. G.R. No.</span></td>
            <td colspan="2"><span style="font-size:11px"><?= $student[$student_list->studentID]->srregisterNO ?></span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px">7. Date of Birth</span></td>
            <td colspan="2"><span style="font-size:11px"><?= date('d-m-Y', strtotime($student[$student_list->studentID]->dob)) ?></span></td>
        </tr>
        <tr>
            <td><span style="font-size:11px">ATTENDANCE</span></td>
            <?php
            $attendance_array = calculate_attendace_total($student_list->studentID, $schoolyear->schoolyearID);
            $attendance_count = isset($attendance_array['present'])?$attendance_array['present']:0;
            ?>
            <?php 
            if (customCompute($classes)) {
                if($student_list->studentID==2628) {
                    $total_working_days = "150";
                }else if($student_list->studentID==2621) {
                    $total_working_days = "180";
                }else if($student_list->studentID==2594) {
                    $total_working_days = "200";
                }else if($student_list->studentID==2608) {
                    $total_working_days = "198";
                } else {
                    $total_working_days = $classes->total_working_days;
                }
            } else {
                $total_working_days = 0;
            }
            ?>
            <td colspan=3><span style="font-size:11px">Total Attendance of the student: <?= ($attendance_count!=null)?'<span style="border-bottom:1px solid #000">&nbsp;&nbsp;&nbsp;&nbsp;'.$attendance_count.'&nbsp;&nbsp;&nbsp;&nbsp;</span>':'______________' ?>Total Working Days<?= customCompute($classes) ? '<span style="border-bottom:1px solid #000">&nbsp;&nbsp;&nbsp;&nbsp;'.$total_working_days.'&nbsp;&nbsp;&nbsp;&nbsp;</span>' : '______' ?>.</span></td>
        </tr>
    </table>
    <table class="table table-bordered">
        <tr>
            <td class="pull-left">
            <span style="font-size: 12px;">Scholastic Area</span>
            </td>
            <?php
            if (customCompute($marksettings[1])) {
            ?>
                <th class="" colspan=7>
                    <span style="font-size: 12px;">Term-1 (100 Marks)</span>
                </th>
            <?php
            }
            if (customCompute($marksettings[2]) && ($termID==0 || $termID==2 || $termID==3)) {
            ?>
                <th class="" colspan=7>
                <span style="font-size: 12px;">Term-2 (100 Marks)</span>
                </th>
            <?php
            }
            ?>
            <th>
            </th>
        </tr>
        <tr>
            <th class="pull-left">
                <span style="font-size: 12px;">SUBJECT</span>
            </th>
            <?php
            if (customCompute($marksettings[1])) {
                $text = "";
                foreach ($marksettings[1] as $subjectID => $markpercentageArr) {
                    if ($subjects[$subjectID]->type == 1) {
                        foreach ($markpercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'] as $markpercentageID) {
                            $text .= "<th class='text-center'>";
                            $text .= '<span style="font-size: 12px;">'.(isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->markpercentagetype . " (" . $markpercentages[$markpercentageID]->percentage . ")" : '').'</span>';
                            $text .= "</th>";
                        }
                        break;
                    }
                }
                echo $text;
            ?>
                <th class="pull-left"  style="text-align: center;">
                <span style="font-size: 12px;">Total (100)</span>
                </th>
                <th class="pull-left"  style="text-align: center;">
                <span style="font-size: 12px;">Grade</span>
                </th>
            <?php
            }
            if (customCompute($marksettings[2]) && ($termID==0 || $termID==2 || $termID==3)) {
                $text = "";
                foreach ($marksettings[2] as $subjectID => $markpercentageArr) {
                    if ($subjects[$subjectID]->type == 1) {
                        foreach ($markpercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'] as $markpercentageID) {
                            $text .= "<th class='text-center'>";
                            $text .= '<span style="font-size: 12px;">'.(isset($markpercentages[$markpercentageID]) ? (($markpercentages[$markpercentageID]->markpercentagetype!="Half Yearly Exam")?$markpercentages[$markpercentageID]->markpercentagetype:"Annual Exam") . " (" . $markpercentages[$markpercentageID]->percentage . ")" : '').'</span>';
                            $text .= "</th>";
                        }
                        break;
                    }
                }
                echo $text;
            ?>
                <th class="pull-center" style="text-align: center;">
                <span style="font-size: 12px;">Total (100)</span>
                </th>
                <th class="pull-left"  style="text-align: center;">
                <span style="font-size: 12px;"> Grade</span>
                </th>
            <?php
            }
            ?>
            <th class="pull-left">
            <span style="font-size:11px;">Overall Grade</span>
            </th>
        </tr>
        <?php
        $totalFinalMark      = 0;
        $totalMark           = 0;
        foreach ($subjects as $subjectID => $subject) {
            if ($subject->type == 1) {
                $totalsubjectwiseMark = 0;
                $totalFinalsubjectMark=0;
        ?>
                <tr>
                    <td class="subject"><span style="font-size:11px"> <?= $subject->subject ?></span></td>
                    <?php
                    $text = "";
                    $optionalsubjectID = $student[$student_list->studentID]->sroptionalsubjectID;
                    $totalSubject        = 0;
                    $averagePoint        = 0;
                    $opmarkpercentageArr = [];
                    $examID = 1;
                    if (customCompute($marksettings[1])) {

                        $markpercentageArr = $marksettings[1][$subjectID];
                        if ($subjectID == $optionalsubjectID) {
                            $opmarkpercentageArr = $markpercentageArr;
                        }

                        if (!in_array($subjectID, $optionalsubjectArr)) {
                            $totalSubject++;
                            $subjectfinalmark = isset($subject) ? (int)$subject->finalmark : 0;
                            $totalSubjectMark = 0;
                            $percentageMark   = 0;
                            foreach ($markpercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'] as $markpercentageID) {

                                $f = false;
                                if (isset($markpercentageArr['own']) && in_array($markpercentageID, $markpercentageArr['own'])) {
                                    $f = true;
                                    $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                                    $text .= "<td class='marks-value text-center'><span style='font-size:11px'>";
                                    if (isset($marks[$student_list->studentID][$examID][$subjectID][$markpercentageID]) && $f) {
                                        $mark_val = $marks[$student_list->studentID][$examID][$subjectID][$markpercentageID];
                                        $text .= $mark_val;
                                        // Added check for "AB" text to prevent PHP math errors
                                        if (is_numeric($mark_val)) {
                                            $totalSubjectMark += $mark_val;
                                        }
                                    } else {
                                        if ($f) {
                                            $text .= '&nbsp;';
                                        }
                                    }
                                    $text .= "</span></td>";
                                }
                            }
                            $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);
                            $text .= "<td class='marks-value text-center'><span style='font-size:11px'>";
                            if($totalSubjectMark>0){
                                $text .= $totalSubjectMark;
                                $totalMark        += $totalSubjectMark;
                                $totalFinalMark   += $finalpercentageMark;
                                $totalsubjectwiseMark+= $totalSubjectMark;
                                $totalFinalsubjectMark+= $finalpercentageMark;
                            }else{
                                $text .= '&nbsp;';
                            }
                            
                            $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                            $text .= "</span></td>";
                            if (customCompute($grades)) {
                                foreach ($grades as $grade) {
                                    if (($grade->gradefrom <= floor($totalSubjectMark)) && ($grade->gradeupto >= floor($totalSubjectMark))) {
                                        $text .= "<td class='marks-value text-center'><span style='font-size:11px'>";
                                        if($totalSubjectMark>0){
                                            $text .= $grade->grade;
                                        }else{
                                            $text .= '&nbsp;';
                                        }
                                        $text .= "</span></td>";
                                    }
                                }
                            } else {
                                $text .= "<td class='f-14 marks-value text-center'><span style='font-size:11px'>";
                                $text .= 'N/A';
                                $text .= '</span></td>';
                                $text .= "<td class='f-14 marks-value text-center'><span style='font-size:11px'>";
                                $text .= 'N/A';
                                $text .= '</span></td>';
                            }
                        }
                        echo $text;
                    ?>
                    <?php
                    }
                    $text = "";
                    $optionalsubjectID = $student[$student_list->studentID]->sroptionalsubjectID;
                    $totalSubject        = 0;
                    $averagePoint        = 0;
                    $opmarkpercentageArr = [];
                    $examID = 2;
                    if (customCompute($marksettings[2]) && ($termID==0 || $termID==2 || $termID==3)) {

                        $markpercentageArr = $marksettings[2][$subjectID];
                        if ($subjectID == $optionalsubjectID) {
                            $opmarkpercentageArr = $markpercentageArr;
                        }

                        if (!in_array($subjectID, $optionalsubjectArr)) {
                            $totalSubject++;
                            $subjectfinalmark = isset($subject) ? (int)$subject->finalmark : 0;
                            $totalSubjectMark = 0;
                            $percentageMark   = 0;
                            foreach ($markpercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'] as $markpercentageID) {

                                $f = false;
                                if (isset($markpercentageArr['own']) && in_array($markpercentageID, $markpercentageArr['own'])) {
                                    $f = true;
                                    $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                                    $text .= "<td class='marks-value text-center'><span style='font-size:11px'>";
                                    if (isset($marks[$student_list->studentID][$examID][$subjectID][$markpercentageID]) && $f) {
                                        $mark_val = $marks[$student_list->studentID][$examID][$subjectID][$markpercentageID];
                                        $text .= $mark_val;
                                        // Added check for "AB" text to prevent PHP math errors
                                        if (is_numeric($mark_val)) {
                                            $totalSubjectMark += $mark_val;
                                        }
                                    } else {
                                        if ($f) {
                                            $text .= 'N/A';
                                        }
                                    }
                                    $text .= "</span></td>";
                                }
                            }
                            

                            $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);
                            $text .= "<td class='marks-value text-center'><span style='font-size:11px'>";
                            
                            $totalMark        += $totalSubjectMark;
                            $totalFinalMark   += $finalpercentageMark;
                            $totalsubjectwiseMark+= $totalSubjectMark;
                            
                            $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                            if($totalSubjectMark == 0)
                                $text .= 'AB';
                            else{
                                $totalFinalsubjectMark+= $finalpercentageMark;
                                $text .= $totalSubjectMark;
                            }
                            $text .= "</span></td>";
                            if (customCompute($grades)) {
                                foreach ($grades as $grade) {
                                    if (($grade->gradefrom <= floor($totalSubjectMark)) && ($grade->gradeupto >= floor($totalSubjectMark))) {
                                        $text .= "<td class='marks-value text-center'><span style='font-size:11px'>";
                                        if($totalSubjectMark == 0)
                                            $text .= "AB";
                                        else
                                            $text .= $grade->grade;
                                        $text .= "</span></td>";
                                    }
                                }
                            } else {
                                $text .= "<td class='marks-value text-center'><span style='font-size:11px'>";
                                $text .= 'N/A';
                                $text .= '</span></td>';
                            }
                        }
                        echo $text;
                    ?>
                    <?php
                    }
                    ?>
                    <?php
                    $text = "";
                    $totalmarkpercentage  = markCalculationView($totalsubjectwiseMark, $totalFinalsubjectMark);
                    if (customCompute($grades)) {
                        foreach ($grades as $grade) {
                            if (($grade->gradefrom <= floor($totalmarkpercentage)) && ($grade->gradeupto >= floor($totalmarkpercentage))) {
                                $text .= "<td class='marks-value text-center'><span style='font-size:11px'>";
                                $text .= $grade->grade;
                                $text .= "</span></td>";
                            }
                        }
                    } else {
                        $text .= "<td class='marks-value text-center'><span style='font-size:11px'>";
                        $text .= 'N/A';
                        $text .= '</span></td>';
                    }
                    echo $text;
                    ?>
                </tr>
        <?php
            }
        }
        ?>
        </table>
        <table class="table table-bordered">
        <tr>
            <?php
            if (customCompute($marksettings[1])) {
            ?>
            <th class="pull-left" colspan=7><span style='font-size:11px'>Co-Scholastic Area Term-1 (On a 3-point A-C) grading scale</span></th>
            <th class="pull-left"><span style='font-size:11px'>Grade</span></th>
            <?php
            }
            if (customCompute($marksettings[2]) && ($termID==0 || $termID==2 || $termID==3)) {
            ?>
            <th class="pull-left" colspan=7><span style='font-size:11px'>Co-Scholastic Area Term-2 (On a 3-point A-C) grading scale</span></th>
            <th class="pull-left"><span style='font-size:11px'>Grade</span></th>
            <?php } ?>
        </tr>
        <?php
        foreach ($subjects as $subjectID => $subject) {
            if ($subject->type == 0) {
        ?>
                <tr>
                    <td colspan=7 class="subject_optional"><span style='font-size:11px'><?= $subject->subject ?></span></td>
                    <?php
                    $examID = 1;
                    if (customCompute($marksettings[1])) {
                    ?>
                    <td class='text-center'><span style='font-size:11px'><?= isset($optional_marks[$student_list->studentID][$examID][$subjectID]) ? $optional_marks[$student_list->studentID][$examID][$subjectID] : '' ?></span></td>
                    <?php
                    }
                    ?>
                    <td colspan=7><span style='font-size:11px'><?= $subject->subject ?></span></td>
                    <?php
                    $examID = 2;
                    if (customCompute($marksettings[2]) && ($termID==0 || $termID==2 || $termID==3)) {
                    ?>
                    <td class='text-center'><span style='font-size:11px'><?= isset($optional_marks[$student_list->studentID][$examID][$subjectID]) ? $optional_marks[$student_list->studentID][$examID][$subjectID] : '' ?></span></td>
                    <?php } ?>
                </tr>
        <?php
            }
        }
        ?>
        </table>
        <table class="table table-bordered">
        <tr>
            <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;"><span style='font-size:11px'>
                <?php 
                $totalmarkpercentage  = markCalculationView($totalMark, $totalFinalMark);
                ?>
                Overall Percentage: <?= ini_round($totalmarkpercentage) ?>
                </span>
            </th>
            <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;">
            <span style='font-size:11px'>
                Sign. of Parent
            </span>
            </th>
        </tr>
        <tr>
            <th class="pull-left" colspan=16>
            <span style='font-size:11px'>
                Class Teacher's Remark: <?= ($teacher_input[$student_list->studentID]->remarks!="")?'<span style="border-bottom:1px solid #000">'.$teacher_input[$student_list->studentID]->remarks.'</span>':"_________________________________" ?>
            </span>
            </th>
        </tr>
        <tr>
            <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;">
                <span style='font-size:11px'>
                Result: <?= (ini_round($totalmarkpercentage)>=(in_array($student_list->studentID,[444,443,451,461])?1:1))?"Passed & Promoted to ".$classes->promoted_class." ":"Failed" ?>
                </span>
            </th>
            <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;">
            <span style='font-size:11px'>
                Sign. of Class Teacher
            </span>
            </th>
        </tr>
        <tr>
            <th class="pull-left" colspan=16 style="height:35px;vertical-align: middle;">
                <span style='font-size:11px'>
                School Re-opens on: <?= date('d-m-Y',strtotime($re_open_date)) ?>
                </span>
            </th>
        </tr>
        <tr>
            <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;">
                <span style='font-size:11px'>
                Date: <?= date('d-m-Y',strtotime($generate_date)) ?>
                </span>
            </th>
            <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;">
                <span style='font-size:11px'>
                Sign. of Principal
                </span>
            </th>
        </tr>
    </table>
    <b>
        <span style="font-size:11px">Grade scale for scholastic areas:
        Grades are awarded on a 8-point grading scale as follows
        </span>
        </b>
        <table class="table table-bordered" style="width:80%">
        <tr>
            <th class="text-center" style="width:14%"><span style="font-size:11px">Marks Range</span></th>
            <th class="text-center" style="width:11%"><span style="font-size:11px">91-100</span></th>
            <th class="text-center" style="width:11%"><span style="font-size:11px">81-90</span></th>
            <th class="text-center" style="width:11%"><span style="font-size:11px">71-80</span></th>
            <th class="text-center" style="width:11%"><span style="font-size:11px">61-70</span></th>
            <th class="text-center" style="width:11%"><span style="font-size:11px">51-60</span></th>
            <th class="text-center" style="width:11%"><span style="font-size:11px">41-50</span></th>
            <th class="text-center" style="width:11%"><span style="font-size:11px">33-40</span></th>
            <th class="text-center" style="width:11%"><span style="font-size:11px">32 and below</span></th>
        </tr>
        <tr>
            <th class="text-center"><span style="font-size:11px">Grade</span></th>
            <th class="text-center"><span style="font-size:11px">A1</span></th>
            <th class="text-center"><span style="font-size:11px">A2</span></th>
            <th class="text-center"><span style="font-size:11px">B1</span></th>
            <th class="text-center"><span style="font-size:11px">B2</span></th>
            <th class="text-center"><span style="font-size:11px">C1</span></th>
            <th class="text-center"><span style="font-size:11px">C2</span></th>
            <th class="text-center"><span style="font-size:11px">D</span></th>
            <th class="text-center"><span style="font-size:11px">E</span></th>
        </tr>
    </table>
    <?php
    $student_counting ++;
    if($student_counting != $count_student) {
        echo "<pagebreak />";
    }
    ?>
<?php } } else { ?>
    <div class="notfound">
        <p><?=$this->lang->line('progresscardreport_data_not_found')?></p>
    </div>
<?php } ?>

</body>

</html>
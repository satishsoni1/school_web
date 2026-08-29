<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <table>
        <tr class="borderless">
            <td rowspan=4 class="w-20 borderless"><img class="studentImg" src="<?= pdfimagelink($siteinfos->photo, 'uploads/images/') ?>"></td>
            <th class="text-center borderless f-12">Khalapur Taluka Shikshan Prasarak Mandal's</th>
            <td rowspan=4 class="w-20 borderless"><img class="studentImg" src="<?= pdfimagelink($student->photo) ?>"></td>
        </tr>
        <tr class="borderless">
            <th class="text-center f-16">P.P. GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL, KHOPOLI.</th>
        </tr>
        <tr class="borderless">
            <th class="text-center borderless">UDISE NO.:27240312802</th>
        </tr>
        <tr class="borderless">
            <th class="text-center borderless">PROGRESS REPORT CARD (A.Y.<?= $schoolyear->schoolyear ?>)</th>
        </tr>
    </table>
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
            <td><?= customCompute($classes) ? $classes->classes : '' ?> Roll No.: <?= $student->srroll ?></td>
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
            <td colspan=2>Total Attendance of the student:___<?= $teacher_input->attendance ?>____Total Working Days___<?= customCompute($classes) ? $classes->total_working_days : '' ?>___.</td>
        </tr>
    </table>
    <table class="table table-bordered">
        <tr>
            <th class="pull-left">
                Sholastic Area
            </th>
            <?php
            if (customCompute($marksettings[1])) {
            ?>
                <th colspan=7>
                    Term-1 (100 Marks)
                </th>
            <?php
            }
            if (customCompute($marksettings[2])) {
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
            <th class="pull-left">
                SUBJECT
            </th>
            <?php
            if (customCompute($marksettings[1])) {
                $text = "";
                foreach ($marksettings[1] as $subjectID => $markpercentageArr) {
                    if ($subjects[$subjectID]->type == 1) {
                        foreach ($markpercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'] as $markpercentageID) {
                            $text .= "<th class=' text-center'>";
                            $text .= (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->markpercentagetype . " (" . $markpercentages[$markpercentageID]->percentage . ")" : '');
                            $text .= "</th>";
                        }
                        break;
                    }
                }
                echo $text;
            ?>
                <th class="pull-left">
                    Mark Obtained (100)
                </th>
                <th class="pull-left">
                    Grade
                </th>
            <?php
            }
            if (customCompute($marksettings[2])) {
                $text = "";
                foreach ($marksettings[1] as $subjectID => $markpercentageArr) {
                    if ($subjects[$subjectID]->type == 1) {
                        foreach ($markpercentageArr[(($settingmarktypeID == 4) || ($settingmarktypeID == 6)) ? 'unique' : 'own'] as $markpercentageID) {
                            $text .= "<th class=' text-center'>";
                            $text .= (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->markpercentagetype . " (" . $markpercentages[$markpercentageID]->percentage . ")" : '');
                            $text .= "</th>";
                        }
                        break;
                    }
                }
                echo $text;
            ?>
                <th class="pull-left">
                    Mark Obtained (100)
                </th>
                <th class="pull-left">
                    Grade
                </th>
            <?php
            }
            ?>
            <th class="pull-left">
                Overall Grade
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
                    <td>
                        <?= $subject->subject ?>
                    </td>
                    <?php
                    $text = "";
                    $optionalsubjectID = $student->sroptionalsubjectID;
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
                                    $text .= "<td class=' text-center'>";
                                    if (isset($marks[$examID][$subjectID][$markpercentageID]) && $f) {
                                        $text .= $marks[$examID][$subjectID][$markpercentageID];
                                        $totalSubjectMark += $marks[$examID][$subjectID][$markpercentageID];
                                    } else {
                                        if ($f) {
                                            $text .= 'N/A';
                                        }
                                    }
                                    $text .= "</td>";
                                }
                            }
                            $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);
                            $text .= "<td class=' text-center'>";
                            $text .= $totalSubjectMark;
                            $totalMark        += $totalSubjectMark;
                            $totalFinalMark   += $finalpercentageMark;
                            $totalsubjectwiseMark+= $totalSubjectMark;
                            $totalFinalsubjectMark+= $finalpercentageMark;
                            $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                            $text .= "</td>";
                            if (customCompute($grades)) {
                                foreach ($grades as $grade) {
                                    if (($grade->gradefrom <= floor($totalSubjectMark)) && ($grade->gradeupto >= floor($totalSubjectMark))) {
                                        $text .= "<td class=' text-center'>";
                                        $text .= $grade->grade;
                                        $text .= "</td>";
                                    }
                                }
                            } else {
                                $text .= "<td class=' text-center'>";
                                $text .= 'N/A';
                                $text .= '</td>';
                                $text .= "<td class=' text-center'>";
                                $text .= 'N/A';
                                $text .= '</td>';
                            }
                        }
                        echo $text;
                    ?>
                    <?php
                    }
                    $text = "";
                    $optionalsubjectID = $student->sroptionalsubjectID;
                    $totalSubject        = 0;
                    $averagePoint        = 0;
                    $opmarkpercentageArr = [];
                    $examID = 2;
                    if (customCompute($marksettings[2])) {

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
                                    $text .= "<td class=' text-center'>";
                                    if (isset($marks[$examID][$subjectID][$markpercentageID]) && $f) {
                                        $text .= $marks[$examID][$subjectID][$markpercentageID];
                                        $totalSubjectMark += $marks[$examID][$subjectID][$markpercentageID];
                                    } else {
                                        if ($f) {
                                            $text .= 'N/A';
                                        }
                                    }
                                    $text .= "</td>";
                                }
                            }
                            $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);
                            $text .= "<td class=' text-center'>";
                            $text .= $totalSubjectMark;
                            $totalMark        += $totalSubjectMark;
                            $totalFinalMark   += $finalpercentageMark;
                            $totalsubjectwiseMark+= $totalSubjectMark;
                            $totalFinalsubjectMark+= $finalpercentageMark;
                            $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                            $text .= "</td>";
                            if (customCompute($grades)) {
                                foreach ($grades as $grade) {
                                    if (($grade->gradefrom <= floor($totalSubjectMark)) && ($grade->gradeupto >= floor($totalSubjectMark))) {
                                        $text .= "<td class=' text-center'>";
                                        $text .= $grade->grade;
                                        $text .= "</td>";
                                    }
                                }
                            } else {
                                $text .= "<td class=' text-center'>";
                                $text .= 'N/A';
                                $text .= '</td>';
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
                    // $text .= "<td>";
                    // $text .= $totalmarkpercentage;
                    // $text .= '</td>';
                    if (customCompute($grades)) {
                        foreach ($grades as $grade) {
                            if (($grade->gradefrom <= floor($totalmarkpercentage)) && ($grade->gradeupto >= floor($totalmarkpercentage))) {
                                $text .= "<td class=' text-center'>";
                                $text .= $grade->grade;
                                $text .= "</td>";
                            }
                        }
                    } else {
                        $text .= "<td class=' text-center'>";
                        $text .= 'N/A';
                        $text .= '</td>';
                    }
                    echo $text;
                    ?>
                </tr>
        <?php
            }
        }
        ?>
        <tr>
            <th colspan=16>
            </th>
        </tr>
        <tr>
            <?php
            if (customCompute($marksettings[1])) {
            ?>
            <th class="pull-left" colspan=7>Co-Scholastic Area Term-1 (On a 3-point A-C) grading scale</th>
            <th class="pull-left">Grade</th>
            <?php
            }
            if (customCompute($marksettings[2])) {
            ?>
            <th class="pull-left" colspan=7>Co-Scholastic Area Term-2 (On a 3-point A-C) grading scale</th>
            <th class="pull-left">Grade</th>
            <?php } ?>
        </tr>
        <?php
        foreach ($subjects as $subjectID => $subject) {
            if ($subject->type == 0) {
        ?>
                <tr>
                    <td colspan=7><?= $subject->subject ?></td>
                    <?php
                    $examID = 1;
                    if (customCompute($marksettings[1])) {
                    ?>
                    <td class=' text-center'><?= $optional_marks[$examID][$subjectID] ?></td>
                    <?php
                    }
                    ?>
                    <td colspan=7><?= $subject->subject ?></td>
                    <?php
                    $examID = 2;
                    if (customCompute($marksettings[2])) {
                    ?>
                    <td class=' text-center'><?= $optional_marks[$examID][$subjectID] ?></td>
                    <?php } ?>
                </tr>
        <?php
            }
        }
        ?>
        <tr>
            <th colspan=16>
            </th>
        </tr>
        <tr>
            <th class="pull-left" colspan=8>
                <?php 
                
                $totalmarkpercentage  = markCalculationView($totalMark, $totalFinalMark);
                ?>
                
                Overall Percentage: <?= ini_round($totalmarkpercentage) ?>
                <br/>
            </th>
            <th class="pull-left" colspan=8>
                <br/>
                Sign. of Parent
            </th>
        </tr>
        <tr>
            <th class="pull-left" colspan=16>
                Class Teacher's Remark: ____________<?= $teacher_input->remarks ?>_____________________
            </th>
        </tr>
        <tr>
            <th class="pull-left" colspan=8>
                Result: <?= (ini_round($totalmarkpercentage)>=36)?"Passed & PROMOTED TO ".str_replace(" A","",str_replace(" B","",$class_numeric[$classes->classes_numeric+1])):"Failed" ?>
            </th>
            <th class="pull-left" colspan=8>
                Sign. of Class Teacher
            </th>
        </tr>
        <tr>
            <th class="pull-left" colspan=16>
                Schhol Re-opens on: <?= date('d-m-Y',strtotime($re_open_date)) ?>
            </th>
        </tr>
        <tr>
            <th class="pull-left" colspan=8>
                Date: <?= date('d-m-Y',strtotime($generate_date)) ?>
                <br/>
            </th>
            <th class="pull-left" colspan=8>
                <br/>
                Sign. of Principal
            </th>
        </tr>
    </table>
    <br/>
        <b>Grade scale for scholastic areas:
        <br/>
        Grades are awarded on a 8-point grading scale as follows
        </b>
    
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


</body>

</html>
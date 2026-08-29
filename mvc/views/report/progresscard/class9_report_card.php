<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <?php if (customCompute($students)) {
        $count_student = count($students);
        $student_counting = 0;

        foreach ($students as $student_list) {
            // Initialize totals for this specific student
            $student_obtained_marks = 0;
            $student_max_marks = 0;
    ?>

            <table class="header-table">
                <tr>
                    <td rowspan="4" style="width: 15%;"><img style="max-height: 80px;" src="<?= pdfimagelink($siteinfos->photo, 'uploads/images/') ?>"></td>
                    <th style="font-size: 14px; text-align: center;">Khalapur Taluka Shikshan Prasarak Mandal's</th>
                    <td rowspan="4" style="width: 15%; text-align:right;"><img style="max-height: 80px;" src="<?= pdfimagelink($student[$student_list->studentID]->photo) ?>"></td>
                </tr>
                <tr>
                    <th style="font-size: 18px; text-align: center;">P.P. GAGANGIRI MAHARAJ INTERNATIONAL SCHOOL, KHOPOLI.</th>
                </tr>
                <tr>
                    <th style="font-size: 12px; text-align: center;">AFFILIATION NO.:1131395 | UDISE NO.:27240312802 | SCHOOL CODE:31384</th>
                </tr>
                <tr>
                    <th style="font-size: 16px; text-align: center; padding-top: 5px;">PROGRESS REPORT CARD (A.Y.<?= $schoolyear->schoolyear ?>)</th>
                </tr>
            </table>

            <table class="table table-bordered" style="width: 100%; margin-bottom: 10px;">
                <tr>
                    <td class="text-left font-bold" style="width: 20%;">Student Name</td>
                    <td class="text-left" style="width: 30%;"><?= $student[$student_list->studentID]->srname ?></td>
                    <td class="text-left font-bold" style="width: 20%;">Roll No.</td>
                    <td class="text-left" style="width: 30%;"><?= $student[$student_list->studentID]->srroll ?></td>
                </tr>
                <tr>
                    <td class="text-left font-bold">Mother's Name</td>
                    <td class="text-left"><?= $student[$student_list->studentID]->mother_name ?></td>
                    <td class="text-left font-bold">G.R. No.</td>
                    <td class="text-left"><?= $student[$student_list->studentID]->srregisterNO ?></td>
                </tr>
                <tr>
                    <td class="text-left font-bold">Father's Name</td>
                    <td class="text-left"><?= $student[$student_list->studentID]->father_name ?></td>
                    <td class="text-left font-bold">Date of Birth</td>
                    <td class="text-left"><?= date('d-m-Y', strtotime($student[$student_list->studentID]->dob)) ?></td>
                </tr>
                <tr>
                    <td class="text-left font-bold">Grade & Section</td>
                    <td class="text-left"><?= $classes->classes ?></td>
                    <td class="text-left font-bold">Attendance</td>
                    <?php $attendance_array = calculate_attendace_total($student_list->studentID, $schoolyear->schoolyearID); ?>
                    <td class="text-left"><?= isset($attendance_array['present']) ? $attendance_array['present'] : 0 ?> / <?= (($student_list->studentID==2632)?" 99":$classes->total_working_days) ?></td>
                </tr>
            </table>

            <table class="table table-bordered" style="width: 100%; margin-bottom: 10px;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th rowspan="2" class="align-middle" style="vertical-align:middle;width:12%">Subject Name</th>
                        <th colspan="4">Periodic Tests</th>
                        <th colspan="4">Internal Assessments</th>
                        <th colspan="3">Consolidated</th>
                    </tr>
                    <tr style="background-color: #f9f9f9;">
                        <th style="width:8%;font-size: 11px;">PT I<br>(5)</th>
                        <th style="width:8%;font-size: 11px;">PT II<br>(5)</th>
                        <th style="width:8%;font-size: 11px;">PT III<br>(5)</th>
                        <th style="width:8%;font-size: 11px;">Average of best<br>two PT (out of 5)<br>(A)</th>
                        <th style="width:8%;font-size: 11px;">Note Book<br>(5)<br>(B)</th>
                        <th style="width:8%;font-size: 11px;">Portfolio<br>(5)<br>(C)</th>
                        <th style="width:8%;font-size: 11px;">Subject Enrichment<br>(5)<br>(D)</th>
                        <th style="width:8%;font-size: 11px;">Total<br>(20)<br>(E=A+B+C+D)</th>
                        <th style="width:8%;font-size: 11px;">Annual exam<br>(80)<br>(F)</th>
                        <th style="width:8%;font-size: 11px;">Total<br>(100)<br>(E+F)</th>
                        <th style="width:8%;font-size: 11px;">Grade</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
$examID = 1; // Assuming Term 1 / Annual
foreach ($subjects as $subjectID => $subject) {
    if ($subject->type == 1 && strpos(strtolower($subject->subject), 'information technology') === false) { 
        
        $row = isset($class9_marks_data[$student_list->studentID][$examID][$subjectID]) ? $class9_marks_data[$student_list->studentID][$examID][$subjectID] : null;

        // 1. Fetch raw inputted values (preserves "AB" or numbers like 30, 80)
        $pt1_input = ($row && $row->pt1 !== '') ? $row->pt1 : 0;
        $pt2_input = ($row && $row->pt2 !== '') ? $row->pt2 : 0;
        $pt3_input = ($row && $row->pt3 !== '') ? $row->pt3 : 0;
        
        $nb_disp  = ($row && $row->notebook !== '') ? $row->notebook : 0;
        $pf_disp  = ($row && $row->portfolio !== '') ? $row->portfolio : 0;
        $se_disp  = ($row && $row->enrichment !== '') ? $row->enrichment : 0;
        $ann_disp = ($row && $row->annual !== '') ? $row->annual : 0;

        // 2. Safely extract the raw numbers (treats "AB" as 0 for math)
        $pt1_raw = is_numeric($pt1_input) ? (float)$pt1_input : 0;
        $pt2_raw = is_numeric($pt2_input) ? (float)$pt2_input : 0;
        $pt3_raw = is_numeric($pt3_input) ? (float)$pt3_input : 0;
        
        // 3. CONVERT TO OUT OF 5 AND ROUND UP TO NEXT NUMBER (ceil)
        // 3. CONVERT TO OUT OF 5 AND ROUND TO NEAREST NUMBER
$pt1_math = round(5 * (($pt1_raw * 100) / 30) / 100);
$pt2_math = round(5 * (($pt2_raw * 100) / 80) / 100);
$pt3_math = round(5 * (($pt3_raw * 100) / 30) / 100);

// Convert the rest to float for safe math and round them as well
$nb_math  = is_numeric($nb_disp) ? round((float)$nb_disp) : 0;
$pf_math  = is_numeric($pf_disp) ? round((float)$pf_disp) : 0;
$se_math  = is_numeric($se_disp) ? round((float)$se_disp) : 0;
$ann_math = is_numeric($ann_disp) ? round((float)$ann_disp) : 0;

// Math: Average of best two PTs (rounding the average)
$pt_array = [$pt1_math, $pt2_math, $pt3_math];
rsort($pt_array); 
$best_two_avg = round(($pt_array[0] + $pt_array[1]) / 2);



        // 4. Format for display (Show "AB" if absent, otherwise show the rounded-up converted mark)
        $pt1_print = (strtoupper($pt1_input) === 'AB' || strtoupper($pt1_input) === 'NA') ? strtoupper($pt1_input) : $pt1_math;
        $pt2_print = (strtoupper($pt2_input) === 'AB' || strtoupper($pt2_input) === 'NA') ? strtoupper($pt2_input) : $pt2_math;
        $pt3_print = (strtoupper($pt3_input) === 'AB' || strtoupper($pt3_input) === 'NA') ? strtoupper($pt3_input) : $pt3_math;

        // Totals
        $internal_total = $best_two_avg + $nb_math + $pf_math + $se_math;
        $grand_total = $internal_total + $ann_math;

        // Add to student's cumulative score for overall percentage calculation
        $student_obtained_marks += $grand_total;
        $student_max_marks += 100;

        // Calculate Grade
        $grade_letter = 'E';
        foreach ($grades as $grade) {
            if ($grand_total >= $grade->gradefrom && $grand_total <= $grade->gradeupto) {
                $grade_letter = $grade->grade;
                break;
            }
        }
        ?>
        <tr>
            <td class="text-left font-bold"><?= $subject->subject ?></td>
            
            <td class="text-center"><?= $pt1_print ?></td>
            <td class="text-center"><?= $pt2_print ?></td>
            <td class="text-center"><?= $pt3_print ?></td>
            
            <td class="font-bold text-center"><?= $best_two_avg ?></td>
            <td class="text-center"><?= (strtoupper($nb_disp) === 'AB' || strtoupper($nb_disp) === 'NA') ? strtoupper($nb_disp) : $nb_math ?></td>
            <td class="text-center"><?= (strtoupper($pf_disp) === 'AB' || strtoupper($pf_disp) === 'NA') ? strtoupper($pf_disp) : $pf_math ?></td>
            <td class="text-center"><?= (strtoupper($se_disp) === 'AB' || strtoupper($se_disp) === 'NA') ? strtoupper($se_disp) : $se_math ?></td>
            <td class="font-bold text-center"><?= $internal_total ?></td>
            <td class="text-center"><?= (strtoupper($ann_disp) === 'AB' || strtoupper($ann_disp) === 'NA') ? strtoupper($ann_disp) : $ann_math ?></td>
            <td class="font-bold text-center"><?= $grand_total ?></td>
            <td class="font-bold text-center"><?= $grade_letter ?></td>
        </tr>
<?php } } ?>


                    <?php
                    foreach ($subjects as $subjectID => $subject) {
                        if (strpos(strtolower($subject->subject), 'information technology') !== false) {
                            $row = isset($class9_marks_data[$student_list->studentID][$examID][$subjectID]) ? $class9_marks_data[$student_list->studentID][$examID][$subjectID] : null;

                            // Raw values for display
                            $th_disp = ($row && $row->it_theory !== '') ? $row->it_theory : 0;
                            $pr_disp = ($row && $row->it_practical !== '') ? $row->it_practical : 0;

                            // Safe math values
                            $th_math = is_numeric($th_disp) ? (float)$th_disp : 0;
                            $pr_math = is_numeric($pr_disp) ? (float)$pr_disp : 0;

                            $it_total = $th_math + $pr_math;

                            // Add to student's cumulative score
                            $student_obtained_marks += $it_total;
                            $student_max_marks += 100;

                            $it_grade = 'E';
                            foreach ($grades as $grade) {
                                if ($it_total >= $grade->gradefrom && $it_total <= $grade->gradeupto) {
                                    $it_grade = $grade->grade;
                                    break;
                                }
                            }
                    ?>
                            <tr>
                                <td class="text-left font-bold" rowspan="2"><?= $subject->subject ?></td>
                                <td colspan="4" class="text-center">Theory (50)</td>
                                <td colspan="5" class="text-center">Practical (50)</td>
                                <td class="text-center">Total (100)</td>
                                <td class="font-bold text-center">Grade</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center"><?= (strtoupper($th_disp) === 'AB' || strtoupper($th_disp) === 'NA') ? strtoupper($th_disp) : round($th_disp, 0) ?></td>
                                <td colspan="5" class="text-center"><?= (strtoupper($pr_disp) === 'AB' || strtoupper($pr_disp) === 'NA') ? strtoupper($pr_disp) : round($pr_disp, 0) ?></td>
                                <td class="text-center"><?= round($it_total, 0) ?></td>
                                <td class="text-center"><?= $it_grade ?></td>
                            </tr>
                    <?php }
                    } ?>
                </tbody>
            </table>

            <table style="width: 100%; border: none; margin-top: 10px;">
                <tr>
                    <td style="width: 48%; vertical-align: top; border: none; padding: 0;">
                        <table class="table table-bordered" style="width: 100%;">
                            <tr style="background-color: #f2f2f2;">
                                <th class="text-left">Co-Scholastic Areas (On a 5-point(A-E) Grading Scale)</th>
                                <th>Grade</th>
                            </tr>
                            <?php foreach ($subjects as $subjectID => $subject) {
                                if ($subject->type == 0) {
                                    $row = isset($class9_marks_data[$student_list->studentID][$examID][$subjectID]) ? $class9_marks_data[$student_list->studentID][$examID][$subjectID] : null;
                                    $op_grade = $row && $row->co_scholastic_grade ? $row->co_scholastic_grade : '-';
                            ?>
                                    <tr>
                                        <td class="text-left"><?= $subject->subject ?></td>
                                        <td class="font-bold text-center"><?= $op_grade ?></td>
                                    </tr>
                            <?php }
                            } ?>
                        </table>
                    </td>
                </tr>
            </table>

            <table class="table table-bordered" style="width: 100%;">
                <tr style="background-color: #f2f2f2;">
                    <th rowspan="2">Grading Scale</th>
                    <th>MARKS RANGE</th>
                    <td>91-100</td>
                    <td>81-90</td>
                    <td>71-80</td>
                    <td>61-70</td>
                    <td>51-60</td>
                    <td>41-50</td>
                    <td>33-40</td>
                    <td>32 & Below</td>
                </tr>
                <tr style="background-color: #f2f2f2;">
                    <th>GRADES</th>
                    <td>A1</td>
                    <td>A2</td>
                    <td>B1</td>
                    <td>B2</td>
                    <td>C1</td>
                    <td>C2</td>
                    <td>D</td>
                    <td>E (Failed)</td>
                </tr>
            </table>

            <table class="table table-bordered">
                <tr>
                    <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;">
                        <span style='font-size:12px'>
                            <?php
                            // Dynamically calculate the percentage based on the loop's math
                            $totalmarkpercentage = ($student_max_marks > 0) ? ($student_obtained_marks / $student_max_marks) * 100 : 0;
                            ?>
                            Overall Percentage: <?= ini_round($totalmarkpercentage) ?>%
                        </span>
                    </th>
                    <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;">
                        <span style='font-size:12px'>Sign. of Parent</span>
                    </th>
                </tr>
                <tr>
                    <th class="pull-left" colspan=16>
                        <span style='font-size:12px'>
                            Class Teacher's Remark: <?= (isset($teacher_input[$student_list->studentID]) && $teacher_input[$student_list->studentID]->remarks != "") ? '<span style="border-bottom:1px solid #000">' . $teacher_input[$student_list->studentID]->remarks . '</span>' : "_________________________________" ?>
                        </span>
                    </th>
                </tr>
                <tr>
                    <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;">
                        <span style='font-size:12px'>
                            <?php
                            // Fix Promotion Logic: 33% is standard passing. Assuming $class_numeric handles the next class text.
                            $is_passed = (ini_round($totalmarkpercentage) >= 33);
                            $promoted_class = str_replace([" A", " B"], "", $classes->promoted_class);
                            ?>
                            Result: <?= $is_passed ? "Passed & Promoted to " . $promoted_class : "Failed" ?>
                        </span>
                    </th>
                    <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;">
                        <span style='font-size:12px'>Sign. of Class Teacher</span>
                    </th>
                </tr>
                <tr>
                    <th class="pull-left" colspan=16 style="height:35px;vertical-align: middle;">
                        <span style='font-size:12px'>
                            School Re-opens on: <?= date('d-m-Y', strtotime($re_open_date)) ?>
                        </span>
                    </th>
                </tr>
                <tr>
                    <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;">
                        <span style='font-size:12px'>
                            Date: <?= date('d-m-Y', strtotime($generate_date)) ?>
                        </span>
                    </th>
                    <th class="pull-left" colspan=8 style="height:35px;vertical-align: middle;">
                        <span style='font-size:12px'>Sign. of Principal</span>
                    </th>
                </tr>
            </table>
    <?php
            $student_counting++;
            if ($student_counting != $count_student) {
                echo "<pagebreak />";
            }
        }
    } ?>
</body>

</html>
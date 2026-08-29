<?php
// Student General Variables
$studentName = isset($student->name) ? $student->name : '';
$className   = isset($classes->classes_without_div) ? $classes->classes_without_div : '';
$schoolYear  = isset($schoolyear->schoolyear) ? $schoolyear->schoolyear : '';
$rollNo      = isset($student->roll) ? $student->roll : '';
$addmission_no      = isset($student->registerNO) ? $student->registerNO : '';
$dob         = isset($student->dob) ? $student->dob : (isset($student->dateofbirth) ? $student->dateofbirth : '');
$parentName  = isset($student->father_name) ? $student->father_name : (isset($student->parent_name) ? $student->parent_name : '');
$contactNo   = isset($student->phone) ? $student->phone : (isset($student->mobileno) ? $student->mobileno : '');
$address     = isset($student->address) ? $student->address : '';

// Holistic Specific Data
$ambition       = isset($holistic->ambition)         ? $holistic->ambition         : '';
$bestFriend     = isset($holistic->best_friend)      ? $holistic->best_friend      : '';
$favColour      = isset($holistic->fav_colour)       ? $holistic->fav_colour       : '';
$favFood        = isset($holistic->fav_food)         ? $holistic->fav_food         : '';
$teacherNotes   = isset($holistic->teacher_remarks)  ? $holistic->teacher_remarks  : '';
$peerNotes   = isset($holistic->peerNotes)  ? $holistic->peerNotes  : '';
$selfNotes   = isset($holistic->selfNotes)  ? $holistic->selfNotes  : '';
$teacherEvidence = isset($holistic->teacher_evidence) ? $holistic->teacher_evidence : '';
$portfolioImg   = isset($holistic->portfolio_snapshot) ? $holistic->portfolio_snapshot : '';

// Health data (already decoded by controller into $health array)
$healthT1Ft     = isset($health['t1']['ft'])     ? $health['t1']['ft']     : '';
$healthT1Weight = isset($health['t1']['weight']) ? $health['t1']['weight'] : '';
$healthT2Ft     = isset($health['t2']['ft'])     ? $health['t2']['ft']     : '';
$healthT2Weight = isset($health['t2']['weight']) ? $health['t2']['weight'] : '';

// Assessment arrays (already decoded by controller)
$selfT1   = isset($self_assessment['t1'])  ? array_values($self_assessment['t1'])  : array('', '', '');
$peerT1   = isset($peer_assessment['t1'])  ? array_values($peer_assessment['t1'])  : array('', '', '');
$parentT1 = isset($parent_feedback['t1']) ? array_values($parent_feedback['t1']) : array('', '', '', '');

// FIX: controller passes both $competencies and $comps (alias), and both $annual_summary and $summary (alias)
// We normalise here so the rest of the file can safely use $comps and $summary.
if (!isset($comps)   || !is_array($comps))   $comps   = isset($competencies)   && is_array($competencies)   ? $competencies   : array();
if (!isset($summary) || !is_array($summary)) $summary = isset($annual_summary) && is_array($annual_summary) ? $annual_summary : array();

// feel_at_school already decoded by controller into $feel_at_school array
if (!isset($feel_at_school) || !is_array($feel_at_school)) $feel_at_school = array();


// Calculate Age safely
$ageYears = '';
if (!empty($dob)) {
    $ts = strtotime($dob);
    if ($ts !== false) {
        $ageYears = (string) date_diff(
            date_create(date('Y-m-d', $ts)),
            date_create(date('Y-m-d'))
        )->y;
    }
}

// Global UI Helper Functions
if (!function_exists('hpc_esc')) {
    function hpc_esc($value)
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
if (!function_exists('print_comp')) {
    function print_comp($comps, $key, $term)
    {
        echo (isset($comps[$key][$term]) && $comps[$key][$term] !== '')
            ? hpc_esc($comps[$key][$term])
            : '–';
    }
}
if (!function_exists('print_tick')) {
    function print_tick($summary, $key, $level)
    {
        if (isset($summary[$key]) && $summary[$key] === $level) {
            echo '<span style="color:var(--green); font-weight:800; font-size:18px;">✔</span>';
        }
    }
}
if (!function_exists('smiley_style')) {
    function smiley_style($actual, $target)
    {
        if ($actual === $target) {
            return 'background-color: #d4edda; border-color: #28a745; box-shadow: 0 0 5px rgba(40,167,69,0.3);';
        }
        return '';
    }
}
if (!function_exists('feel_mark')) {
    function feel_mark($saved_array, $index, $target_value)
    {
        if (isset($saved_array[$index]) && (string)$saved_array[$index] === $target_value) {
            return '<span style="color:var(--green); font-size:16px;">⬤</span>';
        }
        return '◯';
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= hpc_esc($studentName); ?> - Holistic Progress Card</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@400;600;700;800&family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <style>
        :root {
            --red: #d71f2b;
            --red-dark: #b5141f;
            --orange: #f28c1b;
            --orange-soft: #ffd8a6;
            --green: #0ea64b;
            --green-soft: #ddf8e7;
            --blue: #2f2d92;
            --blue-soft: #e4e6ff;
            --paper: #fffdf8;
            --bg: #f1ece2;
            --ink: #2d1d22;
            --line: #ead7b9;
        }

        * {
            box-sizing: border-box;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: var(--bg);
            color: var(--ink);
            font-family: "Baloo 2", sans-serif;
            font-size: 19px;
            line-height: 1.42;
        }

        .page {
            width: 210mm;
            height: 297mm;
            margin: 5mm auto;
            background: var(--paper);
            border: 1px solid #eddfca;
            border-radius: 4mm;
            overflow: hidden;
            position: relative;
            box-shadow: 0 10px 28px rgba(0, 0, 0, .18);
            page-break-after: always;
            break-after: page;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .page:last-of-type {
            page-break-after: auto;
            break-after: auto;
        }

        .page::before,
        .page::after {
            content: "";
            position: absolute;
            border-radius: 999px;
            z-index: 0;
            opacity: .13;
            pointer-events: none;
        }

        .page::before {
            width: 120mm;
            height: 120mm;
            right: -30mm;
            top: -40mm;
            background: radial-gradient(circle, var(--green), transparent 70%);
        }

        .page::after {
            width: 100mm;
            height: 100mm;
            left: -20mm;
            bottom: -35mm;
            background: radial-gradient(circle, var(--orange), transparent 72%);
        }

        .inner {
            position: relative;
            z-index: 1;
            padding: 8mm;
            height: 100%;
            display: flex;
            flex-direction: column;
            gap: 1.2mm;
        }

        .inner::before {
            content: "";
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            width: 50mm;
            height: 50mm;
            background-image: url("https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_1400,h_1400,al_c,q_100,enc_auto/PPGMIS%20logo_edited.png");
            background-repeat: no-repeat;
            background-position: center;
            background-size: contain;
            opacity: 0.2;
            pointer-events: none;
            z-index: 3;
        }

        .inner>* {
            position: relative;
            z-index: 2;
        }

        .head {
            display: grid;
            grid-template-columns: 24mm 1fr 32mm;
            gap: 3mm;
            align-items: center;
            border: 2px solid #ffc28a;
            border-radius: 5mm;
            background: linear-gradient(120deg, var(--red-dark), var(--red));
            color: #fff;
            padding: 4mm;
        }

        .logo {
            height: 22mm;
            border-radius: 4mm;
            border: 2px solid var(--orange);
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1mm;
        }

        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .school {
            text-align: center;
        }

        .school h1 {
            margin: .4mm 0;
            font-family: "Fredoka", sans-serif;
            font-size: 22px;
            line-height: 1.08;
            letter-spacing: .2px;
            color: #fff;
        }

        .school p {
            margin: .2mm 0;
            font-size: 11.2px;
            font-weight: 700;
            line-height: 1.2;
        }

        .school .trust-line {
            font-size: 10.5px;
            color: #ffe9bd;
            letter-spacing: .3px;
            font-weight: 800;
        }

        .school .school-line {
            text-transform: uppercase;
        }

        .school .meta-line {
            font-size: 10px;
            color: #ffd59a;
            font-weight: 700;
            letter-spacing: .2px;
        }

        .tag {
            text-align: center;
            background: #fff;
            color: var(--red-dark);
            border: 2px solid var(--orange);
            border-radius: 999px;
            font-size: 14px;
            font-weight: 800;
            padding: 2mm 1mm;
        }

        .title {
            margin-top: 4mm;
            border: 2px dashed #f7b35e;
            background: linear-gradient(135deg, #fff, #fff4df);
            border-radius: 5mm;
            text-align: center;
            padding: 5mm;
        }

        .title h2 {
            margin: 0;
            font-family: "Fredoka", sans-serif;
            color: var(--red-dark);
            font-size: 46px;
            line-height: .95;
            letter-spacing: .2px;
        }

        .title p {
            margin: 2mm 0 0;
            font-size: 14px;
            font-weight: 700;
            color: #8a4300;
        }

        .bar {
            margin-top: 3mm;
            border-radius: 999px;
            font-family: "Fredoka", sans-serif;
            font-size: 15px;
            color: #fff;
            padding: 1.5mm 4mm;
            font-weight: 700;
            letter-spacing: .2px;
            background: linear-gradient(90deg, var(--blue), #4744b9);
        }

        .grid2 {
            margin-top: 3mm;
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 3mm;
        }

        .cover-grid {
            margin-top: 4mm;
            display: grid;
            grid-template-columns: 1.28fr .92fr;
            gap: 3mm;
            align-items: start;
        }

        .card {
            background: #fff;
            border: 1.5px solid var(--line);
            border-radius: 4mm;
            padding: 3mm;
        }

        .card h3 {
            margin: 0 0 1.2mm;
            font-family: "Fredoka", sans-serif;
            color: var(--blue);
            font-size: 20px;
        }

        .row {
            display: flex;
            align-items: flex-end;
            gap: 2mm;
            font-size: 13.5px;
            font-weight: 700;
            border-bottom: 1px dashed #d8c4a8;
            padding: 1.5mm 0;
        }

        .row_1 {
            display: flex;
            align-items: flex-end;
            gap: 2mm;
            font-size: 13.5px;
            font-weight: 700;
            padding: 1.5mm 0;
        }

        .line {
            flex: 1;
            border-bottom: 1.4px solid #9f8a70;
            min-height: 3.8mm;
        }

        .value {
            flex: 1;
            border-bottom: 1.4px solid #9f8a70;
            min-height: 3.8mm;
            font-weight: 800;
            color: #2d1d22;
            padding-left: 2mm;
        }

        .photo {
            border: 2px dashed var(--orange);
            border-radius: 4mm;
            background: #fff8ef;
            min-height: 38mm;
            display: grid;
            place-items: center;
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            color: #8b6e4d;
            overflow: hidden;
        }

        .passport-photo {
            width: 35mm;
            height: 45mm;
            margin: 2mm auto 4mm;
            border: 2px dashed var(--orange);
            border-radius: 3mm;
            background: #fff8ef;
            display: grid;
            place-items: center;
            text-align: center;
            font-size: 10px;
            font-weight: 700;
            color: #8b6e4d;
            padding: 2mm;
            overflow: hidden;
        }

        .chips {
            display: flex;
            flex-wrap: wrap;
            gap: 1.2mm;
            margin-top: 1.5mm;
        }

        .chip {
            border: 1.5px solid #cdeed9;
            background: #f0fff6;
            color: #0d7d40;
            border-radius: 999px;
            padding: 1.1mm 3mm;
            font-size: 12px;
            font-weight: 700;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2mm;
            font-size: 14px;
        }

        th {
            border: 1px solid #ead4b0;
            padding: 1.8mm 1.6mm;
            text-align: center;
        }
        td {
            border: 1px solid #ead4b0;
            padding: 1.8mm 1.6mm;
            text-align: left;
            font-weight: 700;
        }

        th {
            background: #ffe6ca;
            color: #7b3900;
            font-weight: 800;
            font-size: 13px;
        }

        th:first-child,
        td:first-child {
            text-align: left;
            font-weight: 700;
        }

        .domain {
            margin-top: 2.2mm;
            border: 1.5px solid #ecd7b9;
            border-radius: 4mm;
            overflow: hidden;
            background: #fff;
        }

        .domain .h {
            background: linear-gradient(90deg, var(--orange), #ffb456);
            color: #5e3200;
            font-size: 13.5px;
            font-weight: 800;
            padding: 1.8mm 2.8mm;
        }

        .domain .b {
            padding: 2mm;
            font-size: 12px;
        }

        .signs {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2.2mm;
            margin-top: 3mm;
        }

        .sig {
            border: 1.5px solid #e8d5b7;
            border-radius: 3mm;
            min-height: 21mm;
            padding: 2.2mm;
            font-size: 12px;
            font-weight: 700;
            background: #fffdf9;
        }

        .sig .line {
            margin-top: 8mm;
            border-bottom: 1.4px solid #9f8a70;
        }

        .foot {
            margin-top: auto;
            text-align: center;
            font-size: 11px;
            color: #7b6c53;
            font-weight: 700;
        }

        /* Assessment Tables */
        /* .assess-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 0.95em; }
    .assess-table th { background-color: #d9d9d9; color: #333; padding: 10px 5px; text-align: center; font-weight: 600; font-size: 0.85em; border: 1px solid #fff; }
    .assess-table th:first-child { background-color: transparent; border: none; }
    .assess-table td { padding: 10px 8px; vertical-align: middle; border-bottom: 1px solid #eaeaea; }
    .assess-table .question-col { text-align: left; width: 45%; font-weight: 500; color: #444; } */
        .assess-table .smiley-col {
            text-align: center;
            width: 13.75%;
        }

        .smiley-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border: 1px solid #bbb;
            border-radius: 50%;
            font-size: 1.3em;
            background-color: #fcfcfc;
            color: #555;
        }

        /* Feel at School */
        .feel-wrap {
            margin-top: 2mm;
            border: 1.5px solid var(--line);
            border-radius: 4mm;
            overflow: hidden;
            background: #fff;
        }

        .feel-head {
            background: linear-gradient(90deg, var(--green), #2bc067);
            color: #fff;
            font-family: "Fredoka", sans-serif;
            font-size: 15px;
            font-weight: 700;
            padding: 2mm 3.4mm;
        }

        .feel-sub {
            font-size: 14px;
            color: #1f6a42;
            background: #edfdf3;
            padding: 1.7mm 3.4mm;
            font-weight: 600;
            border-bottom: 1px solid #d4ecd9;
        }

        .feel-table {
            margin-top: 0;
            font-size: 14px;
        }

        .feel-table th {
            background: #e6f8ed;
            color: #1d663e;
            font-size: 14px;
            padding: 1.6mm;
        }

        .feel-table td {
            padding: 1.4mm;
        }

        .feel-table td:first-child {
            font-weight: 700;
        }

        .feel-mark {
            font-size: 12px;
            letter-spacing: .2px;
            color: #6f5f49;
        }

        /* Floating Art */
        .art-strip {
            margin-top: 1.8mm;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 2mm;
        }

        .art-icon {
            width: 16mm;
            height: 10mm;
            display: inline-block;
            filter: drop-shadow(0 1px 1px rgba(0, 0, 0, .12));
        }

        .art-float {
            position: absolute;
            z-index: 1;
            pointer-events: none;
            opacity: .85;
        }

        .art-float.left {
            left: 3mm;
            bottom: 6mm;
            width: 12mm;
            height: 12mm;
        }

        .art-float.right {
            right: 3mm;
            bottom: 8mm;
            width: 14mm;
            height: 14mm;
        }

        .print-btn {
            position: fixed;
            right: 16px;
            bottom: 16px;
            border: 0;
            border-radius: 999px;
            background: var(--red-dark);
            color: #fff;
            font-family: "Fredoka", sans-serif;
            font-size: 13px;
            font-weight: 700;
            padding: 10px 18px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .25);
            cursor: pointer;
            z-index: 999;
        }

        @page {
            size: A4 portrait;
            margin: 0;
        }

        @media print {

            html,
            body {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: var(--bg) !important;
            }

            .page {
                width: 210mm !important;
                height: 297mm !important;
                margin: 0 !important;
                border: none !important;
                border-radius: 0 !important;
                box-shadow: none !important;
                page-break-after: always !important;
                break-after: page !important;
                overflow: hidden !important;
            }

            .page:last-of-type {
                page-break-after: auto !important;
                break-after: auto !important;
            }

            .inner {
                padding: 8mm !important;
                gap: 1.2mm !important;
            }

            .print-btn {
                display: none !important;
            }
        }
    </style>
</head>

<body>

    <!-- ═══════════════════════════════════════════════════════════ PAGE 1 – COVER -->
    <section class="page">
        <div class="inner">
            <svg class="art-float left" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="42" fill="#ffd24d" />
                <circle cx="38" cy="42" r="5" fill="#7b4c00" />
                <circle cx="62" cy="42" r="5" fill="#7b4c00" />
                <path d="M30 60 Q50 78 70 60" fill="none" stroke="#7b4c00" stroke-width="6" stroke-linecap="round" />
            </svg>
            <svg class="art-float right" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 80 C30 40,70 40,90 80" stroke="#ff6b6b" stroke-width="8" fill="none" />
                <path d="M15 80 C33 48,67 48,85 80" stroke="#f28c1b" stroke-width="8" fill="none" />
                <path d="M20 80 C36 56,64 56,80 80" stroke="#0ea64b" stroke-width="8" fill="none" />
                <path d="M25 80 C39 63,61 63,75 80" stroke="#2f2d92" stroke-width="8" fill="none" />
            </svg>
            <header class="head">
                <div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_122,h_122,al_c,q_85,enc_avif,quality_auto/PPGMIS%20logo_edited.png" alt="PPGMIS Logo"></div>
                <div class="school">
                    <p class="trust-line">Khalapur Taluka Shikshan Prasarak Mandal's</p>
                    <h1 class="school-line">P.P. Gagangiri Maharaj International School</h1>
                    <p class="meta-line">AFFILIATION NO.:1131395 | UDISE NO.:27240312802 | SCHOOL CODE:31384</p>
                </div>
                <div class="tag">Class: <?= hpc_esc($className); ?></div>
            </header>
            <div class="art-strip">
                <svg class="art-icon" viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="22" cy="22" r="16" fill="#ffd24d" />
                    <g stroke="#ffd24d" stroke-width="4">
                        <line x1="22" y1="1" x2="22" y2="12" />
                        <line x1="22" y1="32" x2="22" y2="43" />
                        <line x1="1" y1="22" x2="12" y2="22" />
                        <line x1="32" y1="22" x2="43" y2="22" />
                    </g>
                    <rect x="56" y="20" width="42" height="14" rx="6" fill="#bde8ff" />
                    <circle cx="68" cy="20" r="10" fill="#bde8ff" />
                </svg>
                <svg class="art-icon" viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg">
                    <rect x="16" y="34" width="66" height="18" rx="5" fill="#ffd24d" />
                    <polygon points="82,34 102,43 82,52" fill="#ff6f3c" />
                    <rect x="12" y="36" width="8" height="14" rx="2" fill="#ff6f3c" />
                </svg>
            </div>
            <div class="title">
                <h2>HOLISTIC<br>PROGRESS CARD</h2>
                <p style="font-size: 22px;">Academic Year: <?= hpc_esc($schoolYear); ?></p>
            </div>
            <div class="cover-grid">
                <div class="card">
                    <h3>Student Information</h3>
                    <div class="row">Student Name <span class="value"><?= hpc_esc($studentName); ?></span></div>
                    <?php if (in_array($classes->classesID, [5, 13, 24, 4, 11, 23, 6, 7, 8, 15, 16, 20])) { ?>
                        <div class="row">Admission No. <span class="value"><?= hpc_esc($addmission_no); ?></span></div>
                    <?php } ?>
                    <div class="row">Roll No. <span class="value"><?= hpc_esc($rollNo); ?></span></div>
                    <div class="row">Date of Birth <span class="value"><?= (!empty($dob) && strtotime($dob)) ? hpc_esc(date('d-m-Y', strtotime($dob))) : hpc_esc($dob); ?></span></div>
                    <div class="row">Parent/Guardian Name <span class="value"><?= hpc_esc($parentName); ?></span></div>
                    <div class="row">Contact No. <span class="value"><?= hpc_esc($contactNo); ?></span></div>
                    <div class="row">Address <span class="value"><?= hpc_esc($address); ?></span></div>
                    <div class="bar">My Interests</div>
                    <div class="chips">
                        <?php if (is_array($interests) && count($interests) > 0):
                            foreach ($interests as $interest):
                                echo '<span class="chip">' . hpc_esc($interest) . '</span>';
                            endforeach;
                        else: ?>
                            <span style="font-size:12px; color:#999; padding-left:2mm;">No interests selected</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card">
                    <h3>Photograph</h3>
                    <div class="passport-photo">
                        <img style="width:100%; height:100%; object-fit:cover;" src="<?= pdfimagelink($student->photo) ?>" alt="Student Photo" />
                    </div>
                </div>
            </div>
            <div class="card" style="margin-top:3mm;">
                <h3>Health Record</h3>
                <table>
                    <!-- <tr><th>Measurement</th><th style="width:4.5rem;">Term 1</th><th style="width:4.5rem;">Term 2</th></tr>
        <tr><td>Height (Ft/Cm)</td><td><?= hpc_esc($healthT1Ft); ?></td><td><?= hpc_esc($healthT2Ft); ?></td></tr>
        <tr><td>Weight (Kg)</td><td><?= hpc_esc($healthT1Weight); ?></td><td><?= hpc_esc($healthT2Weight); ?></td></tr> -->
                    <tr>
                        <th>Measurement</th>
                        <th style="width:4.5rem;">Term 2</th>
                    </tr>
                    <tr>
                        <td>Height (Ft/Cm)</td>
                        <td><?= hpc_esc($healthT2Ft); ?></td>
                    </tr>
                    <tr>
                        <td>Weight (Kg)</td>
                        <td><?= hpc_esc($healthT2Weight); ?></td>
                    </tr>
                </table>
            </div>
            <div class="foot">Page 1 / 17 – Cover</div>
        </div>
    </section>

    <!-- ══════════════════════════════════════════ PAGE 2 – GENERAL INFORMATION -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_122,h_122,al_c,q_85,enc_avif,quality_auto/PPGMIS%20logo_edited.png" alt="PPGMIS Logo"></div>
                <div class="school">
                    <h1>Part A – General Information</h1>
                    <p>To be filled by teacher in consultation with parents</p>
                </div>
                <div class="tag">Page 2</div>
            </header>
            <div class="bar">Attendance Record</div>
            <table style="text-align:center; border:1px solid #ccc;">
                <thead>
                    <tr style="background:#f5f5f5;">
                        <th>Month</th>
                        <?php foreach (['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan', 'Feb', 'Mar'] as $name) echo "<th>$name</th>"; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Working Days</strong></td>
                        <?php foreach (['04', '05', '06', '07', '08', '09', '10', '11', '12', '01', '02', '03'] as $m): ?>
                            <td><?= isset($attendance_report[$m]['working']) ? $attendance_report[$m]['working'] : '–' ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td><strong>Days Present</strong></td>
                        <?php foreach (['04', '05', '06', '07', '08', '09', '10', '11', '12', '01', '02', '03'] as $m): ?>
                            <td><?= isset($attendance_report[$m]['present']) ? $attendance_report[$m]['present'] : '–' ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <td><strong>Attendance %</strong></td>
                        <?php foreach (['04', '05', '06', '07', '08', '09', '10', '11', '12', '01', '02', '03'] as $m): ?>
                            <td><?= (isset($attendance_report[$m]['working']) && $attendance_report[$m]['working'] > 0) ? $attendance_report[$m]['percentage'] . '%' : '–' ?></td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>

            <div class="grid2">
                <div class="card">
                    <h3>All About Me</h3>
                    <div class="row">My birthday is on <span class="value"><?= (!empty($dob) && strtotime($dob)) ? hpc_esc(date('d-m-Y', strtotime($dob))) : hpc_esc($dob); ?></span></div>
                    <div class="row">I am <span class="value"><?= hpc_esc($ageYears); ?></span> years old</div>
                    <div class="row">I live in <span class="value"><?= hpc_esc($address); ?></span></div>
                    <div class="row">My favourite colour is <span class="value"><?= hpc_esc($favColour); ?></span></div>
                    <div class="row">My favourite food is <span class="value"><?= hpc_esc($favFood); ?></span></div>
                    <div class="row">I want to be <span class="value"><?= hpc_esc($ambition); ?></span></div>
                    <div class="row">My best friend is <span class="value"><?= hpc_esc($bestFriend); ?></span></div>
                </div>
                <div class="card">
                    <h3>Portfolio Snapshot</h3>
                    <div class="photo" style="min-height:72mm; display:flex; justify-content:center; align-items:center;">
                        <?php if ($portfolioImg != ''): ?>
                            <img src="<?= base_url('uploads/holistic_photos/' . hpc_esc($portfolioImg)) ?>" style="max-width:100%; height:72mm; object-fit:cover;" alt="Portfolio" />
                        <?php else: ?>
                            Paste/Draw Work Sample
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="feel-wrap">
                <div class="feel-head">How Do I Feel At School?</div>
                <div class="feel-sub">Circle or tick the most appropriate option for each sentence.</div>
                <table class="feel-table">
                    <tr>
                        <th>Statement</th>
                        <th>Yes</th>
                        <th>Sometimes</th>
                        <th>No</th>
                        <th>Not Sure</th>
                    </tr>
                    <?php
                    $statements = [
                        "1. I can talk about how I feel (happy, upset, or angry).",
                        "2. I can calm myself down during difficult situations.",
                        "3. I can understand how my friends feel.",
                        "4. I respect everyone's opinions.",
                        "5. I can help my friends after a fight.",
                        "6. When someone is sad, I can make them feel better.",
                        "7. I think I do well at school."
                    ];
                    foreach ($statements as $index => $stmt): ?>
                        <tr>
                            <td><?= hpc_esc($stmt) ?></td>
                            <td class="feel-mark" style="text-align:center;"><?= feel_mark($feel_at_school, $index, 'Yes') ?></td>
                            <td class="feel-mark" style="text-align:center;"><?= feel_mark($feel_at_school, $index, 'Sometimes') ?></td>
                            <td class="feel-mark" style="text-align:center;"><?= feel_mark($feel_at_school, $index, 'No') ?></td>
                            <td class="feel-mark" style="text-align:center;"><?= feel_mark($feel_at_school, $index, 'Not sure') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="foot">Page 2 / 17 – General Information</div>
        </div>
    </section>
    <!-- ══════════════════════════════════════════════════════════════
     PAGE 1 – LANGUAGE R1 (English)
══════════════════════════════════════════════════════════════ -->
    <?php
    $LOGO = "https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_122,h_122,al_c,q_85,enc_avif,quality_auto/PPGMIS%20logo_edited.png";
    $AFF = "";
    ?>

    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>

                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 3</div>
            </header>

            <div class="bar">Language (English)</div>

            <div class="domain">
                <div class="h">CG1 – Develops oral language skills using complex sentence structures to understand and communicate ideas coherently</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C1.1 &nbsp; Goals Converses fluently and meaningfully indifferent contexts</td>
                            <td><?php print_comp($comps, '1', 't1'); ?></td>
                            <td><?php print_comp($comps, '1', 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.2 &nbsp; Summarises core ideas from material read out in class</td>
                            <td><?php print_comp($comps, '2', 't1'); ?></td>
                            <td><?php print_comp($comps, '2', 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.3 &nbsp; Makes oral presentations (show and tell, short welcome notes, anchoring of small events, short speeches, class debates)</td>
                            <td><?php print_comp($comps, '3', 't1'); ?></td>
                            <td><?php print_comp($comps, '3', 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="domain mt">
                <div class="h">CG2 – Develops the ability to read with comprehension by gaining a basic understanding of different forms of familiar and unfamiliar texts (such as prose and poetry)</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C2.1 &nbsp; Applies varied comprehension strategies (inferring, predicting, visualising) to understand different texts</td>
                            <td><?php print_comp($comps, '4', 't1'); ?></td>
                            <td><?php print_comp($comps, '4', 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.2 &nbsp; Understands main ideas and draws essential conclusions from the material read</td>
                            <td><?php print_comp($comps, '5', 't1'); ?></td>
                            <td><?php print_comp($comps, '5', 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="domain mt">
                <div class="h">CG3 – Develops the ability to write simple and compound sentence structures to express their understanding and experiences</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C3.1 &nbsp; Uses writing strategies, such as sequencing, identifying headings/sub-headings, the beginning, and ending, and forming paragraphs</td>
                            <td><?php print_comp($comps, '6', 't1'); ?></td>
                            <td><?php print_comp($comps, '6', 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.2 &nbsp; Writes clear and coherent paragraphs that convey their understanding of a given topic/concept or on a reading of a text</td>
                            <td><?php print_comp($comps, '7', 't1'); ?></td>
                            <td><?php print_comp($comps, '7', 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.3 &nbsp; Creates posters, invites, simple poems, stories, and dialogues with appropriate information and purpose</td>
                            <td><?php print_comp($comps, '8', 't1'); ?></td>
                            <td><?php print_comp($comps, '8', 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.4 &nbsp; Uses appropriate grammar and structure in their writing</td>
                            <td><?php print_comp($comps, '9', 't1'); ?></td>
                            <td><?php print_comp($comps, '9', 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>




            <div class="foot">Page 3 / 17</div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════
     PAGE 2 – LANGUAGE R2 (Hindi)
══════════════════════════════════════════════════════════════ -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 4</div>
            </header>
            <div class="bar">Language - English (continued)</div>
            <div class="domain mt">
                <div class="h">CG4 – Acquires a more comprehensive range of words in various contexts (of home and school experience) through different sources</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C4.1 &nbsp; Discusses meanings of words and develops vocabulary by listening to and reading a variety of texts</td>
                            <td><?php print_comp($comps, '10', 't1'); ?></td>
                            <td><?php print_comp($comps, '10', 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C4.2 &nbsp; Discusses meanings of words and develops vocabulary by listening to and reading a variety of texts or other content areas</td>
                            <td><?php print_comp($comps, '11', 't1'); ?></td>
                            <td><?php print_comp($comps, '11', 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="domain mt">
                <div class="h">CG5 – Develops interest and preferences in reading</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C5.1 &nbsp; Borrows books from the library regularly to read at home</td>
                            <td><?php print_comp($comps, '12', 't1'); ?></td>
                            <td><?php print_comp($comps, '12', 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C5.2 &nbsp; Demonstrates interest in reading books from the library</td>
                            <td><?php print_comp($comps, '13', 't1'); ?></td>
                            <td><?php print_comp($comps, '13', 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="bar">Language (Hindi)</div>

            <?php
            /* R2 competency IDs start at 14 */
            $r2_base = 13; // offset so R2 Hindi starts at 14
            $r2_ids = function ($n) use ($r2_base) {
                return (string)($r2_base + $n);
            };
            ?>

            <div class="domain">
                <div class="h">CG1 – Sustains effective communication skills for day-to-day interactions, enhancing their oral ability to express ideas</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C1.1 &nbsp; Listens to poems, stories, and conversations and locates important ideas in them</td>
                            <td><?php print_comp($comps, $r2_ids(1), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(1), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.2 &nbsp; Comprehends narrated/read out stories and identifies characters, storyline, and key aspects</td>
                            <td><?php print_comp($comps, $r2_ids(2), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(2), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.3 &nbsp; Converses meaningfully and coherently</td>
                            <td><?php print_comp($comps, $r2_ids(3), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(3), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.4 &nbsp; Makes oral presentations and participates in group discussions</td>
                            <td><?php print_comp($comps, $r2_ids(4), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(4), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="foot">Page 4 / 17</div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════
     PAGE 3 – LANGUAGE R2 (Marathi)
══════════════════════════════════════════════════════════════ -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 5</div>
            </header>
            <div class="bar">Language - Hindi (continued)</div>
            <div class="domain mt">
                <div class="h">CG2 – Develops fluency in reading and the ability to read with comprehension</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C2.1 &nbsp; Develops phonological awareness further by blending phonemes/syllables into words and segmenting words into phonemes/syllables</td>
                            <td><?php print_comp($comps, $r2_ids(5), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(5), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.2 &nbsp; Examines the basic structure of the text and recognises words and sentences in print and basic punctuation marks</td>
                            <td><?php print_comp($comps, $r2_ids(6), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(6), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.3 &nbsp; Reads stories and passages fluently and accurately with appropriate pauses</td>
                            <td><?php print_comp($comps, $r2_ids(7), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(7), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.4 &nbsp; Comprehends the meaning of stories, poems, and story posters</td>
                            <td><?php print_comp($comps, $r2_ids(8), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(8), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.5 &nbsp; Demonstrates interest in picking up and reading a variety of children's books</td>
                            <td><?php print_comp($comps, $r2_ids(9), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(9), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="domain mt">
                <div class="h">CG3 – Develops the ability to express understanding, experiences, feelings, and ideas in writing</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C3.1 &nbsp; Writes a paragraph to express understanding and experiences</td>
                            <td><?php print_comp($comps, $r2_ids(10), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(10), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.2 &nbsp; Creates simple posters, invites, and instructions with appropriate information and purpose</td>
                            <td><?php print_comp($comps, $r2_ids(11), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(11), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.3 &nbsp; Writes stories, poems, and conversations based on imagination and experiences</td>
                            <td><?php print_comp($comps, $r2_ids(12), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(12), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="domain mt">
                <div class="h">CG4 – Develops a wide range of vocabulary in various contexts and through different sources</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C4.1 &nbsp; Discusses meanings of words and develops vocabulary by listening to and reading a variety of texts or other content areas</td>
                            <td><?php print_comp($comps, $r2_ids(13), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2_ids(13), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>



            <div class="foot">Page 5 / 17</div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════
     PAGE 4 – MATHEMATICS
══════════════════════════════════════════════════════════════ -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 6</div>
            </header>
            <div class="bar">Language - Marathi</div>

            <?php $r2m_base = 26;
            $r2m_ids = function ($n) use ($r2m_base) {
                return (string)($r2m_base + $n);
            }; ?>

            <div class="domain">
                <div class="h">CG1 – Sustains effective communication skills for day-to-day interactions, enhancing their oral ability to express ideas</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C1.1 &nbsp; Listens to poems, stories, and conversations and locates important ideas in them</td>
                            <td><?php print_comp($comps, $r2m_ids(1), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(1), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.2 &nbsp; Comprehends narrated/read out stories and identifies characters, storyline, and key aspects</td>
                            <td><?php print_comp($comps, $r2m_ids(2), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(2), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.3 &nbsp; Converses meaningfully and coherently</td>
                            <td><?php print_comp($comps, $r2m_ids(3), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(3), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.4 &nbsp; Makes oral presentations and participates in group discussions</td>
                            <td><?php print_comp($comps, $r2m_ids(4), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(4), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="domain mt">
                <div class="h">CG2 – Develops fluency in reading and the ability to read with comprehension</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C2.1 &nbsp; Develops phonological awareness further by blending phonemes/syllables into words and segmenting words into phonemes/syllables</td>
                            <td><?php print_comp($comps, $r2m_ids(5), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(5), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.2 &nbsp; Examines the basic structure of the text and recognises words and sentences in print and basic punctuation marks</td>
                            <td><?php print_comp($comps, $r2m_ids(6), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(6), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.3 &nbsp; Reads stories and passages fluently and accurately with appropriate pauses</td>
                            <td><?php print_comp($comps, $r2m_ids(7), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(7), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.4 &nbsp; Comprehends the meaning of stories, poems, and story posters</td>
                            <td><?php print_comp($comps, $r2m_ids(8), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(8), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.5 &nbsp; Demonstrates interest in picking up and reading a variety of children's books</td>
                            <td><?php print_comp($comps, $r2m_ids(9), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(9), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="domain mt">
                <div class="h">CG3 – Develops the ability to express understanding, experiences, feelings, and ideas in writing</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C3.1 &nbsp; Writes a paragraph to express understanding and experiences</td>
                            <td><?php print_comp($comps, $r2m_ids(10), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(10), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.2 &nbsp; Creates simple posters, invites, and instructions with appropriate information and purpose</td>
                            <td><?php print_comp($comps, $r2m_ids(11), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(11), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.3 &nbsp; Writes stories, poems, and conversations based on imagination and experiences</td>
                            <td><?php print_comp($comps, $r2m_ids(12), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(12), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="foot">Page 6 / 17</div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════
     PAGE 5 – THE WORLD AROUND US (Part 1)
══════════════════════════════════════════════════════════════ -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 7</div>
            </header>
            <div class="bar">Language - Marathi (continued)</div>
            
            <div class="domain mt">
                <div class="h">CG4 – Develops a wide range of vocabulary in various contexts and through different sources</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C4.1 &nbsp; Discusses meanings of words and develops vocabulary by listening to and reading a variety of texts or other content areas</td>
                            <td><?php print_comp($comps, $r2m_ids(13), 't1'); ?></td>
                            <td><?php print_comp($comps, $r2m_ids(13), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="bar">Mathematics</div>

            <?php
            /* Math IDs: for 3-lang classes start at 40, for 4-lang at 53
Here we use the comp_list index from build_comp_list_for_class()
For a 3-lang class: Math starts at index 40 (13+13+13+1)
We show with placeholder variable $math_base for easy swap */
            $math_base = 39; // 3-lang: indices 40-58 | change to 52 for 4-lang
            $m = function ($n) use ($math_base) {
                return (string)($math_base + $n);
            };
            ?>

            <div class="domain">
                <div class="h">CG1 – Understands numbers, represents whole numbers using the Indian place value system, carries out four basic operations, and discovers patterns in number sequences</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C1.1 &nbsp; Represents numbers using the place value structure of the Indian number system, compares whole numbers, and knows and can read the names of very large numbers</td>
                            <td><?php print_comp($comps, $m(1), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(1), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.2 &nbsp; Represents and compares commonly used fractions in daily life (such as ½, ¼, etc.) as parts of unit wholes, as locations on number lines, and as divisions of whole numbers</td>
                            <td><?php print_comp($comps, $m(2), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(2), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.3 &nbsp; Understands and visualises arithmetic operations and their relationships, knows addition and multiplication tables at least up to 10×10 (pahade) and applies the four basic operations on whole numbers to solve daily life problems</td>
                            <td><?php print_comp($comps, $m(3), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(3), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.4 &nbsp; Recognises, describes, and extends simple number patterns such as odd numbers, even numbers, square numbers, cubes, powers of 2, powers of 10, and Virahanka–Fibonacci numbers</td>
                            <td><?php print_comp($comps, $m(4), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(4), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>



            <div class="foot">Page 7 / 17</div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════
     PAGE 6 – THE WORLD AROUND US (Part 2) + CG4–CG7
══════════════════════════════════════════════════════════════ -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 8</div>
            </header>
            <div class="bar">Mathematics (continued)</div>
            <div class="domain mt">
                <div class="h">CG2 – Analyses characteristics and properties of 2D and 3D shapes, specifies locations, describes spatial relationships, and creates shapes with symmetry</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C2.1 &nbsp; Identifies, compares, and analyses attributes of two- and three-dimensional shapes and develops vocabulary to describe their attributes/properties</td>
                            <td><?php print_comp($comps, $m(5), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(5), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.2 &nbsp; Describes location and movement using both common language and mathematical vocabulary; understands the notion of map (najri naksha)</td>
                            <td><?php print_comp($comps, $m(6), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(6), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.3 &nbsp; Recognises and creates symmetry (reflection, rotation) in familiar 2D and 3D shapes</td>
                            <td><?php print_comp($comps, $m(7), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(7), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.4 &nbsp; Discovers, recognises, describes, and extends patterns in 2D and 3D shapes</td>
                            <td><?php print_comp($comps, $m(8), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(8), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="domain mt">
                <div class="h">CG3 – Understands measurable attributes of objects and the units, systems, and processes of measurement</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C3.1 &nbsp; Measures in non-standard and standard units and evaluates the need for standard units</td>
                            <td><?php print_comp($comps, $m(9), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(9), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.2 &nbsp; Uses an appropriate unit and tool for the attribute (like length, perimeter, time, weight, volume) being measured</td>
                            <td><?php print_comp($comps, $m(10), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(10), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.3 &nbsp; Carries out simple unit conversions, such as from centimetres to metres, within a system of measurement</td>
                            <td><?php print_comp($comps, $m(11), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(11), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.4 &nbsp; Understands the definition and formula for the area of a square or rectangle as length times breadth</td>
                            <td><?php print_comp($comps, $m(12), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(12), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.5 &nbsp; Devises strategies for estimating distance, length, time, perimeter, area, weight and volume and verifies using standard units</td>
                            <td><?php print_comp($comps, $m(13), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(13), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.6 &nbsp; Deduces that shapes having equal areas can have different perimeters and shapes having equal perimeters can have different areas</td>
                            <td><?php print_comp($comps, $m(14), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(14), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.7 &nbsp; Evaluates the conservation of attributes like length and volume and solves daily-life problems related to them</td>
                            <td><?php print_comp($comps, $m(15), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(15), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>





            <div class="foot">Page 8 / 17</div>
        </div>
    </section>


    <!-- ══════════════════════════════════════════════════════════════
     PAGE 7 – ART EDUCATION + PHYSICAL EDUCATION
══════════════════════════════════════════════════════════════ -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 9</div>
            </header>
            <div class="bar">Mathematics (continued)</div>
            <div class="domain mt">
                <div class="h">CG4 – Develops problem-solving skills with procedural fluency to solve mathematical puzzles and daily-life problems</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C4.1 &nbsp; Solves puzzles and daily-life problems involving one or more operations on whole numbers (including word puzzles and puzzles from 'recreational' areas, such as the construction of magic squares)</td>
                            <td><?php print_comp($comps, $m(16), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(16), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C4.2 &nbsp; Learns to systematically count and list all possible permutations or combination given a constraint, in simple situations</td>
                            <td><?php print_comp($comps, $m(17), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(17), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C4.3 &nbsp; Selects appropriate methods and tools for computing with whole numbers, such as mental computation, estimation, or paper pencil calculation, in accordance with the context</td>
                            <td><?php print_comp($comps, $m(18), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(18), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="domain mt">
                <div class="h">CG5 – Knows and appreciates the development in India of the decimal place value system</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C5.1 &nbsp; Understand the development of zero in India and the Indian place value system for writing numerals, the history of its transmission to the world, and its modern impact on our lives and in all technology</td>
                            <td><?php print_comp($comps, $m(19), 't1'); ?></td>
                            <td><?php print_comp($comps, $m(19), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            


            <div class="foot">Page 9 / 17</div>
        </div>
    </section>
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 10</div>
            </header>
            <div class="bar">The World Around Us</div>

            <?php
            $w_base = 58; // 3-lang: World starts at 59 | 4-lang: change to 71
            $w = function ($n) use ($w_base) {
                return (string)($w_base + $n);
            };
            ?>

            <div class="domain">
                <div class="h">CG1 – Explores and engages with the natural and socio-cultural environment in their surroundings</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C1.1 &nbsp; Observes and identifies the natural (insects, plants, birds, animals, geographical features, sun and moon, stars, planets, natural resources) and social (houses, relationships) components in their immediate environment</td>
                            <td><?php print_comp($comps, $w(1), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(1), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.2 &nbsp; Describes relationships (including between humans and animals/nature) and traditions (art forms, celebrations, festivals) in the family and community</td>
                            <td><?php print_comp($comps, $w(2), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(2), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.3 &nbsp; Asks questions and makes predictions about simple patterns (season change, food chain, phases of the moon, movement of stars and planets, shapes of trees, plants, leaves, and flowers, rituals, celebrations) observed in the immediate environment</td>
                            <td><?php print_comp($comps, $w(3), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(3), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.4 &nbsp; Explains the functioning of local institutions (family, school, bank/post office, market, and panchayat) in different forms (story, drawing, tabulating data, reports), and analyses their roles</td>
                            <td><?php print_comp($comps, $w(4), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(4), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.5 &nbsp; Uses local materials to create simple objects (family tree, envelopes, origami animals) on their own for display or use in classroom processes</td>
                            <td><?php print_comp($comps, $w(5), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(5), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>


            <div class="domain mt">
                <div class="h">CG2 – Understands the interdependence in their environment, developing the basis for appreciation of 'Vasudhaiva Kutumbakam'</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C2.1 &nbsp; Identifies natural and humanmade systems that support their lives (water supply, water cycle, river flow systems, seasons, life cycle of plants and animals, food, household items, transport, communication, electricity in the home)</td>
                            <td><?php print_comp($comps, $w(6), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(6), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.2 &nbsp; Describes the relationship between the natural environment and cultural practices in immediate environment (nature of work, food, festivals, traditions)</td>
                            <td><?php print_comp($comps, $w(7), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(7), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.3 &nbsp; Connects changes in the environment and the lives of family and community, as communicated by elders and through local stories (changes in occupation, food habits, resources, celebrations, communication)</td>
                            <td><?php print_comp($comps, $w(8), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(8), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

           
            

            <div class="foot">Page 10 / 17</div>
        </div>
    </section>
    <!-- ══════════════════════════════════════════════════════════════
     PAGE 8 – SOCIAL EMOTIONAL ETHICAL LEARNING + POSITIVE LEARNING HABITS
══════════════════════════════════════════════════════════════ -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 11</div>
            </header>
            <div class="bar">The World Around Us (continued)</div>
            <div class="domain mt">
                <div class="h">CG3 – Explains how to ensure the safety of self and others in different (normal as well as emergency) situations</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C3.1 &nbsp; Describes the basic safety needs and protection (health and hygiene, food, water, shelter, precautions, awareness of emergency situations, abuse, and unsafe situations) of humans, birds, and animals</td>
                            <td><?php print_comp($comps, $w(9), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(9), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.2 &nbsp; Discusses how to prepare for emergency situations (smoke, fire, small injuries, burns, electrical safety, unseasonal rains, fallen trees) based on discussions with family and community, or personal experiences</td>
                            <td><?php print_comp($comps, $w(10), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(10), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.3 &nbsp; Develops simple labels and slogans, and participates in roleplay on safety and protection in the local environment to be displayed/done in school and locality</td>
                            <td><?php print_comp($comps, $w(11), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(11), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="domain">
                <div class="h">CG4 – Develops sensitivity towards social and natural environment</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C4.1 &nbsp; Observes and describes diversity among plants, and birds and animals in immediate environment (shape, sounds, food habits, growth, habitat)</td>
                            <td><?php print_comp($comps, $w(12), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(12), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C4.2 &nbsp; Observes and describes cultural diversity in their immediate environment (food, clothing, games, different seasons, festivals related to harvest and sowing)</td>
                            <td><?php print_comp($comps, $w(13), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(13), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C4.3 &nbsp; Describes usage of natural resources in their immediate environment</td>
                            <td><?php print_comp($comps, $w(14), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(14), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C4.4 &nbsp; Demonstrates how natural resources can be shared, maintained, and conserved (trees, use of rainwater, benefits of millets)</td>
                            <td><?php print_comp($comps, $w(15), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(15), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C4.5 &nbsp; Identifies needs of plants, birds, and animals, and how they can be supported (water, soil, food, care)</td>
                            <td><?php print_comp($comps, $w(16), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(16), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C4.6 &nbsp; Identifies the needs of people in different situations – in terms of access to resources, equal opportunities, work distribution, and shelter</td>
                            <td><?php print_comp($comps, $w(17), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(17), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C4.7 &nbsp; Learns about basic social and behavioural norms, values, and dispositions that benefit our social and natural environments and that help our society function smoothly (using dustbins, standing in queues, conserving water, using public transportation, keeping one's environment clean, always helping others in need regardless of background)</td>
                            <td><?php print_comp($comps, $w(18), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(18), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="foot">Page 11 / 17</div>
        </div>
    </section>
    <!-- ══════════════════════════════════════════════════════════════
     PAGE 8 – SOCIAL EMOTIONAL ETHICAL LEARNING + POSITIVE LEARNING HABITS
══════════════════════════════════════════════════════════════ -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 12</div>
            </header>
            <div class="bar">The World Around Us (continued)</div>
            <div class="domain mt">
                <div class="h">CG5 – Develops the ability to read and interpret simple maps</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C5.1 &nbsp; Explains a line drawing of their school, village, and ward</td>
                            <td><?php print_comp($comps, $w(19), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(19), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C5.2 &nbsp; Draws a sketch of their school, village, and ward using symbols and directions</td>
                            <td><?php print_comp($comps, $w(20), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(20), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C5.3 &nbsp; Reads simple maps of city, state, and country to identify natural and humanmade features (well, lake, post office, school, hospital) with reference to symbols and directions</td>
                            <td><?php print_comp($comps, $w(21), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(21), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="domain mt">
                <div class="h">CG6 – Uses data and information from various sources to investigate questions related to their immediate environment</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C6.1 &nbsp; Performs simple inquiry related to specific questions independently or in groups</td>
                            <td><?php print_comp($comps, $w(22), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(22), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C6.2 &nbsp; Presents observations and findings through different creative modes (drawing, diagram, poem, play, skit, oral and written expression)</td>
                            <td><?php print_comp($comps, $w(23), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(23), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="domain mt">
                <div class="h">CG7 – Gains foundational familiarity with basic concepts and methods from the natural sciences (life sciences, physical sciences, and earth and space)</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C7.1 &nbsp; Gains familiarity with using the scientific method in investigations, as well as familiarity with other crosscutting concepts such as energy, matter, and systems that apply across the domains of science and engineering</td>
                            <td><?php print_comp($comps, $w(24), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(24), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C7.2 &nbsp; Gains familiarity with disciplinary core ideas in the natural sciences, as well as in engineering, technology, and applications of science, which reflect the content that will be learned across subject areas in later Grades</td>
                            <td><?php print_comp($comps, $w(25), 't1'); ?></td>
                            <td><?php print_comp($comps, $w(25), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="foot">Page 12 / 17</div>
        </div>
    </section>
    <!-- ══════════════════════════════════════════════════════════════
     PAGE 8 – SOCIAL EMOTIONAL ETHICAL LEARNING + POSITIVE LEARNING HABITS
══════════════════════════════════════════════════════════════ -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 13</div>
            </header>
            <!-- ART EDUCATION -->
            <div class="bar">Art Education</div>

            <?php
            $a_base = 83; // 3-lang: Art starts at 84 | 4-lang: change to 96
            $a = function ($n) use ($a_base) {
                return (string)($a_base + $n);
            };
            ?>

            <div class="domain">
                <div class="h">CG1 – Develops an enjoyment of the Arts and exercises their creativity and imagination in Visual and Performing Arts activities</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C1.1 &nbsp; Creates and presents a variety of artwork to communicate their ideas and emotions in any of the Visual and Performing Art forms (emphasis on variety in Music, painting, drawing, crafts, Drama, Dance and Movement, and local Art forms)</td>
                            <td><?php print_comp($comps, $a(1), 't1'); ?></td>
                            <td><?php print_comp($comps, $a(1), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.2 &nbsp; Describes the varied materials, tools, and processes used in the Visual and Performing Arts and demonstrates familiarity with some of these in their own artwork [e.g., identifies and names some musical instruments and demonstrates simple beats on a dholak, khanjira, bells, utensils, or one's own body (clapping, tapping, making different sounds using mouth and voice)]</td>
                            <td><?php print_comp($comps, $a(2), 't1'); ?></td>
                            <td><?php print_comp($comps, $a(2), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.3 &nbsp; Creates artworks collaboratively and shares own thoughts and feelings while responding to arts and culture in their surroundings</td>
                            <td><?php print_comp($comps, $a(3), 't1'); ?></td>
                            <td><?php print_comp($comps, $a(3), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>


            <!-- PHYSICAL EDUCATION -->
            <div class="bar mt">Physical Education</div>

            <?php
            $p_base = 86; // 3-lang: PE starts at 87 | 4-lang: change to 99
            $p = function ($n) use ($p_base) {
                return (string)($p_base + $n);
            };
            ?>

            <div class="domain">
                <div class="h">CG1 – Demonstrates the use of basic skills (running, jumping, catching, throwing, hitting, and kicking) to participate in different physical activities / games / sports</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C1.1 &nbsp; Practices a combination of movement, motor skills, and manipulative skills (catching, throwing, kicking, hitting a ball towards a target while moving, focusing on visual cues to hit the target)</td>
                            <td><?php print_comp($comps, $p(1), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(1), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.2 &nbsp; Moves purposefully their body to a beat / rhythm / music</td>
                            <td><?php print_comp($comps, $p(2), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(2), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.3 &nbsp; Demonstrates coordination abilities with a partner and objects (e.g., being able to move in coordination with a partner in three-legged race, hand-eye coordination while bowling, throwing)</td>
                            <td><?php print_comp($comps, $p(3), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(3), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C1.4 &nbsp; Demonstrates basic warm-up exercises and stretching to develop strength and flexibility in the body</td>
                            <td><?php print_comp($comps, $p(4), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(4), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="foot">Page 13 / 17</div>
        </div>
    </section>
    <!-- ══════════════════════════════════════════════════════════════
     PAGE 8 – SOCIAL EMOTIONAL ETHICAL LEARNING + POSITIVE LEARNING HABITS
══════════════════════════════════════════════════════════════ -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 14</div>
            </header>
            <div class="bar">Physical Education (continued)</div>
            <div class="domain mt">
                <div class="h">CG2 – Develops an awareness of their personal and social behaviour towards themselves and others</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C2.1 &nbsp; Demonstrates the ability to play games and activities which require and emphasise teamwork, cooperation, personal responsibility, and communication of ideas</td>
                            <td><?php print_comp($comps, $p(5), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(5), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.2 &nbsp; Creates group norms and rules of the game/activity before playing and reviews these regularly</td>
                            <td><?php print_comp($comps, $p(6), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(6), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.3 &nbsp; Exhibits sensitivity to injuries of others and acts empathetically when the other player is physically injured, emotionally stressed, and feeling unwell</td>
                            <td><?php print_comp($comps, $p(7), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(7), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.4 &nbsp; Practices care and responsibility towards the physical activity material, playground, and facilities</td>
                            <td><?php print_comp($comps, $p(8), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(8), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C2.5 &nbsp; Identifies characteristics of safe/unsafe touch in the context of physical activity and describes ways of reporting them</td>
                            <td><?php print_comp($comps, $p(9), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(9), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="domain mt">
                <div class="h">CG3 – Demonstrates mental engagement in physical activity / game situations</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C3.1 &nbsp; Explains the concept of some games, their rules, playing positions, and basic moves</td>
                            <td><?php print_comp($comps, $p(10), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(10), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>C3.2 &nbsp; Expresses their emotions and thinking process during the game</td>
                            <td><?php print_comp($comps, $p(11), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(11), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="domain mt">
                <div class="h">CG4 – Develops an understanding of the need to develop themselves and self-assess their progress</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td>C4.1 &nbsp; Sets simple personal goals/targets and records progress (e.g., throwing a ball at 25 m, then 30 m, then 40 m; Jumping 1, 2, 3 feet high/long)</td>
                            <td><?php print_comp($comps, $p(12), 't1'); ?></td>
                            <td><?php print_comp($comps, $p(12), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="foot">Page 14 / 17</div>
        </div>
    </section>
    <!-- ══════════════════════════════════════════════════════════════
     PAGE 8 – SOCIAL EMOTIONAL ETHICAL LEARNING + POSITIVE LEARNING HABITS
══════════════════════════════════════════════════════════════ -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 15</div>
            </header>
            <!-- SEL -->
            <div class="bar">Social Emotional – Ethical Learning</div>

            <?php
            $sel_base = 98; // 3-lang: SEL starts at 99 | 4-lang: change to 112
            $sel = function ($n) use ($sel_base) {
                return (string)($sel_base + $n);
            };
            ?>

            <div class="domain">
                <div class="h">Socio-Emotional Ethical Learning</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Area</th>
                            <th>Observation</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td rowspan="5" class="sel-area" style="vertical-align:middle;text-align:center;width:22mm;">Socio&#8209;Emotional<br />Ethical<br />Learning</td>
                            <td>Has emotional regulation and is able to respond appropriately to various situations</td>
                            <td><?php print_comp($comps, $sel(1), 't1'); ?></td>
                            <td><?php print_comp($comps, $sel(1), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>Displays empathy for all living beings and the environment</td>
                            <td><?php print_comp($comps, $sel(2), 't1'); ?></td>
                            <td><?php print_comp($comps, $sel(2), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>Is inclusive and kind in approach towards all cultural, social, religious and ethnic identities</td>
                            <td><?php print_comp($comps, $sel(3), 't1'); ?></td>
                            <td><?php print_comp($comps, $sel(3), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>Has resilience and displays grit</td>
                            <td><?php print_comp($comps, $sel(4), 't1'); ?></td>
                            <td><?php print_comp($comps, $sel(4), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>Understands ethical implications in all situations</td>
                            <td><?php print_comp($comps, $sel(5), 't1'); ?></td>
                            <td><?php print_comp($comps, $sel(5), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- PLH -->
            <div class="bar mt">Positive Learning Habits</div>

            <?php
            $plh_base = 103; // 3-lang: PLH starts at 104 | 4-lang: change to 117
            $plh = function ($n) use ($plh_base) {
                return (string)($plh_base + $n);
            };
            ?>

            <div class="domain mt">
                <div class="h">Positive Learning Habits</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Area</th>
                            <th>Observation</th>
                            <th style="width:4.5rem">Term 1</th>
                            <th style="width:4.5rem">Term 2</th>
                        </tr>
                        <tr>
                            <td rowspan="7" class="sel-area" style="vertical-align:middle;text-align:center;width:22mm;">Positive<br />Learning<br />Habits</td>
                            <td>Has the mental flexibility to sustain as well as shift attention appropriately</td>
                            <td><?php print_comp($comps, $plh(1), 't1'); ?></td>
                            <td><?php print_comp($comps, $plh(1), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>Asks interesting and relevant questions</td>
                            <td><?php print_comp($comps, $plh(2), 't1'); ?></td>
                            <td><?php print_comp($comps, $plh(2), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>Can articulate one's opinions in a coherent and focused manner</td>
                            <td><?php print_comp($comps, $plh(3), 't1'); ?></td>
                            <td><?php print_comp($comps, $plh(3), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>Has growth mindset – seeks help actively</td>
                            <td><?php print_comp($comps, $plh(4), 't1'); ?></td>
                            <td><?php print_comp($comps, $plh(4), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>Is able to reflect on work done and take suitable action</td>
                            <td><?php print_comp($comps, $plh(5), 't1'); ?></td>
                            <td><?php print_comp($comps, $plh(5), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>Follows classroom norms with agency and understanding</td>
                            <td><?php print_comp($comps, $plh(6), 't1'); ?></td>
                            <td><?php print_comp($comps, $plh(6), 't2'); ?></td>
                        </tr>
                        <tr>
                            <td>Has self-control that enables learning in structured settings</td>
                            <td><?php print_comp($comps, $plh(7), 't1'); ?></td>
                            <td><?php print_comp($comps, $plh(7), 't2'); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="foot">Page 15 / 17</div>
        </div>
    </section>
    <!-- ══════════════════════════════════════════════════════════════
     PAGE 8 – SOCIAL EMOTIONAL ETHICAL LEARNING + POSITIVE LEARNING HABITS
══════════════════════════════════════════════════════════════ -->
    <!-- <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="<?= $LOGO ?>" alt="PPGMIS Logo" /></div>
                <div class="school">
                    <h1>Part B – Records of Progress</h1>
                    <p><?= $AFF ?></p>
                </div>
                <div class="tag">Page 16</div>
            </header>
            
            <div class="foot">Page 16 / 17</div>
        </div>
    </section> -->
    <section class="page">
  <div class="inner">
    <header class="head">
      <div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_122,h_122,al_c,q_85,enc_avif,quality_auto/PPGMIS%20logo_edited.png" alt="PPGMIS Logo"></div>
      <div class="school"><h1>Observations &amp; Assessments</h1><p>Self, Peer, Parent and Teacher Inputs</p></div>
      <div class="tag">Page 16</div>
    </header><br/>
    <div class="bar">Self Assessment &amp; Peer Assessment</div>

    <div class="domain">
      <div class="h">Self Assessment</div>
      <div class="b">
        <table class="assess-table" style="margin-top:0;">
          <thead><tr><th></th><th>Yes</th><th>Sometimes</th><th>No</th><th>Not sure</th></tr></thead>
          <tbody>
            <tr>
              <td class="question-col">I enjoyed all the activities</td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[0],'Yes'); ?>">😃</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[0],'Sometimes'); ?>">😐</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[0],'No'); ?>">🙁</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[0],'Not sure'); ?>">🤔</div></td>
            </tr>
            <tr>
              <td class="question-col">I could complete work independently</td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[1],'Yes'); ?>">😃</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[1],'Sometimes'); ?>">😐</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[1],'No'); ?>">🙁</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[1],'Not sure'); ?>">🤔</div></td>
            </tr>
            <tr>
              <td class="question-col">I followed classroom instructions</td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[2],'Yes'); ?>">😃</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[2],'Sometimes'); ?>">😐</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[2],'No'); ?>">🙁</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($selfT1[2],'Not sure'); ?>">🤔</div></td>
            </tr>
            <tr>
              <td colspan="5">
              <div class="bar">Comment : </div>
    <div class="card" style="margin-top:2mm;">
      <div class="row"><span class="value"><?= hpc_esc($selfNotes); ?></span></div>
    </div>

              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="domain">
      <div class="h">Peer Assessment</div>
      <div class="b">
        <table class="assess-table" style="margin-top:0;">
          <thead><tr><th></th><th>Yes</th><th>Sometimes</th><th>No</th><th>Not sure</th></tr></thead>
          <tbody>
            <tr>
              <td class="question-col">Child collaborates with friends</td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[0],'Yes'); ?>">😃</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[0],'Sometimes'); ?>">😐</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[0],'No'); ?>">🙁</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[0],'Not sure'); ?>">🤔</div></td>
            </tr>
            <tr>
              <td class="question-col">Child shares learning materials</td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[1],'Yes'); ?>">😃</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[1],'Sometimes'); ?>">😐</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[1],'No'); ?>">🙁</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[1],'Not sure'); ?>">🤔</div></td>
            </tr>
            <tr>
              <td class="question-col">Child supports team activities</td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[2],'Yes'); ?>">😃</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[2],'Sometimes'); ?>">😐</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[2],'No'); ?>">🙁</div></td>
              <td class="smiley-col"><div class="smiley-icon" style="<?= smiley_style($peerT1[2],'Not sure'); ?>">🤔</div></td>
            </tr>
            <tr>
              <td colspan="5">
              <div class="bar">Comment : </div>
    <div class="card" style="margin-top:2mm;">
      <div class="row"><span class="value"><?= hpc_esc($peerNotes); ?></span></div>
    </div>

              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <br/>
    <div class="foot">Page 16 / 17 – Assessments &amp; Observations</div>
  </div>
</section>

    <!-- ══════════════════════════════════════════ PAGE 8 – ANNUAL SUMMARY -->
    <section class="page">
        <div class="inner">
            <svg class="art-float right" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                <circle cx="50" cy="50" r="42" fill="#fff4df" />
                <path d="M28 60 L44 76 L74 34" fill="none" stroke="#0ea64b" stroke-width="8" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <header class="head">
                <div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_122,h_122,al_c,q_85,enc_avif,quality_auto/PPGMIS%20logo_edited.png" alt="PPGMIS Logo"></div>
                <div class="school">
                    <h1>Part C – Annual Summary</h1>
                    <p>Holistic summary and sign-off</p>
                </div>
                <div class="tag">Page 17</div>
            </header>

            <div class="bar">Parent / Guardian Observation</div>
            <div class="card" style="margin-top:2mm;">
                <div class="row">My child enjoys <span class="value"><?= hpc_esc($parentT1[0] ?? ''); ?></span></div>
                <div class="row">My child needs support in <span class="value"><?= hpc_esc($parentT1[1] ?? ''); ?></span></div>
                <div class="row">Home resources used (books/games/etc.) <span class="value"><?= hpc_esc($parentT1[2] ?? ''); ?></span></div>
                <div class="row">Parent comments <span class="value"><?= hpc_esc($parentT1[3] ?? ''); ?></span></div>
            </div>

            <div class="bar">Learner’s profile by the Teacher</div>
            <div class="card" style="margin-top:2mm;">
                <div class="row"><span class="value"><?= hpc_esc($teacherNotes); ?></span></div>
                <!-- <div class="row">Activity Evidence <span class="value"><?= hpc_esc($teacherEvidence); ?></span></div> -->
            </div>

            <!-- <div class="bar">Domain-wise Annual Summary</div>
            <table>
                <tr>
                    <th>Domain</th>
                    <th>Beginner</th>
                    <th>Proficient</th>
                    <th>Advanced</th>
                </tr>
                <tr>
                    <td>Physical Development</td>
                    <td style="text-align:center;"><?php print_tick($summary, 'physical', 'Beginner'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'physical', 'Proficient'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'physical', 'Advanced'); ?></td>
                </tr>
                <tr>
                    <td>Socio-Emotional Development</td>
                    <td style="text-align:center;"><?php print_tick($summary, 'socio', 'Beginner'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'socio', 'Proficient'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'socio', 'Advanced'); ?></td>
                </tr>
                <tr>
                    <td>Cognitive Development</td>
                    <td style="text-align:center;"><?php print_tick($summary, 'cognitive', 'Beginner'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'cognitive', 'Proficient'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'cognitive', 'Advanced'); ?></td>
                </tr>
                <tr>
                    <td>Language &amp; Literacy</td>
                    <td style="text-align:center;"><?php print_tick($summary, 'language', 'Beginner'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'language', 'Proficient'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'language', 'Advanced'); ?></td>
                </tr>
                <tr>
                    <td>Aesthetic &amp; Cultural Development</td>
                    <td style="text-align:center;"><?php print_tick($summary, 'aesthetic', 'Beginner'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'aesthetic', 'Proficient'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'aesthetic', 'Advanced'); ?></td>
                </tr>
                <tr>
                    <td>Positive Learning Habits</td>
                    <td style="text-align:center;"><?php print_tick($summary, 'habits', 'Beginner'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'habits', 'Proficient'); ?></td>
                    <td style="text-align:center;"><?php print_tick($summary, 'habits', 'Advanced'); ?></td>
                </tr>
            </table> -->
            
            <div class="signs">
      <div class="sig" style="text-align:center;">
        Class Teacher Signature
        <div class="line"><img  style="width:50%; height:100%; object-fit:cover;"  src="<?= base_url($teacher_sign) ?>" /></div>
        <div style="text-align:center; padding-top:0.8rem;"><?= isset($teacher_name) ? str_replace(',', '<br>', hpc_esc($teacher_name)) : ''; ?></div>
      </div>
      <div class="sig" style="text-align:center;">Parent/Guardian Signature
      <div class="line"><img  style="width:50%; height:100%; object-fit:cover;"  src="<?= base_url('assets/sign/17.png') ?>" /></div>
      </div>
      <div class="sig" style="text-align:center;">
        Principal Signature
        <div class="line"><img  style="width:50%; height:100%; object-fit:cover;"  src="<?= base_url('assets/sign/6.png') ?>" /></div>
        <div style="text-align:center; padding-top:0.8rem;">Dr. Gaurav N. Tiwari</div>
      </div>
    </div>
            <div class="bar">KEY PERFORMANCE LEVEL DESCRIPTORS</div>
            <div class="card">
                <div class="row_1">The competencies are interpreted at various levels on the basis of the following description</div>
            </div>
            <div class="domain">
                <div class="b">
                    <table>
                        <tr>
                            <th>Level</th>
                            <th>Interpretation</th>
                        </tr>
                        <tr>
                            <td>Beginner</td>
                            <td style="text-align:left;">Tries to achieve the competency with a lot of support from teachers.</td>
                        </tr>
                        <tr>
                            <td>Progressing</td>
                            <td style="text-align:left;">Achieves the competency with occasional/some support from teachers.</td>
                        </tr>
                        <tr>
                            <td>Proficient</td>
                            <td style="text-align:left;">Achieves the competency on his/her own.</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="foot">Page 17 / 17 – Final Summary</div>
        </div>
    </section>

    <button class="print-btn" onclick="window.print()">Print / Save PDF</button>
</body>

</html>
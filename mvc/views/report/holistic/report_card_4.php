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
            margin-top: 2.4mm;
            font-size: 14.8px;
        }

        th,
        td {
            border: 1px solid #ead4b0;
            padding: 1.8mm 1.6mm;
            text-align: center;
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
            padding: 2.4mm 2.7mm;
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
                    <?php if (in_array($classes->classesID, [5, 13, 24, 4, 11, 23])) { ?>
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
                    <!-- <tr><th>Measurement</th><th style="width:6rem;">Term 1</th><th style="width:6rem;">Term 2</th></tr>
        <tr><td>Height (Ft/Cm)</td><td><?= hpc_esc($healthT1Ft); ?></td><td><?= hpc_esc($healthT2Ft); ?></td></tr>
        <tr><td>Weight (Kg)</td><td><?= hpc_esc($healthT1Weight); ?></td><td><?= hpc_esc($healthT2Weight); ?></td></tr> -->
                    <tr>
                        <th>Measurement</th>
                        <th style="width:6rem;">Term 2</th>
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
            <div class="foot">Page 1 / 9 – Cover</div>
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
            <div class="foot">Page 2 / 9 – General Information</div>
        </div>
    </section>

    <!-- ══════════════════════════════════════════ PAGE 3 – PHYSICAL DEVELOPMENT -->
    <section class="page">
        <div class="inner">
            <header class="head">
                <div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png" alt="Logo" /></div>
                <div class="school">
                    <h1>Part B – Holistic Development</h1>
                    <p>Physical Development</p>
                </div>
                <div class="tag">Page 3</div>
            </header>

            <div class="bar">Physical Development</div>

            <div class="domain">
                <div class="h">CG1 – Develops habits that keep him/her healthy and safe</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Shows a liking for and understanding of nutritious food and does not waste food</td>
                            <td><?php print_comp($comps, '1', 't1'); ?></td>
                            <td><?php print_comp($comps, '1', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Practices basic self-care and hygiene</td>
                            <td><?php print_comp($comps, '2', 't1'); ?></td>
                            <td><?php print_comp($comps, '2', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Understands unsafe situations and asks for help</td>
                            <td><?php print_comp($comps, '3', 't1'); ?></td>
                            <td><?php print_comp($comps, '3', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>


            <div class="domain">
                <div class="h">CG2 – Develops sharpness in sensorial perceptions</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Differentiates between shapes, colours and their shades</td>
                            <td><?php print_comp($comps, '4', 't1'); ?></td>
                            <td><?php print_comp($comps, '4', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Develops discrimination in the sense of touch</td>
                            <td><?php print_comp($comps, '5', 't1'); ?></td>
                            <td><?php print_comp($comps, '5', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Begins integrating sensorial perceptions to get a holistic awareness of experiences</td>
                            <td><?php print_comp($comps, '6', 't1'); ?></td>
                            <td><?php print_comp($comps, '6', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>


            <div class="domain">
                <div class="h">CG3 – Develops a fit and flexible body</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Shows balance, coordination and flexibility in various physical activities</td>
                            <td><?php print_comp($comps, '7', 't1'); ?></td>
                            <td><?php print_comp($comps, '7', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Shows precision and control in working with their hands and fingers</td>
                            <td><?php print_comp($comps, '8', 't1'); ?></td>
                            <td><?php print_comp($comps, '8', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Shows strength and endurance in carrying, walking and running</td>
                            <td><?php print_comp($comps, '9', 't1'); ?></td>
                            <td><?php print_comp($comps, '9', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>

            <div class="foot">Page 3 / 9 – Physical Development</div>
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
                    <h1>Part B - Holistic Development</h1>
                    <p>Socio-Emotional & Ethical Development</p>
                </div>
                <div class="tag">Page 4</div>
            </header>



            <div class="bar">Socio-Emotional & Ethical Development</div>

            <div class="domain">
                <div class="h">CG4 – Develops emotional intelligence</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Recognizing self as an individual belonging to family and community</td>
                            <td><?php print_comp($comps, '10', 't1'); ?></td>
                            <td><?php print_comp($comps, '10', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Recognises different emotions and makes deliberate effort to regulate them appropriately</td>
                            <td><?php print_comp($comps, '11', 't1'); ?></td>
                            <td><?php print_comp($comps, '11', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Interacts comfortably with other children and adults</td>
                            <td><?php print_comp($comps, '12', 't1'); ?></td>
                            <td><?php print_comp($comps, '12', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Shows kindness and helpfulness to others including animals and plants</td>
                            <td><?php print_comp($comps, '13', 't1'); ?></td>
                            <td><?php print_comp($comps, '13', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>


<!-- 
            <div class="bar">Attitude Towards Work & Environment</div> -->

            <div class="domain">
                <div class="h">CG5 – Develops positive attitude towards productive work and service or Seva</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Demonstrates willingness and participation in age-appropriate physical work towards helping others</td>
                            <td><?php print_comp($comps, '14', 't1'); ?></td>
                            <td><?php print_comp($comps, '14', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>


            <div class="domain">
                <div class="h">CG6 – Develops a positive regard for the natural environment</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Shows care for and joy in engaging with all life forms</td>
                            <td><?php print_comp($comps, '15', 't1'); ?></td>
                            <td><?php print_comp($comps, '15', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>

            <div class="foot">Page 4 / 9 – Cognitive Development</div>
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
                    <h1>Part B – Holistic Development</h1>
                    <p>Cognitive Development</p>
                </div>
                <div class="tag">Page 5</div>
            </header>
            <div class="bar">Cognitive Development</div>

            <div class="domain">
                <div class="h">CG7 – Makes sense of world around through observation and logical thinking</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Observes and understands different objects and relationship between them</td>
                            <td><?php print_comp($comps, '16', 't1'); ?></td>
                            <td><?php print_comp($comps, '16', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Observes and understands cause and effect relationships in nature</td>
                            <td><?php print_comp($comps, '17', 't1'); ?></td>
                            <td><?php print_comp($comps, '17', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>



            <div class="domain">
                <div class="h">CG8 – Develops mathematical understanding and abilities</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Sorts objects into groups and sub-groups based on more than one property</td>
                            <td><?php print_comp($comps, '18', 't1'); ?></td>
                            <td><?php print_comp($comps, '18', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Identifies and extends patterns in surroundings, shapes and numbers</td>
                            <td><?php print_comp($comps, '19', 't1'); ?></td>
                            <td><?php print_comp($comps, '19', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Counts up to 99 forwards and backwards and in groups of 10s and 20s</td>
                            <td><?php print_comp($comps, '20', 't1'); ?></td>
                            <td><?php print_comp($comps, '20', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Performs addition and subtraction of 2-digit numbers</td>
                            <td><?php print_comp($comps, '21', 't1'); ?></td>
                            <td><?php print_comp($comps, '21', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Recognises and classifies basic geometric shapes</td>
                            <td><?php print_comp($comps, '22', 't1'); ?></td>
                            <td><?php print_comp($comps, '22', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Solves simple mathematical problems</td>
                            <td><?php print_comp($comps, '23', 't1'); ?></td>
                            <td><?php print_comp($comps, '23', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>



            <div class="bar">Language & Literacy Development</div>

            <div class="domain">
                <div class="h">CG9 – Develops effective communication skills for day-to-day interaction</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Listens to and appreciates simple songs, rhymes and poems</td>
                            <td><?php print_comp($comps, '24', 't1'); ?></td>
                            <td><?php print_comp($comps, '24', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Converses fluently and holds meaningful conversation</td>
                            <td><?php print_comp($comps, '25', 't1'); ?></td>
                            <td><?php print_comp($comps, '25', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Understands oral instructions and gives clear instructions</td>
                            <td><?php print_comp($comps, '26', 't1'); ?></td>
                            <td><?php print_comp($comps, '26', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Comprehends narrated stories and identifies characters and storyline</td>
                            <td><?php print_comp($comps, '27', 't1'); ?></td>
                            <td><?php print_comp($comps, '27', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>


            <div class="foot">Page 5 / 9 – Cognitive Development</div>
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
                    <h1>Part B – Holistic Development</h1>
                    <p>Aesthetic and Cultural Development</p>
                </div>
                <div class="tag">Page 6</div>
            </header>
            <div class="domain">
                <div class="h">CG10 – Develops fluency in reading and writing in Language 1</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Recognises all letters of alphabet of the script (L1) and uses this knowledge to read and write words</td>
                            <td><?php print_comp($comps, '28', 't1'); ?></td>
                            <td><?php print_comp($comps, '28', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Reads stories and passages (in L1) with accuracy and fluency with appropriate pauses and voice modulation</td>
                            <td><?php print_comp($comps, '29', 't1'); ?></td>
                            <td><?php print_comp($comps, '29', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Reads short stories and comprehends its meaning by identifying characters and storyline</td>
                            <td><?php print_comp($comps, '30', 't1'); ?></td>
                            <td><?php print_comp($comps, '30', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Reads short poems and begins to appreciate the poem for its choice of words and imagination</td>
                            <td><?php print_comp($comps, '31', 't1'); ?></td>
                            <td><?php print_comp($comps, '31', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Reads and comprehends meaning of short news items, instructions, recipes and publicity material</td>
                            <td><?php print_comp($comps, '32', 't1'); ?></td>
                            <td><?php print_comp($comps, '32', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="domain">
                <div class="h">CG11 – Begins to read and write in Language 2</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Recognises most frequently occurring letters of the alphabet of the script and uses this knowledge to read and write simple words and sentences</td>
                            <td><?php print_comp($comps, '33', 't1'); ?></td>
                            <td><?php print_comp($comps, '33', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="bar">Aesthetic and Cultural Development</div>

            <div class="domain">
                <div class="h">CG12 – Develops ability and sensibilities in visual and performing arts</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Explores and plays with a variety of materials and tools to create two-dimensional and three-dimensional artworks in varying sizes</td>
                            <td><?php print_comp($comps, '34', 't1'); ?></td>
                            <td><?php print_comp($comps, '34', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Explores and plays with own voice, body, spaces and objects to create music, role-play, dance and movement</td>
                            <td><?php print_comp($comps, '35', 't1'); ?></td>
                            <td><?php print_comp($comps, '35', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Innovates and works imaginatively to express ideas and emotions through the arts</td>
                            <td><?php print_comp($comps, '36', 't1'); ?></td>
                            <td><?php print_comp($comps, '36', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>

            <div class="foot">Page 6 / 9 – Aesthetic and Cultural Development</div>
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
                    <h1>Part B - Holistic Development</h1>
                    <p>Positive Learning Habits</p>
                </div>
                <div class="tag">Page 7</div>
            </header>
            <div class="bar">Positive Learning Habits</div>

            <div class="domain">
                <div class="h">CG13 – Develops habits of learning that allow active engagement in school learning</div>
                <div class="b">
                    <table>
                        <tr>
                            <th>Competency</th>
                            <th style="width:6rem;">Term 1</th>
                            <th style="width:6rem;">Term 2</th>
                        </tr>

                        <tr>
                            <td>Attention and intentional action – acquires skills to plan, focus attention and direct activities to achieve goals</td>
                            <td><?php print_comp($comps, '37', 't1'); ?></td>
                            <td><?php print_comp($comps, '37', 't2'); ?></td>
                        </tr>

                        <tr>
                            <td>Observation, wonder, curiosity and exploration – observes details, explores using senses and asks questions</td>
                            <td><?php print_comp($comps, '38', 't1'); ?></td>
                            <td><?php print_comp($comps, '38', 't2'); ?></td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="bar">KEY PERFORMANCE LEVEL DESCRIPTORS</div>
            <div class="card">
                <div class="row_1">The competencies are interpreted at various levels on the basis of the following description</div>
            </div>
<div class="domain">
      <div class="b">
        <table>
          <tr><th>Level</th><th>Interpretation</th></tr>
          <tr><td>Beginner</td>   <td style="text-align:left;">Tries to achieve the competency with a lot of support from teachers.</td></tr>
          <tr><td>Progressing</td><td style="text-align:left;">Achieves the competency with occasional/some support from teachers.</td></tr>
          <tr><td>Proficient</td> <td style="text-align:left;">Achieves the competency on his/her own.</td></tr>
        </table>
      </div>
    </div>
            <div class="foot">Page 7 / 9 – Positive Learning Habits</div>
        </div>
    </section>

    <section class="page">
  <div class="inner">
    <header class="head">
      <div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_122,h_122,al_c,q_85,enc_avif,quality_auto/PPGMIS%20logo_edited.png" alt="PPGMIS Logo"></div>
      <div class="school"><h1>Observations &amp; Assessments</h1><p>Self, Peer, Parent and Teacher Inputs</p></div>
      <div class="tag">Page 8</div>
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
    <div class="foot">Page 8 / 9 – Assessments &amp; Observations</div>
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
                <div class="tag">Page 9</div>
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

            <div class="bar">Domain-wise Annual Summary</div>
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
            </table>

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

            <div class="foot">Page 9 / 9 – Final Summary</div>
        </div>
    </section>

    <button class="print-btn" onclick="window.print()">Print / Save PDF</button>
</body>

</html>
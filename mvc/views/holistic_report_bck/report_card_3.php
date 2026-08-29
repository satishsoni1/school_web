<?php
$studentName = isset($student->name) ? $student->name : '';
$className = isset($classes->classes_without_div) ? $classes->classes_without_div : '';
$schoolYear = isset($schoolyear->schoolyear) ? $schoolyear->schoolyear : '';
$admissionNo = isset($student->registerNO) ? $student->registerNO : (isset($student->srregisterNO) ? $student->srregisterNO : '');
$rollNo = isset($student->roll) ? $student->roll : '';
$dob = isset($student->dob) ? $student->dob : (isset($student->dateofbirth) ? $student->dateofbirth : '');
$parentName = isset($student->father_name) ? $student->father_name : (isset($student->parent_name) ? $student->parent_name : '');
$contactNo = isset($student->phone) ? $student->phone : (isset($student->mobileno) ? $student->mobileno : '');
$address = isset($student->address) ? $student->address : '';
$teacherRemarks = isset($holistic->teacher_remarks) ? $holistic->teacher_remarks : '';
$motherTongue = isset($holistic->mother_tongue) ? $holistic->mother_tongue : '';
$ambition = isset($holistic->ambition) ? $holistic->ambition : '';
$favColour = isset($holistic->fav_colour) ? $holistic->fav_colour : '';
$favFood = isset($holistic->fav_food) ? $holistic->fav_food : '';
$favGame = isset($holistic->fav_game) ? $holistic->fav_game : '';
$healthT1Span = isset($health['t1']['span']) ? $health['t1']['span'] : '';
$healthT1Ft = isset($health['t1']['ft']) ? $health['t1']['ft'] : '';
$healthT1Weight = isset($health['t1']['weight']) ? $health['t1']['weight'] : '';
$healthT2Span = isset($health['t2']['span']) ? $health['t2']['span'] : '';
$healthT2Ft = isset($health['t2']['ft']) ? $health['t2']['ft'] : '';
$healthT2Weight = isset($health['t2']['weight']) ? $health['t2']['weight'] : '';
$ageYears = '';
if (!empty($dob)) {
    $ts = strtotime($dob);
    if ($ts) {
        $ageYears = (string) date_diff(date_create(date('Y-m-d', $ts)), date_create(date('Y-m-d')))->y;
    }
}

$parentRemarkLines = array();
if (isset($parent_feedback['t1']) && is_array($parent_feedback['t1'])) $parentRemarkLines = array_merge($parentRemarkLines, $parent_feedback['t1']);
if (isset($parent_feedback['t2']) && is_array($parent_feedback['t2'])) $parentRemarkLines = array_merge($parentRemarkLines, $parent_feedback['t2']);
$parentRemarkLines = array_filter(array_map('trim', $parentRemarkLines));
$parentRemarkText = implode(', ', $parentRemarkLines);
$selfT1 = isset($self_assessment['t1']) && is_array($self_assessment['t1']) ? array_values($self_assessment['t1']) : array();
$selfT2 = isset($self_assessment['t2']) && is_array($self_assessment['t2']) ? array_values($self_assessment['t2']) : array();
$peerT1 = isset($peer_assessment['t1']) && is_array($peer_assessment['t1']) ? array_values($peer_assessment['t1']) : array();
$peerT2 = isset($peer_assessment['t2']) && is_array($peer_assessment['t2']) ? array_values($peer_assessment['t2']) : array();
for ($i = 0; $i < 3; $i++) {
    if (!isset($selfT1[$i])) $selfT1[$i] = '';
    if (!isset($selfT2[$i])) $selfT2[$i] = '';
    if (!isset($peerT1[$i])) $peerT1[$i] = '';
    if (!isset($peerT2[$i])) $peerT2[$i] = '';
}
$parentT1 = isset($parent_feedback['t1']) && is_array($parent_feedback['t1']) ? array_values($parent_feedback['t1']) : array('', '', '', '');
$parentT2 = isset($parent_feedback['t2']) && is_array($parent_feedback['t2']) ? array_values($parent_feedback['t2']) : array('', '', '', '');
for ($i = 0; $i < 4; $i++) {
    if (!isset($parentT1[$i])) $parentT1[$i] = '';
    if (!isset($parentT2[$i])) $parentT2[$i] = '';
}
$teacherLines = array_values(array_filter(array_map('trim', preg_split('/\\r\\n|\\r|\\n/', (string)$teacherRemarks))));
for ($i = count($teacherLines); $i < 5; $i++) $teacherLines[] = '';

if (!function_exists('hpc_esc')) {
    function hpc_esc($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
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

    * { box-sizing: border-box; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    html, body { margin: 0; padding: 0; background: var(--bg); color: var(--ink); font-family: "Baloo 2", sans-serif; font-size: 19px; line-height: 1.42; }

    .page {
      width: 210mm;
      height: 297mm;
      margin: 5mm auto;
      background: var(--paper);
      border: 1px solid #eddfca;
      border-radius: 4mm;
      overflow: hidden;
      position: relative;
      box-shadow: 0 10px 28px rgba(0,0,0,.18);
      page-break-after: always;
      break-after: page;
      page-break-inside: avoid;
      break-inside: avoid;
    }

    .page:last-of-type { page-break-after: auto; break-after: auto; }

    .page::before, .page::after {
      content: "";
      position: absolute;
      border-radius: 999px;
      z-index: 0;
      opacity: .13;
      pointer-events: none;
    }
    .page::before { width: 120mm; height: 120mm; right: -30mm; top: -40mm; background: radial-gradient(circle, var(--green), transparent 70%); }
    .page::after  { width: 100mm; height: 100mm; left: -20mm; bottom: -35mm; background: radial-gradient(circle, var(--orange), transparent 72%); }

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
    .inner > * { position: relative; z-index: 2; }

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

    .school { text-align: center; }
    .school h1 {
      margin: .4mm 0;
      font-family: "Fredoka", sans-serif;
      font-size: 24px;
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
      text-transform: uppercase;
      letter-spacing: .3px;
      font-weight: 800;
    }
    .school .meta-line {
      font-size: 10px;
      color: #ffd59a;
      font-weight: 700;
      letter-spacing: .2px;
    }
    .school .stage-line {
      color: #ffffff;
      font-size: 10.8px;
      font-weight: 700;
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
    .title h2 { margin: 0; font-family: "Fredoka", sans-serif; color: var(--red-dark); font-size: 46px; line-height: .95; letter-spacing: .2px; }
    .title p { margin: 2mm 0 0; font-size: 14px; font-weight: 700; color: #8a4300; }

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

    .grid2 { margin-top: 3mm; display: grid; grid-template-columns: 1.4fr 1fr; gap: 3mm; }
    .cover-grid { margin-top: 4mm; display: grid; grid-template-columns: 1.28fr .92fr; gap: 3mm; align-items: start; }
    .card {
      background: #fff;
      border: 1.5px solid var(--line);
      border-radius: 4mm;
      padding: 3mm;
    }
    .card h3 { margin: 0 0 1.2mm; font-family: "Fredoka", sans-serif; color: var(--blue); font-size: 20px; }

    .row {
      display: flex;
      align-items: flex-end;
      gap: 2mm;
      font-size: 13.5px;
      font-weight: 700;
      border-bottom: 1px dashed #d8c4a8;
      padding: 1.5mm 0;
    }
    .line { flex: 1; border-bottom: 1.4px solid #9f8a70; min-height: 3.8mm; }
    .value { flex: 1; border-bottom: 1.4px solid #9f8a70; min-height: 3.8mm; font-weight: 800; color: #2d1d22; padding-left: 2mm; }

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
    }

    .chips { display: flex; flex-wrap: wrap; gap: 1.2mm; margin-top: 1.5mm; }
    .chip { border: 1.5px solid #cdeed9; background: #f0fff6; color: #0d7d40; border-radius: 999px; padding: 1.1mm 3mm; font-size: 12px; font-weight: 700; }

    table { width: 100%; border-collapse: collapse; margin-top: 2.4mm; font-size: 14.8px; }
    th, td { border: 1px solid #ead4b0; padding: 1.8mm 1.6mm; text-align: center; }
    th { background: #ffe6ca; color: #7b3900; font-weight: 800; font-size: 13px; }
    th:first-child, td:first-child { text-align: left; font-weight: 700; }

    .domain {
      margin-top: 2.2mm;
      border: 1.5px solid #ecd7b9;
      border-radius: 4mm;
      overflow: hidden;
      background: #fff;
    }
    .domain .h { background: linear-gradient(90deg, var(--orange), #ffb456); color: #5e3200; font-size: 13.5px; font-weight: 800; padding: 1.8mm 2.8mm; }
    .domain .b { padding: 2.4mm 2.7mm; font-size: 12px; }

    .levels { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2mm; margin-top: 2mm; }
    .lv { border-radius: 3mm; color: #fff; text-align: center; padding: 2.6mm; font-size: 13px; font-weight: 800; }
    .lv.st { background: var(--blue); }
    .lv.md { background: var(--orange); }
    .lv.sk { background: var(--green); }

    .signs { display: grid; grid-template-columns: repeat(3,1fr); gap: 2.2mm; margin-top: 3mm; }
    .sig { border: 1.5px solid #e8d5b7; border-radius: 3mm; min-height: 21mm; padding: 2.2mm; font-size: 12px; font-weight: 700; background: #fffdf9; }
    .sig .line { margin-top: 8mm; }

    .mini-note {
      margin-top: 2mm;
      border-left: 3px solid var(--green);
      background: var(--green-soft);
      border-radius: 2mm;
      padding: 1.7mm 2.2mm;
      font-size: 12px;
      color: #175c36;
      font-weight: 600;
    }

    .foot { margin-top: auto; text-align: center; font-size: 11px; color: #7b6c53; font-weight: 700; }

    .feel-wrap { margin-top: 2mm; border: 1.5px solid var(--line); border-radius: 4mm; overflow: hidden; background: #fff; }
    .feel-head { background: linear-gradient(90deg, var(--green), #2bc067); color: #fff; font-family: "Fredoka", sans-serif; font-size: 15px; font-weight: 700; padding: 2mm 3.4mm; }
    .feel-sub { font-size: 11.5px; color: #1f6a42; background: #edfdf3; padding: 1.7mm 3.4mm; font-weight: 600; border-bottom: 1px solid #d4ecd9; }
    .feel-table { margin-top: 0; font-size: 11.6px; }
    .feel-table th { background: #e6f8ed; color: #1d663e; font-size: 11.4px; padding: 1.6mm; }
    .feel-table td { padding: 1.4mm; }
    .feel-table td:first-child { font-weight: 700; }
    .feel-mark { font-size: 12px; letter-spacing: .2px; color: #6f5f49; }

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
      filter: drop-shadow(0 1px 1px rgba(0,0,0,.12));
    }
    .art-float {
      position: absolute;
      z-index: 1;
      pointer-events: none;
      opacity: .85;
    }
    .art-float.left { left: 3mm; bottom: 6mm; width: 12mm; height: 12mm; }
    .art-float.right { right: 3mm; bottom: 8mm; width: 14mm; height: 14mm; }

    .assess-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3mm; margin-top: 2mm; }
    .assess-card { background: #fff; border: 1.5px solid var(--line); border-radius: 4mm; padding: 2.5mm; }
    .assess-card h4 { margin: 0 0 1mm; font-size: 14px; color: var(--blue); font-family: "Fredoka", sans-serif; }
    .dot-row { display: flex; align-items: center; justify-content: space-between; gap: 2mm; font-size: 12px; font-weight: 700; padding: 1.3mm 0; border-bottom: 1px dashed #dfccb0; }
    .dots { display: flex; gap: 1.2mm; }
    .dot { width: 4mm; height: 4mm; border: 1.5px solid #9b896f; border-radius: 50%; background: #fff; }
    .comment-box { margin-top: 1.5mm; border: 1.2px dashed #d9c3a4; border-radius: 3mm; min-height: 24mm; padding: 1.7mm; font-size: 11.6px; color: #8a7960; }
    .remark-grid { margin-top: 3mm; display: grid; grid-template-columns: 1fr 1fr; gap: 3mm; }
    .remark-card {
      background: #fff;
      border: 1.5px solid var(--line);
      border-radius: 4mm;
      padding: 3mm;
      min-height: 52mm;
    }
    .remark-card h4 {
      margin: 0 0 1.2mm;
      font-family: "Fredoka", sans-serif;
      color: var(--blue);
      font-size: 15px;
    }
    .remark-line {
      height: 8mm;
      border-bottom: 1.5px dashed #cfb28a;
      margin-top: 1.2mm;
    }
    .remark-chip-row {
      display: flex;
      gap: 2mm;
      flex-wrap: wrap;
      margin-top: 2mm;
    }
    .remark-chip {
      border: 1.5px solid #d7c8ae;
      border-radius: 999px;
      padding: .8mm 2.4mm;
      font-size: 11px;
      font-weight: 700;
      color: #6d4f2f;
      background: #fffaf1;
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
      box-shadow: 0 8px 24px rgba(0,0,0,.25);
      cursor: pointer;
      z-index: 999;
    }

    @page { size: A4 portrait; margin: 0; }
    @media print {
      html, body {
        width: 100% !important;
        min-height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        background: var(--bg) !important;
        color: var(--ink) !important;
      }

      .page {
        width: 210mm !important;
        height: 297mm !important;
        margin: 0 !important;
        border: none !important;
        border-radius: 0 !important;
        box-shadow: none !important;
        background: var(--paper) !important;
        page-break-after: always !important;
        break-after: page !important;
        overflow: hidden !important;
      }

      .page:last-of-type {
        page-break-after: auto !important;
        break-after: auto !important;
      }

      .inner { padding: 8mm !important; gap: 1.2mm !important; }
      .school h1 { font-size: 24px !important; }
      .school p { font-size: 12px !important; }
      .bar { font-size: 13px !important; }
      table { font-size: 12.2px !important; }
      th { font-size: 12.4px !important; }
      .foot { font-size: 10px !important; }

      .logo img {
        max-height: 100% !important;
      }

      .print-btn { display: none !important; }
    }
  </style>
</head>
<body>

  <!-- PAGE 1: COVER -->
  <section class="page">
    <div class="inner">
      <svg class="art-float left" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="42" fill="#ffd24d"/><circle cx="38" cy="42" r="5" fill="#7b4c00"/><circle cx="62" cy="42" r="5" fill="#7b4c00"/><path d="M30 60 Q50 78 70 60" fill="none" stroke="#7b4c00" stroke-width="6" stroke-linecap="round"/></svg>
      <svg class="art-float right" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path d="M10 80 C30 40, 70 40, 90 80" stroke="#ff6b6b" stroke-width="8" fill="none"/><path d="M15 80 C33 48, 67 48, 85 80" stroke="#f28c1b" stroke-width="8" fill="none"/><path d="M20 80 C36 56, 64 56, 80 80" stroke="#0ea64b" stroke-width="8" fill="none"/><path d="M25 80 C39 63, 61 63, 75 80" stroke="#2f2d92" stroke-width="8" fill="none"/></svg>
      <header class="head">
        <div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_122,h_122,al_c,q_85,enc_avif,quality_auto/PPGMIS%20logo_edited.png" alt="PPGMIS Logo"></div>
        <div class="school">
          <p class="trust-line">Khalapur Taluka Shikshan Prasarak Mandal's</p>
          <h1>P.P. Gagangiri Maharaj International School</h1>
          <p class="meta-line">AFFILIATION NO.:1131395 | UDISE NO.:27240312802 | SCHOOL CODE:31384</p>
          <p class="stage-line">Foundational Stage | Holistic Progress Card</p>
        </div>
        <div class="tag">Class: <?= hpc_esc($className); ?></div>
      </header>
      <div class="art-strip">
        <svg class="art-icon" viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><circle cx="22" cy="22" r="16" fill="#ffd24d"/><g stroke="#ffd24d" stroke-width="4"><line x1="22" y1="1" x2="22" y2="12"/><line x1="22" y1="32" x2="22" y2="43"/><line x1="1" y1="22" x2="12" y2="22"/><line x1="32" y1="22" x2="43" y2="22"/></g><rect x="56" y="20" width="42" height="14" rx="6" fill="#bde8ff"/><circle cx="68" cy="20" r="10" fill="#bde8ff"/></svg>
        <svg class="art-icon" viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><rect x="16" y="34" width="66" height="18" rx="5" fill="#ffd24d"/><polygon points="82,34 102,43 82,52" fill="#ff6f3c"/><rect x="12" y="36" width="8" height="14" rx="2" fill="#ff6f3c"/></svg>
        <svg class="art-icon" viewBox="0 0 120 80" xmlns="http://www.w3.org/2000/svg"><polygon points="25,10 31,24 46,24 34,33 39,47 25,38 11,47 16,33 4,24 19,24" fill="#ffd24d"/><polygon points="76,18 80,28 90,28 82,35 85,45 76,39 67,45 70,35 62,28 72,28" fill="#f28c1b"/></svg>
      </div>
      <div class="title">
        <h2>HOLISTIC<br>PROGRESS CARD</h2>
        <p>Academic Year: <?= hpc_esc($schoolYear); ?></p>
      </div>
      <div class="cover-grid">
        <div class="card">
          <h3>Student Information</h3>
          <div class="row">Student Name <span class="value"><?= hpc_esc($studentName); ?></span></div>
          <div class="row">Admission No. <span class="value"><?= hpc_esc($admissionNo); ?></span></div>
          <div class="row">Roll No. <span class="value"><?= hpc_esc($rollNo); ?></span></div>
          <div class="row">Date of Birth <span class="value"><?= hpc_esc(date('d-m-Y',strtotime($dob))); ?></span></div>
          <div class="row">Parent/Guardian Name <span class="value"><?= hpc_esc($parentName); ?></span></div>
          <div class="row">Contact No. <span class="value"><?= hpc_esc($contactNo); ?></span></div>
          <div class="row">Address <span class="value"><?= hpc_esc($address); ?></span></div>
          <div class="bar">My Interests</div>
          <div class="chips">
            <span class="chip">Reading</span><span class="chip">Drawing</span><span class="chip">Music</span><span class="chip">Dance</span>
            <span class="chip">Rhymes</span><span class="chip">Sports</span><span class="chip">Clay Work</span><span class="chip">Nature</span>
          </div>
        </div>
       
        <div class="card">
          <h3>Photograph</h3>
          <div class="passport-photo">Paste Passport<br>Size Photo</div>
          <div class="mini-note">Use this box for passport size child photograph.</div>
        </div>
      </div>
      <div class="card" style="margin-top:3mm;">
        <h3>Health Record</h3>
        <table>
          <tr><th>Measurement</th><th>Term 1</th><th>Term 2</th></tr>
          <tr><td>Height (Hand Span)</td><td><?= hpc_esc($healthT1Span); ?></td><td><?= hpc_esc($healthT2Span); ?></td></tr>
          <tr><td>Height (Ft/Cm)</td><td><?= hpc_esc($healthT1Ft); ?></td><td><?= hpc_esc($healthT2Ft); ?></td></tr>
          <tr><td>Weight (Kg)</td><td><?= hpc_esc($healthT1Weight); ?></td><td><?= hpc_esc($healthT2Weight); ?></td></tr>
        </table>
      </div>
      <div class="foot">Page 1 / 9 - Cover</div>
    </div>
  </section>

  <!-- PAGE 2: GENERAL + ATTENDANCE -->
  <section class="page">
    <div class="inner">
      <svg class="art-float left" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><path d="M15 70 C25 45, 45 45, 55 70" stroke="#ff6b6b" stroke-width="8" fill="none"/><path d="M35 70 C45 45, 65 45, 75 70" stroke="#f28c1b" stroke-width="8" fill="none"/><circle cx="84" cy="24" r="9" fill="#ffd24d"/></svg>
      <header class="head"><div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_122,h_122,al_c,q_85,enc_avif,quality_auto/PPGMIS%20logo_edited.png" alt="PPGMIS Logo"></div><div class="school"><h1>Part A - General Information</h1><p>To be filled by teacher in consultation with parents</p></div><div class="tag">Page 2</div></header>
      <div class="bar">Attendance Record</div>
      <table>
        <tr><th>Month</th><th>Apr</th><th>May</th><th>Jun</th><th>Jul</th><th>Aug</th><th>Sep</th><th>Oct</th><th>Nov</th><th>Dec</th><th>Jan</th><th>Feb</th><th>Mar</th></tr>
        <tr><td>Working Days</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr><td>Days Present</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        <tr><td>Attendance %</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
      </table>

      <div class="grid2">
        <div class="card">
          <h3>All About Me</h3>
          <div class="row">My birthday is on <span class="value"><?= hpc_esc($dob); ?></span></div>
          <div class="row">I am <span class="value"><?= hpc_esc($ageYears); ?></span> years old</div>
          <div class="row">I live in <span class="value"><?= hpc_esc($address); ?></span></div>
          <div class="row">My favourite colour is <span class="value"><?= hpc_esc($favColour); ?></span></div>
          <div class="row">My favourite food is <span class="value"><?= hpc_esc($favFood); ?></span></div>
          <div class="row">I want to be <span class="value"><?= hpc_esc($ambition); ?></span></div>
          <div class="row">My best friend is <span class="line"></span></div>
        </div>
        <div class="card">
          <h3>Portfolio Snapshot</h3>
          <div class="photo" style="min-height:55mm;">Paste/Draw Work Sample</div>
          <div class="mini-note">Teacher can paste art sheet, worksheet, or photo evidence of activity.</div>
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
          <tr><td>1. I can talk about how I feel (happy, upset, or angry).</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td></tr>
          <tr><td>2. I can calm myself down during difficult situations.</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td></tr>
          <tr><td>3. I can understand how my friends feel.</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td></tr>
          <tr><td>4. I respect everyone's opinions.</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td></tr>
          <tr><td>5. I can help my friends after a fight.</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td></tr>
          <tr><td>6. When someone is sad, I can make them feel better.</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td></tr>
          <tr><td>7. I think I do well at school.</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td><td class="feel-mark">◯</td></tr>
        </table>
      </div>
      <div class="foot">Page 2 / 9 - General Information</div>
    </div>
  </section>

<!-- PAGE 3: PHYSICAL DEVELOPMENT -->
<section class="page">
  <div class="inner">
  
  <header class="head">
  <div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png" /></div>
  <div class="school">
  <h1>Part B - Holistic Development</h1>
  <p>Physical Development</p>
  </div>
  <div class="tag">Page 3</div>
  </header>
  
  <div class="bar">Physical Development Competencies</div>
  <br/>
  <div class="domain">
    
  <div class="h">Curriculum Goal 1 – Children develop habits that keep them healthy & safe</div>
  <div class="b">
  <table>
  <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
  <tr><td>C-1.1 Shows liking for nutritious food and does not waste food</td><td></td><td></td></tr>
  <tr><td>C-1.2 Practices basic self-care and hygiene</td><td></td><td></td></tr>
  <tr><td>C-1.6 Understands unsafe situations and asks for help</td><td></td><td></td></tr>
  </table>
  </div>
  </div>
  <br/>
  <br/>
  <div class="domain">
  <div class="h">Curriculum Goal 2 – Children develop sharpness in sensorial perceptions</div>
  <div class="b">
  <table>
  <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
  <tr><td>C-2.1 Differentiates shapes, colours and shades</td><td></td><td></td></tr>
  <tr><td>C-2.4 Differentiates smells and tastes</td><td></td><td></td></tr>
  <tr><td>C-2.5 Develops discrimination in sense of touch</td><td></td><td></td></tr>
  </table>
  </div>
  </div>
  <br/>
  <br/>
  <div class="domain">
  <div class="h">Curriculum Goal 3 – Children develop a fit and flexible body</div>
  <div class="b">
  <table>
  <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
  <tr><td>C-3.2 Shows balance and coordination</td><td></td><td></td></tr>
  <tr><td>C-3.3 Shows precision using hands and fingers</td><td></td><td></td></tr>
  <tr><td>C-3.4 Shows strength and endurance in physical activity</td><td></td><td></td></tr>
  </table>
  </div>
  </div>
  <br/>
  <br/>
  <div class="foot">Page 3 / 9 - Physical Development</div>
  </div>
  </section>
  <!-- PAGE 4: SOCIO-EMOTIONAL -->
  <section class="page">
    <div class="inner">
      <header class="head">
        <div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png" /></div>
        <div class="school">
          <h1>Holistic Development</h1>
          <p>Socio Emotional Development</p>
        </div>
        <div class="tag">Page 4</div>
      </header>

      <div class="bar">Socio-Emotional Development</div>
      <div class="domain">
        <div class="h">Curriculum Goal 4 – Emotional Intelligence</div>
        <div class="b">
          <table>
            <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
            <tr><td>C-4.1 Recognises self as an individual</td><td></td><td></td></tr>
            <tr><td>C-4.2 Recognises and regulates emotions</td><td></td><td></td></tr>
            <tr><td>C-4.3 Interacts comfortably with children and adults</td><td></td><td></td></tr>
            <tr><td>C-4.6 Shows kindness and helpfulness</td><td></td><td></td></tr>
          </table>
        </div>
      </div>
      <div class="domain">
        <div class="h">Curriculum Goal 5 – Positive attitude towards work</div>
        <div class="b">
          <table>
            <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
            <tr><td>C-5.1 Participates in helping activities</td><td></td><td></td></tr>
          </table>
        </div>
      </div>
      <div class="domain">
        <div class="h">Curriculum Goal 6 – Positive regard for the natural environment</div>
        <div class="b">
          <table>
            <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
            <tr><td>C-6.1 Shows care for and joy in engaging with all life forms</td><td></td><td></td></tr>
          </table>
        </div>
      </div>
      <div class="bar">Cognitive Development</div>
      <div class="domain">
        <div class="h">Curriculum Goal 7 – Observation & logical thinking</div>
        <div class="b">
          <table>
            <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
            <tr><td>C-7.1 Observes categories of objects</td><td></td><td></td></tr>
            <tr><td>C-7.2 Understands cause and effect</td><td></td><td></td></tr>
          </table>
        </div>
      </div>
        <div class="foot">Page 4 / 9 - Socio Emotional Development</div>
    </div>
  </section>

  <!-- PAGE 5: COGNITIVE -->
  <section class="page">
    <div class="inner">
      <header class="head">
        <div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png" /></div>
        <div class="school">
          <h1>Holistic Development</h1>
          <p>Cognitive Development</p>
        </div>
        <div class="tag">Page 5</div>
      </header>

      <div class="bar">Cognitive Development</div>


      <div class="domain">
        <div class="h">Curriculum Goal 8 – Mathematical understanding and abilities</div>
        <div class="b">
          <table>
            <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
            <tr><td>C-8.1 Sorts objects into groups and sub-groups based on properties</td><td></td><td></td></tr>
            <tr><td>C-8.2 Identifies and extends simple patterns in surroundings</td><td></td><td></td></tr>
            <tr><td>C-8.5 Recognises and uses numerals to represent quantities up to 99</td><td></td><td></td></tr>
            <tr><td>C-8.8 Recognises and classifies basic geometric shapes</td><td></td><td></td></tr>
            <tr><td>C-8.13 Formulates and solves simple mathematical problems</td><td></td><td></td></tr>
          </table>
        </div>
      </div>
      <div class="bar">Language & Literacy Development</div>

      <div class="domain">
        <div class="h">Curriculum Goal 9 – Communication skills</div>
        <div class="b">
          <table>
            <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
            <tr><td>C-9.1 Listens to songs, rhymes and poems</td><td></td><td></td></tr>
            <tr><td>C-9.3 Converses fluently</td><td></td><td></td></tr>
            <tr><td>C-9.4 Understands oral instructions</td><td></td><td></td></tr>
            <tr><td>C-9.5 Understands narrated stories</td><td></td><td></td></tr>
          </table>
        </div>
      </div>
      <div class="domain">
        <div class="h">Curriculum Goal 10 – Reading & writing fluency</div>
        <div class="b">
          <table>
            <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
            <tr><td>C-10.1 Develops phonological awareness</td><td></td><td></td></tr>
            <tr><td>C-10.2 Understands structure of a book</td><td></td><td></td></tr>
            <tr><td>C-10.3 Recognises alphabet letters</td><td></td><td></td></tr>
            <tr><td>C-10.4 Reads passages with fluency</td><td></td><td></td></tr>
            <tr><td>C-10.5 Reads short stories and understands meaning</td><td></td><td></td></tr>
          </table>
        </div>
      </div>
      <div class="foot">Page 5 / 9 - Cognitive Development</div>
    </div>
  </section>

  <!-- PAGE 6: LANGUAGE -->
  <section class="page">
    <div class="inner">
      <header class="head">
        <div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png" /></div>
        <div class="school">
          <h1>Holistic Development</h1>
          <p>Language and Literacy Development</p>
        </div>
        <div class="tag">Page 6</div>
      </header>

      <div class="bar">Aesthetic & Cultural Development</div>

      <div class="domain">
        <div class="h">Curriculum Goal 12 – Abilities and sensibilities in visual and performing arts</div>
        <div class="b">
          <table>
            <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
            <tr><td>C-12.1 Explores art materials</td><td></td><td></td></tr>
            <tr><td>C-12.2 Uses voice and movement for expression</td><td></td><td></td></tr>
            <tr><td>C-12.3 Shows imagination through art</td><td></td><td></td></tr>
          </table>
        </div>
      </div>

      <div class="bar">Positive Learning Habits</div>

      <div class="domain">
        <div class="h">Curriculum Goal 13 – Habits of learning for school readiness</div>
        <div class="b">
          <table>
            <tr><th>Competency</th><th>Term 1</th><th>Term 2</th></tr>
            <tr><td>C-13.1 Focuses attention and plans activities</td><td></td><td></td></tr>
            <tr><td>C-13.3 Shows curiosity and exploration</td><td></td><td></td></tr>
          </table>
        </div>
      </div>

        <div class="foot">Page 6 / 9 - Language Development</div>
    </div>
  </section>

  
  <!-- PAGE 7: SELF/PEER + PARENT + TEACHER OBSERVATION -->
  <section class="page">
    <div class="inner">
      <header class="head"><div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_122,h_122,al_c,q_85,enc_avif,quality_auto/PPGMIS%20logo_edited.png" alt="PPGMIS Logo"></div><div class="school"><h1>Observations & Assessments</h1><p>Self, Peer, Parent and Teacher Inputs</p></div><div class="tag">Page 7</div></header>

      <div class="bar">Self Assessment & Peer Assessment</div>
      <div class="assess-grid">
        <div class="assess-card">
          <h4>Self Assessment</h4>
          <div class="dot-row"><span>I enjoyed today's activities</span><span class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></span></div>
          <div class="dot-row"><span>I could complete work independently</span><span class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></span></div>
          <div class="dot-row"><span>I followed classroom instructions</span><span class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></span></div>
          <div class="comment-box"><strong>Comments:</strong> <?= hpc_esc(trim($selfT1[0].' '.$selfT1[1].' '.$selfT1[2].' | '.$selfT2[0].' '.$selfT2[1].' '.$selfT2[2])); ?><br>_______________________________________________________<br>_______________________________________________________</div>
        </div>
        <div class="assess-card">
          <h4>Peer Assessment</h4>
          <div class="dot-row"><span>Child collaborates with friends</span><span class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></span></div>
          <div class="dot-row"><span>Child shares learning materials</span><span class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></span></div>
          <div class="dot-row"><span>Child supports team activities</span><span class="dots"><span class="dot"></span><span class="dot"></span><span class="dot"></span></span></div>
          <div class="comment-box"><strong>Comments:</strong> <?= hpc_esc(trim($peerT1[0].' '.$peerT1[1].' '.$peerT1[2].' | '.$peerT2[0].' '.$peerT2[1].' '.$peerT2[2])); ?><br>_______________________________________________________<br>_______________________________________________________</div>
        </div>
      </div>

      <div class="bar">Parent / Guardian Observation</div>
      <div class="card" style="margin-top:2mm;">
        <div class="row">My child enjoys <span class="value"><?= hpc_esc($parentT1[0]); ?></span></div>
        <div class="row">My child needs support in <span class="value"><?= hpc_esc($parentT1[1]); ?></span></div>
        <div class="row">Home resources used (books/games/etc.) <span class="value"><?= hpc_esc($parentT1[2]); ?></span></div>
        <div class="row">Parent comments <span class="value"><?= hpc_esc($parentT1[3]); ?></span></div>
      </div>

      <div class="bar">Teacher Observation Notes</div>
      <div class="card" style="margin-top:2mm;">
        <div class="row">Notes <span class="value"><?= hpc_esc($teacherLines[0]); ?></span></div>
        <div class="row">Notes <span class="value"><?= hpc_esc($teacherLines[1]); ?></span></div>
        <div class="row">Notes <span class="value"><?= hpc_esc($teacherLines[2]); ?></span></div>
        <div class="row">Notes <span class="value"><?= hpc_esc($teacherLines[3]); ?></span></div>
        <div class="row">Activity Evidence <span class="value"><?= hpc_esc($teacherLines[4]); ?></span></div>
      </div>

      <div class="foot">Page 7 / 9 - Assessments & Observations</div>
    </div>
  </section>

  <!-- PAGE 7: CLASS GALLERY -->
  <section class="page">
    <div class="inner">
      <svg class="art-float left" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><rect x="18" y="20" width="64" height="48" rx="6" fill="#e4e6ff" stroke="#2f2d92" stroke-width="5"/><circle cx="34" cy="36" r="6" fill="#ffd24d"/><rect x="46" y="32" width="26" height="8" rx="3" fill="#2f2d92"/><rect x="24" y="50" width="52" height="8" rx="3" fill="#f28c1b"/></svg>
      <header class="head"><div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_122,h_122,al_c,q_85,enc_avif,quality_auto/PPGMIS%20logo_edited.png" alt="PPGMIS Logo"></div><div class="school"><h1>Class Gallery</h1><p>Memories from classroom activities and events</p></div><div class="tag">Page 8</div></header>

      <div class="title" style="margin-top:3mm;">
        <h2 style="font-size:30px; color:var(--blue);"><?=  $className ?> CLASS GALLERY</h2>
        <p>Paste class photos, activity snapshots, and celebration moments</p>
      </div>

      <div class="bar">Class Group Photo</div>
      <div class="photo" style="min-height:82mm; margin-top:2mm; font-size:12px;">Paste Main Class Photo Here</div>

      <div class="bar">Activity Highlights</div>
      <div class="grid2" style="grid-template-columns:1fr 1fr; margin-top:2mm;">
        <div class="photo" style="min-height:46mm;">Art & Craft Photo</div>
        <div class="photo" style="min-height:46mm;">Sports / Play Photo</div>
      </div>

      <div class="mini-note">Optional: Add date and short caption below each image.</div>
      <div class="foot">Page 8 / 9 - Class Gallery</div>
    </div>
  </section>

  <!-- PAGE 8: FINAL SUMMARY -->
  <section class="page">
    <div class="inner">
      <svg class="art-float right" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="42" fill="#fff4df"/><path d="M28 60 L44 76 L74 34" fill="none" stroke="#0ea64b" stroke-width="8" stroke-linecap="round" stroke-linejoin="round"/></svg>
      <header class="head"><div class="logo"><img src="https://static.wixstatic.com/media/feee33_69d2a900adf0485a918168f1493bac96~mv2.png/v1/fill/w_122,h_122,al_c,q_85,enc_avif,quality_auto/PPGMIS%20logo_edited.png" alt="PPGMIS Logo"></div><div class="school"><h1>Part C - Annual Summary</h1><p>Holistic summary and sign-off</p></div><div class="tag">Page 9</div></header>
      <div class="bar">Domain-wise Annual Summary</div>
      <table>
        <tr><th>Domain</th><th>Beginner</th><th>Proficient</th><th>Advanced</th><th>Remarks</th></tr>
        <tr><td>Physical Development</td><td></td><td></td><td></td><td></td></tr>
        <tr><td>Socio-Emotional Development</td><td></td><td></td><td></td><td></td></tr>
        <tr><td>Cognitive Development</td><td></td><td></td><td></td><td></td></tr>
        <tr><td>Language & Literacy</td><td></td><td></td><td></td><td></td></tr>
        <tr><td>Aesthetic & Cultural Development</td><td></td><td></td><td></td><td></td></tr>
        <tr><td>Positive Learning Habits</td><td></td><td></td><td></td><td></td></tr>
      </table>

      <div class="bar">Teacher's Final Narrative</div>
      <div class="card" style="margin-top:2mm;">
        <div class="row">Overall growth summary <span class="value"><?= hpc_esc($teacherRemarks); ?></span></div>
        <div class="row">Areas of progress <span class="value"><?= hpc_esc($favColour . ($favFood ? ', ' . $favFood : '')); ?></span></div>
        <div class="row">Action plan for next class <span class="value"><?= hpc_esc($ambition); ?></span></div>
        <div class="row">Any special recommendation <span class="value"><?= hpc_esc($parentRemarkText); ?></span></div>
      </div>

      <div class="signs">
        <div class="sig">Class Teacher Signature<div class="line"></div></div>
        <div class="sig">Parent/Guardian Signature<div class="line"></div></div>
        <div class="sig">Principal Signature<div class="line"></div></div>
      </div>

      <div class="mini-note">Key Levels: Beginner = Needs support, Proficient = On track, Advanced = Exceeds expectations.</div>
      <div class="foot">Page 9 / 9 - Final Summary</div>
    </div>
  </section>

  <button class="print-btn" onclick="window.print()">Print / Save PDF</button>
</body>
</html>

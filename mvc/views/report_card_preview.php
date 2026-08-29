<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Report Card Preview</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .sheet { max-width: 900px; margin: 0 auto; }
    h1, h2 { margin: 0; }
    .meta { margin-top: 8px; display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
    .meta div { border: 1px solid #ddd; padding: 8px; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    th { background: #ffe3b8; text-align: left; }
    .print-btn { margin-top: 14px; padding: 10px 14px; background: #0b67d3; color: #fff; border: 0; border-radius: 6px; cursor: pointer; }
    @media print {
      .print-btn { display: none; }
      @page { size: A4 portrait; margin: 0; }
      body { margin: 8mm; }
    }
  </style>
</head>
<body>
  <div class="sheet">
    <h1>Holistic Progress Card</h1>
    <h2><?= esc($report['academic_year']) ?></h2>

    <div class="meta">
      <div><strong>Admission No:</strong> <?= esc($report['admission_no']) ?></div>
      <div><strong>Student:</strong> <?= esc($report['student_name']) ?></div>
      <div><strong>Class:</strong> <?= esc($report['class_name']) ?></div>
      <div><strong>Parent:</strong> <?= esc($report['parent_name']) ?></div>
      <div><strong>Contact:</strong> <?= esc($report['contact_no']) ?></div>
      <div><strong>DOB:</strong> <?= esc((string) $report['dob']) ?></div>
    </div>

    <table>
      <tr><th>Section</th><th>Yearly</th></tr>
      <tr><td>Physical Development</td><td><?= esc($report['physical_yearly']) ?></td></tr>
      <tr><td>Socio-Emotional Development</td><td><?= esc($report['socio_emotional_yearly']) ?></td></tr>
      <tr><td>Cognitive Development</td><td><?= esc($report['cognitive_yearly']) ?></td></tr>
      <tr><td>Language Development</td><td><?= esc($report['language_yearly']) ?></td></tr>
      <tr><td>Aesthetic Development</td><td><?= esc($report['aesthetic_yearly']) ?></td></tr>
      <tr><td>Positive Learning Habits</td><td><?= esc($report['learning_habits_yearly']) ?></td></tr>
      <tr><td>Attendance (Present / Working)</td><td><?= esc((string) $report['attendance_present_days']) ?> / <?= esc((string) $report['attendance_working_days']) ?></td></tr>
    </table>

    <table>
      <tr><th>Teacher Remark</th></tr>
      <tr><td><?= nl2br(esc($report['teacher_remark'])) ?></td></tr>
      <tr><th>Parent Remark</th></tr>
      <tr><td><?= nl2br(esc($report['parent_remark'])) ?></td></tr>
    </table>

    <button class="print-btn" onclick="window.print()">Print</button>
  </div>
</body>
</html>

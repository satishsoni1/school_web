<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Report Card</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 24px; background: #f6f8fb; }
    .wrap { max-width: 980px; margin: 0 auto; background: #fff; border: 1px solid #ddd; border-radius: 8px; padding: 20px; }
    h1 { margin-top: 0; }
    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    label { font-size: 14px; font-weight: 700; display: block; margin-bottom: 4px; }
    input, textarea, select { width: 100%; padding: 8px; border: 1px solid #bbb; border-radius: 4px; }
    textarea { min-height: 80px; }
    table { width: 100%; border-collapse: collapse; margin-top: 16px; }
    th, td { border: 1px solid #ccc; padding: 8px; }
    th { background: #ffe3b8; }
    .btn { margin-top: 16px; background: #0b67d3; color: #fff; border: 0; padding: 10px 16px; border-radius: 6px; cursor: pointer; }
  </style>
</head>
<body>
  <div class="wrap">
    <h1>Holistic Progress Card - Entry Form</h1>
    <form method="post" action="/report-card/store">
      <?= csrf_field() ?>

      <div class="grid">
        <div><label>Admission No</label><input name="admission_no" required></div>
        <div><label>Student Name</label><input name="student_name" required></div>
        <div><label>Date of Birth</label><input type="date" name="dob"></div>
        <div><label>Class</label><input name="class_name" value="Nursery"></div>
        <div><label>Parent Name</label><input name="parent_name"></div>
        <div><label>Contact No</label><input name="contact_no"></div>
        <div><label>Academic Year</label><input name="academic_year" placeholder="2026-2027" required></div>
        <div><label>Attendance (Working Days)</label><input type="number" name="attendance_working_days" min="0"></div>
        <div><label>Attendance (Present Days)</label><input type="number" name="attendance_present_days" min="0"></div>
      </div>

      <table>
        <tr>
          <th>Section</th>
          <th>Yearly</th>
        </tr>
        <tr><td>Physical Development</td><td>
          <select name="physical_yearly"><option value="">Select</option><option>Beginner</option><option>Proficient</option><option>Advanced</option></select>
        </td></tr>
        <tr><td>Socio-Emotional Development</td><td>
          <select name="socio_emotional_yearly"><option value="">Select</option><option>Beginner</option><option>Proficient</option><option>Advanced</option></select>
        </td></tr>
        <tr><td>Cognitive Development</td><td>
          <select name="cognitive_yearly"><option value="">Select</option><option>Beginner</option><option>Proficient</option><option>Advanced</option></select>
        </td></tr>
        <tr><td>Language Development</td><td>
          <select name="language_yearly"><option value="">Select</option><option>Beginner</option><option>Proficient</option><option>Advanced</option></select>
        </td></tr>
        <tr><td>Aesthetic Development</td><td>
          <select name="aesthetic_yearly"><option value="">Select</option><option>Beginner</option><option>Proficient</option><option>Advanced</option></select>
        </td></tr>
        <tr><td>Positive Learning Habits</td><td>
          <select name="learning_habits_yearly"><option value="">Select</option><option>Beginner</option><option>Proficient</option><option>Advanced</option></select>
        </td></tr>
      </table>

      <div style="margin-top:16px;">
        <label>Teacher Remark</label>
        <textarea name="teacher_remark"></textarea>
      </div>
      <div style="margin-top:12px;">
        <label>Parent Remark</label>
        <textarea name="parent_remark"></textarea>
      </div>

      <button class="btn" type="submit">Save & Open Report</button>
    </form>
  </div>
</body>
</html>

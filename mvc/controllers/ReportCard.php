<?php

namespace App\Controllers;

use App\Models\ReportCardModel;
use App\Models\StudentModel;

class ReportCard extends BaseController
{
    public function create()
    {
        return view('report_card_form');
    }

    public function store()
    {
        $studentModel = new StudentModel();
        $reportCardModel = new ReportCardModel();

        $studentData = [
            'admission_no' => (string) $this->request->getPost('admission_no'),
            'student_name' => (string) $this->request->getPost('student_name'),
            'dob' => $this->request->getPost('dob') ?: null,
            'class_name' => (string) ($this->request->getPost('class_name') ?: 'Nursery'),
            'parent_name' => (string) $this->request->getPost('parent_name'),
            'contact_no' => (string) $this->request->getPost('contact_no'),
        ];

        $existing = $studentModel->where('admission_no', $studentData['admission_no'])->first();
        if ($existing) {
            $studentId = (int) $existing['id'];
            $studentModel->update($studentId, $studentData);
        } else {
            $studentId = (int) $studentModel->insert($studentData, true);
        }

        $reportData = [
            'student_id' => $studentId,
            'academic_year' => (string) $this->request->getPost('academic_year'),
            'attendance_working_days' => (int) $this->request->getPost('attendance_working_days'),
            'attendance_present_days' => (int) $this->request->getPost('attendance_present_days'),
            'physical_yearly' => (string) $this->request->getPost('physical_yearly'),
            'socio_emotional_yearly' => (string) $this->request->getPost('socio_emotional_yearly'),
            'cognitive_yearly' => (string) $this->request->getPost('cognitive_yearly'),
            'language_yearly' => (string) $this->request->getPost('language_yearly'),
            'aesthetic_yearly' => (string) $this->request->getPost('aesthetic_yearly'),
            'learning_habits_yearly' => (string) $this->request->getPost('learning_habits_yearly'),
            'teacher_remark' => (string) $this->request->getPost('teacher_remark'),
            'parent_remark' => (string) $this->request->getPost('parent_remark'),
        ];

        $reportId = (int) $reportCardModel->insert($reportData, true);

        return redirect()->to('/report-card/view/' . $reportId);
    }

    public function view(int $id)
    {
        $reportCardModel = new ReportCardModel();
        $data['report'] = $reportCardModel->withStudent($id);

        if (!$data['report']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Report not found');
        }

        return view('report_card_preview', $data);
    }
}

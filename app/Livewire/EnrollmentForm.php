<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Student;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class EnrollmentForm extends Component
{
    public Course $course;
    public bool $showForm = false;

    // Form fields
    public string $studentName = '';
    public string $studentEmail = '';
    public string $studentPhone = '';
    public string $message = '';

    // UI states
    public bool $isSubmitting = false;
    public string $successMessage = '';
    public string $errorMessage = '';

    protected $rules = [
        'studentName' => 'required|string|max:255',
        'studentEmail' => 'required|email|max:255',
        'studentPhone' => 'required|string|max:20',
        'message' => 'nullable|string|max:500',
    ];

    protected $messages = [
        'studentName.required' => 'Vui lòng nhập họ và tên',
        'studentEmail.required' => 'Vui lòng nhập email',
        'studentEmail.email' => 'Email không đúng định dạng',
        'studentPhone.required' => 'Vui lòng nhập số điện thoại',
        'message.max' => 'Tin nhắn không được vượt quá 500 ký tự',
    ];

    public function mount(Course $course)
    {
        $this->course = $course;
    }

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;

        if (!$this->showForm) {
            $this->resetForm();
        }
    }

    public function resetForm()
    {
        $this->reset(['studentName', 'studentEmail', 'studentPhone', 'message', 'successMessage', 'errorMessage']);
        $this->resetErrorBag();
    }

    public function submit()
    {
        $this->isSubmitting = true;
        $this->errorMessage = '';
        $this->successMessage = '';

        try {
            // Validate form
            $this->validate();

            // Check if course is available
            if (!$this->course->canEnroll()) {
                $this->errorMessage = $this->course->is_full
                    ? 'Khóa học đã đầy, vui lòng chọn khóa học khác'
                    : 'Khóa học hiện không thể đăng ký';
                return;
            }

            // Find or create student
            $student = Student::firstOrCreate(
                ['email' => $this->studentEmail],
                [
                    'name' => $this->studentName,
                    'phone' => $this->studentPhone,
                    'status' => 'active'
                ]
            );

            // Check if already enrolled
            if ($student->isEnrolledIn($this->course->id)) {
                $this->errorMessage = 'Bạn đã đăng ký khóa học này rồi';
                return;
            }

            // Enroll student
            $student->courses()->attach($this->course->id, [
                'enrolled_at' => now(),
                'status' => 'enrolled',
                'notes' => $this->message,
            ]);

            // Success
            $this->successMessage = 'Đăng ký khóa học thành công! Chúng tôi sẽ liên hệ với bạn sớm nhất.';
            $this->resetForm();
            $this->showForm = false;

            // Refresh course data to update enrollment count
            $this->course->refresh();

            // Dispatch browser event for analytics or other purposes
            $this->dispatch('courseEnrolled', [
                'courseId' => $this->course->id,
                'courseName' => $this->course->title,
                'studentEmail' => $this->studentEmail
            ]);

        } catch (\Exception $e) {
            $this->errorMessage = 'Có lỗi xảy ra, vui lòng thử lại sau';
            Log::error('Course enrollment error: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function render()
    {
        return view('livewire.enrollment-form');
    }
}

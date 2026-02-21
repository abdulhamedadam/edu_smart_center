<?php

use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\HomeworkController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LessonController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentParentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\CommunicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicStudentProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/s/{token}', [PublicStudentProfileController::class, 'show'])
    ->name('public.students.show');

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/admin', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::resource('grades', GradeController::class)->except(['create', 'show']);
        Route::resource('subjects', SubjectController::class)->except(['create', 'show']);
        Route::resource('groups', GroupController::class)->except(['create', 'show']);
        Route::get('grades/{grade}/subjects', [SubjectController::class, 'forGrade'])
            ->name('grades.subjects');
        Route::resource('students', StudentController::class)->except(['show']);
        Route::resource('teachers', TeacherController::class);
        Route::get('schedule', [LessonController::class, 'index'])->name('schedule.index');
        Route::post('schedule', [LessonController::class, 'store'])->name('schedule.store');
        Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
        Route::get('attendance/student/{student}', [AttendanceController::class, 'student'])->name('attendance.student');
        Route::get('attendance/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
        Route::post('attendance/scan', [AttendanceController::class, 'scanStore'])->name('attendance.scan.store');
        Route::post('attendance/absence', [AttendanceController::class, 'storeAbsence'])->name('attendance.absence.store');
        Route::post('attendance/{attendance}/toggle', [AttendanceController::class, 'toggle'])->name('attendance.toggle');
        Route::resource('homework', HomeworkController::class);
        Route::post('homework/{homework}/submit', [HomeworkController::class, 'storeSubmission'])->name('homework.submit');
        Route::post('homework/{homework}/grades', [HomeworkController::class, 'saveGrades'])->name('homework.grades');
        Route::resource('exams', ExamController::class);
        Route::post('exams/{exam}/marks', [ExamController::class, 'saveMarks'])->name('exams.marks');
        Route::get('exams/student/{student}', [ExamController::class, 'student'])->name('exams.student');
        Route::resource('subscriptions', SubscriptionController::class)->except(['show']);
        Route::post('subscriptions/{subscription}/pay', [SubscriptionController::class, 'pay'])->name('subscriptions.pay');
        Route::get('subscriptions/reports/monthly-income', [SubscriptionController::class, 'monthlyIncome'])->name('subscriptions.reports.monthly');
        Route::get('subscriptions/reports/teacher-commission', [SubscriptionController::class, 'teacherCommission'])->name('subscriptions.reports.teacher_commission');
        Route::resource('expenses', ExpenseController::class)->except(['show']);
        Route::resource('parents', StudentParentController::class)->only(['index', 'store', 'destroy']);
        Route::get('communication/absence', [CommunicationController::class, 'absenceNotifications'])->name('communication.absence');
        Route::post('communication/absence/send', [CommunicationController::class, 'sendAbsenceNotifications'])->name('communication.absence.send');
        Route::get('communication/exams', [CommunicationController::class, 'examNotifications'])->name('communication.exams');
        Route::post('communication/exams/{exam}/send', [CommunicationController::class, 'sendExamNotifications'])->name('communication.exams.send');
        Route::get('communication/broadcast', [CommunicationController::class, 'broadcast'])->name('communication.broadcast');
        Route::post('communication/broadcast/send', [CommunicationController::class, 'sendBroadcast'])->name('communication.broadcast.send');
        Route::get('communication/parent/{parent}', [CommunicationController::class, 'parentReport'])->name('communication.parent_report');
    });

require __DIR__.'/auth.php';

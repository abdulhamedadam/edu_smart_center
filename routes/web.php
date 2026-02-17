<?php

use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\StudentParentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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
        Route::resource('parents', StudentParentController::class)->only(['index', 'store', 'destroy']);
    });

require __DIR__.'/auth.php';

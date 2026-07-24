<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\LecturerController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\ThesisController;
use App\Http\Controllers\Admin\ThesisProposalController;
use App\Http\Controllers\Admin\ThesisAdvisorController;
use App\Http\Controllers\Admin\MentoringLogController;
use App\Http\Controllers\Admin\ProposalSeminarController;
use App\Http\Controllers\Admin\ProposalExaminerController;
use App\Http\Controllers\Admin\ProposalCommentController;
use App\Http\Controllers\Admin\ThesisDefenseController;
use App\Http\Controllers\Admin\DefenseExaminerController;
use App\Http\Controllers\Admin\DefenseRevisionController;
use App\Http\Controllers\Admin\YudisiumController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::prefix('dashboard')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/mahasiswa', [DashboardController::class, 'mahasiswa'])->name('dashboard.mahasiswa');
    Route::get('/baak', [DashboardController::class, 'baak'])->name('dashboard.baak');
    Route::get('/finance', [DashboardController::class, 'finance'])->name('dashboard.finance');
    Route::get('/perpustakaan', [DashboardController::class, 'perpustakaan'])->name('dashboard.perpustakaan');
    Route::get('/dosen', [DashboardController::class, 'dosen'])->name('dashboard.dosen');
});

Route::prefix('admin')->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('staff', StaffController::class);
    Route::resource('lecturers', LecturerController::class);
    Route::resource('students', StudentController::class);
    Route::resource('theses', ThesisController::class);
    Route::resource('thesis-proposals', ThesisProposalController::class);
    Route::post('thesis-proposals/{id}/approve', [ThesisProposalController::class, 'approve'])->name('thesis-proposals.approve');
    Route::resource('thesis-advisors', ThesisAdvisorController::class);
    Route::resource('mentoring-logs', MentoringLogController::class);
    Route::resource('proposal-seminars', ProposalSeminarController::class);
    Route::resource('proposal-examiners', ProposalExaminerController::class);
    Route::resource('proposal-comments', ProposalCommentController::class);
    Route::resource('thesis-defenses', ThesisDefenseController::class);
    Route::resource('defense-examiners', DefenseExaminerController::class);
    Route::resource('defense-revisions', DefenseRevisionController::class);
    Route::post('defense-revisions/{id}/approve', [DefenseRevisionController::class, 'approve'])->name('defense-revisions.approve');
    Route::resource('yudisiums', YudisiumController::class);
    Route::get('yudisiums/{id}/print', [YudisiumController::class, 'print'])->name('yudisiums.print');
});
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

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/mahasiswa', [DashboardController::class, 'mahasiswa'])->name('dashboard.mahasiswa')->middleware('role:student,admin');
        Route::get('/baak', [DashboardController::class, 'baak'])->name('dashboard.baak')->middleware('role:staff_baak,admin');
        Route::get('/finance', [DashboardController::class, 'finance'])->name('dashboard.finance')->middleware('role:staff_finance,admin');
        Route::get('/perpustakaan', [DashboardController::class, 'perpustakaan'])->name('dashboard.perpustakaan')->middleware('role:staff_library,admin');
        Route::get('/dosen', [DashboardController::class, 'dosen'])->name('dashboard.dosen')->middleware('role:lecturer,admin');
    });

    Route::prefix('dosen')->middleware('role:lecturer')->name('dosen.')->group(function () {
        Route::get('/advisees', [\App\Http\Controllers\Lecturer\AdviseeController::class, 'index'])->name('advisees.index');
        Route::post('/advisees/{id}/approve', [\App\Http\Controllers\Lecturer\AdviseeController::class, 'approve'])->name('advisees.approve');
        
        Route::get('/mentoring-logs', [\App\Http\Controllers\Lecturer\MentoringLogController::class, 'index'])->name('mentoring-logs.index');
        Route::post('/mentoring-logs/{id}', [\App\Http\Controllers\Lecturer\MentoringLogController::class, 'update'])->name('mentoring-logs.update');
        
        Route::get('/exams', [\App\Http\Controllers\Lecturer\ExamScheduleController::class, 'index'])->name('exams.index');
        Route::post('/exams/proposal/{id}/evaluate', [\App\Http\Controllers\Lecturer\ExamScheduleController::class, 'evaluateProposal'])->name('exams.proposal.evaluate');
        Route::post('/exams/defense/{id}/evaluate', [\App\Http\Controllers\Lecturer\ExamScheduleController::class, 'evaluateDefense'])->name('exams.defense.evaluate');
        
        Route::get('/revisions', [\App\Http\Controllers\Lecturer\RevisionController::class, 'index'])->name('revisions.index');
        Route::post('/revisions/{id}/approve', [\App\Http\Controllers\Lecturer\RevisionController::class, 'approve'])->name('revisions.approve');
    });

    Route::prefix('kaprodi')->middleware('role:kaprodi')->name('kaprodi.')->group(function () {
        Route::get('/proposals', [\App\Http\Controllers\Kaprodi\ProposalController::class, 'index'])->name('proposals.index');
        Route::post('/proposals/{id}/approve', [\App\Http\Controllers\Kaprodi\ProposalController::class, 'approve'])->name('proposals.approve');
        
        Route::get('/advisors', [\App\Http\Controllers\Kaprodi\AdvisorController::class, 'index'])->name('advisors.index');
        Route::post('/advisors', [\App\Http\Controllers\Kaprodi\AdvisorController::class, 'store'])->name('advisors.store');
        
        Route::get('/examiners', [\App\Http\Controllers\Kaprodi\ExaminerController::class, 'index'])->name('examiners.index');
        Route::post('/examiners', [\App\Http\Controllers\Kaprodi\ExaminerController::class, 'store'])->name('examiners.store');
        Route::post('/examiners/defense', [\App\Http\Controllers\Kaprodi\ExaminerController::class, 'storeDefense'])->name('examiners.store_defense');
    });

    Route::prefix('finance')->middleware('role:staff_finance')->name('finance.')->group(function () {
        Route::get('/clearance', [\App\Http\Controllers\Finance\ClearanceController::class, 'index'])->name('clearance.index');
        Route::post('/clearance/student/{id}', [\App\Http\Controllers\Finance\ClearanceController::class, 'updateStudent'])->name('clearance.update_student');
        Route::post('/clearance/proposal/{id}', [\App\Http\Controllers\Finance\ClearanceController::class, 'updateProposal'])->name('clearance.update_proposal');
    });

    Route::prefix('student')->middleware('role:student')->name('student.')->group(function () {
        Route::get('/proposal', [\App\Http\Controllers\Student\ProposalController::class, 'index'])->name('proposal.index');
        Route::post('/proposal', [\App\Http\Controllers\Student\ProposalController::class, 'store'])->name('proposal.store');
        Route::delete('/proposal', [\App\Http\Controllers\Student\ProposalController::class, 'destroy'])->name('proposal.destroy');
        
        Route::get('/mentoring-logs', [\App\Http\Controllers\Student\MentoringController::class, 'index'])->name('mentoring-logs.index');
        Route::post('/mentoring-logs', [\App\Http\Controllers\Student\MentoringController::class, 'store'])->name('mentoring-logs.store');
        
        Route::get('/defenses', [\App\Http\Controllers\Student\DefenseController::class, 'index'])->name('defenses.index');
        Route::post('/defenses', [\App\Http\Controllers\Student\DefenseController::class, 'store'])->name('defenses.store');
        Route::put('/defenses', [\App\Http\Controllers\Student\DefenseController::class, 'update'])->name('defenses.update');
        Route::delete('/defenses', [\App\Http\Controllers\Student\DefenseController::class, 'destroy'])->name('defenses.destroy');
        
        Route::get('/revisions', [\App\Http\Controllers\Student\RevisionController::class, 'index'])->name('revisions.index');
        Route::post('/revisions', [\App\Http\Controllers\Student\RevisionController::class, 'store'])->name('revisions.store');
    });

    Route::prefix('library')->middleware('role:staff_library')->name('library.')->group(function () {
        Route::get('/clearance', [\App\Http\Controllers\Library\ClearanceController::class, 'index'])->name('clearance.index');
        Route::post('/clearance/student/{id}', [\App\Http\Controllers\Library\ClearanceController::class, 'updateStudent'])->name('clearance.update_student');
    });

    Route::prefix('baak')->middleware('role:staff_baak')->name('baak.')->group(function () {
        Route::get('/clearance', [\App\Http\Controllers\Baak\ClearanceController::class, 'index'])->name('clearance.index');
        Route::post('/clearance/student/{id}', [\App\Http\Controllers\Baak\ClearanceController::class, 'updateStudent'])->name('clearance.update_student');
        Route::post('/clearance/proposal/{id}', [\App\Http\Controllers\Baak\ClearanceController::class, 'updateProposal'])->name('clearance.update_proposal');
    });

    // Admin Only
    Route::prefix('admin')->middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('staff', StaffController::class);
        Route::resource('lecturers', LecturerController::class);
        Route::resource('theses', ThesisController::class);
        Route::resource('thesis-advisors', ThesisAdvisorController::class);
        Route::resource('mentoring-logs', MentoringLogController::class);
    });

    // Admin & BAAK Shared (Scheduling & Approval & Student Management)
    Route::prefix('admin')->middleware('role:admin,staff_baak')->group(function () {
        Route::resource('students', StudentController::class);
        Route::resource('thesis-proposals', ThesisProposalController::class);
        Route::post('thesis-proposals/{id}/approve', [ThesisProposalController::class, 'approve'])->name('thesis-proposals.approve');
        Route::resource('proposal-seminars', ProposalSeminarController::class);
        Route::resource('proposal-examiners', ProposalExaminerController::class);
        Route::resource('proposal-comments', ProposalCommentController::class);
        Route::resource('thesis-defenses', ThesisDefenseController::class);
        Route::resource('defense-examiners', DefenseExaminerController::class);
        Route::resource('defense-revisions', DefenseRevisionController::class);
        Route::post('defense-revisions/{id}/approve', [DefenseRevisionController::class, 'approve'])->name('defense-revisions.approve');
        Route::resource('yudisiums', YudisiumController::class);
    });

    Route::get('admin/yudisiums/{id}/print', [YudisiumController::class, 'print'])
        ->name('yudisiums.print')
        ->middleware('role:admin,staff_baak,student');
});
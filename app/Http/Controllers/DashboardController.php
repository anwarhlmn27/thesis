<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Lecturer;
use App\Models\Staff;
use App\Models\Thesis;
use App\Models\ThesisProposal;
use App\Models\ProposalSeminar;
use App\Models\ThesisDefense;
use App\Models\MentoringLog;
use App\Models\DefenseRevision;
use App\Models\Yudisium;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard Utama / Overview Hub
     */
    public function index()
    {
        $user = auth()->user();
        if ($user && $user->role !== 'admin') {
            if ($user->role === 'student') return redirect()->route('dashboard.mahasiswa');
            if ($user->role === 'lecturer') return redirect()->route('dashboard.dosen');
            if ($user->role === 'staff_baak') return redirect()->route('dashboard.baak');
            if ($user->role === 'staff_finance') return redirect()->route('dashboard.finance');
            if ($user->role === 'staff_library') return redirect()->route('dashboard.perpustakaan');
        }

        $stats = [
            'total_students' => Student::count(),
            'total_lecturers' => Lecturer::count(),
            'total_staff' => Staff::count(),
            'total_theses' => Thesis::count(),
            'active_proposals' => ThesisProposal::where('eligibility_status', 'pending')->count(),
            'scheduled_seminars' => ProposalSeminar::where('status', 'scheduled')->count(),
            'scheduled_defenses' => ThesisDefense::where('status', 'scheduled')->count(),
            'total_yudisiums' => Yudisium::count(),
            'finance_paid' => Student::where('is_paid', true)->count(),
            'library_clear' => Student::where('is_library_clear', true)->count(),
            'coursework_completed' => Student::where('is_coursework_completed', true)->count(),
        ];

        $recentTheses = Thesis::with(['student.user'])->latest()->take(5)->get();

        return view('dashboard.index', compact('stats', 'recentTheses'));
    }

    /**
     * Dashboard Mahasiswa
     */
    public function mahasiswa(Request $request)
    {
        $user = auth()->user();
        $students = collect();
        
        if ($user && $user->role === 'student') {
            $selectedStudentId = $user->student ? $user->student->id : null;
        } else {
            $students = Student::with('user')->get();
            $selectedStudentId = $request->get('student_id', $students->first()?->id);
        }
        
        $student = Student::with([
            'user',
            'theses.thesisAdvisors.lecturer.user',
            'theses.proposalSeminars.proposalExaminers.lecturer.user',
            'theses.proposalSeminars.proposalComments.lecturer.user',
            'theses.mentoringLogs.thesisAdvisor.lecturer.user',
            'theses.thesisDefenses.defenseExaminers.lecturer.user',
            'theses.thesisDefenses.defenseRevisions.lecturer.user',
            'yudisiums'
        ])->find($selectedStudentId);

        $thesis = $student?->theses->last();
        $approvedLogsCount = 0;
        if ($thesis) {
            $approvedLogsCount = MentoringLog::where('thesis_id', $thesis->id)
                ->where('status', 'approved')
                ->count();
        }

        return view('dashboard.mahasiswa', compact('students', 'student', 'thesis', 'approvedLogsCount'));
    }

    /**
     * Dashboard Staf BAAK
     */
    public function baak()
    {
        $totalStudents = Student::count();
        $pendingCoursework = Student::where('is_coursework_completed', false)->count();
        $pendingProposals = ThesisProposal::where('is_baak_approved', false)->count();
        $activeTheses = Thesis::whereNotIn('status', ['graduated', 'rejected', 'cancelled'])->count();
        $totalYudisiumStudents = \Illuminate\Support\Facades\DB::table('yudisium_students')->count();

        $stats = [
            'total_students' => $totalStudents,
            'pending_validation' => $pendingCoursework,
            'pending_coursework' => $pendingCoursework,
            'pending_proposals' => $pendingProposals,
            'active_theses' => $activeTheses,
            'total_theses' => Thesis::count(),
            'total_yudisium_students' => $totalYudisiumStudents,
            'total_yudisiums' => Yudisium::count(),
            'scheduled_seminars' => ProposalSeminar::where('status', 'scheduled')->count(),
            'scheduled_defenses' => ThesisDefense::where('status', 'scheduled')->count(),
        ];

        $pendingStudents = Student::with('user')
            ->where('is_coursework_completed', false)
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        $pendingProposals = ThesisProposal::with('thesis.student.user')
            ->where('is_baak_approved', false)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $upcomingSeminars = ProposalSeminar::with('thesis.student.user')
            ->where('status', 'scheduled')
            ->get();

        $upcomingDefenses = ThesisDefense::with('thesis.student.user')
            ->where('status', 'scheduled')
            ->get();

        $yudisiums = Yudisium::withCount('students')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.baak', compact(
            'stats',
            'pendingStudents',
            'pendingProposals',
            'upcomingSeminars',
            'upcomingDefenses',
            'yudisiums'
        ));
    }

    /**
     * Dashboard Staf Finance
     */
    public function finance()
    {
        $totalStudents = Student::count();
        $paidCount = Student::where('is_paid', true)->count();
        $unpaidCount = Student::where('is_paid', false)->count();
        $paidPercentage = $totalStudents > 0 ? round(($paidCount / $totalStudents) * 100) : 0;

        $pendingProposalsCount = ThesisProposal::where('is_finance_approved', false)->count();
        $approvedProposalsCount = ThesisProposal::where('is_finance_approved', true)->count();

        $stats = [
            'total_students' => $totalStudents,
            'paid_count' => $paidCount,
            'unpaid_count' => $unpaidCount,
            'paid_percentage' => $paidPercentage,
            'pending_proposals' => $pendingProposalsCount,
            'approved_proposals' => $approvedProposalsCount,
            'pending_verification' => $pendingProposalsCount + $unpaidCount,
            'total_verified' => $paidCount + $approvedProposalsCount,
        ];

        $pendingProposals = ThesisProposal::with('thesis.student.user')
            ->where('is_finance_approved', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $unpaidStudents = Student::with('user')
            ->where('is_paid', false)
            ->orderBy('id', 'desc')
            ->get();

        return view('dashboard.finance', compact('stats', 'pendingProposals', 'unpaidStudents'));
    }

    /**
     * Dashboard Staf Perpustakaan
     */
    public function perpustakaan()
    {
        $totalStudents = Student::count();
        $clearCount = Student::where('is_library_clear', true)->count();
        $pendingCount = Student::where('is_library_clear', false)->count();
        $clearPercentage = $totalStudents > 0 ? round(($clearCount / $totalStudents) * 100) : 0;

        $stats = [
            'total_students' => $totalStudents,
            'clear_count' => $clearCount,
            'pending_count' => $pendingCount,
            'clear_percentage' => $clearPercentage,
            'final_submissions' => Thesis::whereNotNull('final_file_path')->count(),
        ];

        $pendingStudents = Student::with(['user', 'theses'])
            ->where('is_library_clear', false)
            ->orderBy('id', 'desc')
            ->get();

        $clearedStudents = Student::with(['user', 'theses'])
            ->where('is_library_clear', true)
            ->orderBy('updated_at', 'desc')
            ->get();

        $finalSubmissions = Thesis::with('student.user')
            ->whereNotNull('final_file_path')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('dashboard.perpustakaan', compact('stats', 'pendingStudents', 'clearedStudents', 'finalSubmissions'));
    }

    /**
     * Dashboard Dosen / Kaprodi
     */
    public function dosen(Request $request)
    {
        $user = auth()->user();
        $lecturers = collect();
        
        if ($user && $user->role === 'lecturer') {
            $selectedLecturerId = $user->lecturer ? $user->lecturer->id : null;
        } else {
            $lecturers = Lecturer::with('user')->get();
            $selectedLecturerId = $request->get('lecturer_id', $lecturers->first()?->id);
        }

        $lecturer = Lecturer::with('user')->find($selectedLecturerId);

        if (!$lecturer) {
            return view('dashboard.dosen', [
                'lecturers' => $lecturers,
                'lecturer' => null,
                'stats' => [
                    'total_advisees' => 0,
                    'active_advisees' => 0,
                    'completed_advisees' => 0,
                    'pending_logs' => 0,
                    'upcoming_exams' => 0,
                    'pending_revisions' => 0,
                    'ready_for_defense' => 0,
                    'total_pending_actions' => 0,
                    'total_exams' => 0,
                ],
                'adviseeTheses' => collect(),
                'pendingLogs' => collect(),
                'upcomingExams' => collect(),
                'pendingRevisions' => collect(),
            ]);
        }

        // Advisees (Mahasiswa bimbingan)
        $adviseeTheses = Thesis::whereHas('thesisAdvisors', function ($q) use ($selectedLecturerId) {
            $q->where('lecturer_id', $selectedLecturerId);
        })->with([
            'student.user',
            'thesisAdvisors.lecturer.user',
            'mentoringLogs' => function ($q) {
                $q->orderBy('mentoring_date', 'desc');
            },
            'thesisDefenses',
            'proposalSeminars'
        ])->get();

        // Pending Mentoring Logs to review
        $pendingLogs = MentoringLog::whereHas('thesisAdvisor', function ($q) use ($selectedLecturerId) {
            $q->where('lecturer_id', $selectedLecturerId);
        })->where('status', 'submitted')
          ->with(['thesis.student.user', 'thesisAdvisor'])
          ->orderBy('mentoring_date', 'desc')
          ->get();

        // Proposal Seminars as examiner
        $proposalExams = ProposalSeminar::whereHas('proposalExaminers', function ($q) use ($selectedLecturerId) {
            $q->where('lecturer_id', $selectedLecturerId);
        })->with([
            'thesis.student.user',
            'proposalExaminers' => function ($q) use ($selectedLecturerId) {
                $q->where('lecturer_id', $selectedLecturerId);
            }
        ])->get();

        // Thesis Defenses as examiner
        $defenseExams = ThesisDefense::whereHas('defenseExaminers', function ($q) use ($selectedLecturerId) {
            $q->where('lecturer_id', $selectedLecturerId);
        })->with([
            'thesis.student.user',
            'defenseExaminers' => function ($q) use ($selectedLecturerId) {
                $q->where('lecturer_id', $selectedLecturerId);
            }
        ])->get();

        // Pending Revisions to approve
        $pendingRevisions = DefenseRevision::where('lecturer_id', $selectedLecturerId)
            ->where('is_approved', false)
            ->with(['thesisDefense.thesis.student.user'])
            ->get();

        // Process upcoming exams list combining sempro & defense
        $upcomingExams = collect();

        foreach ($proposalExams as $sem) {
            $examiner = $sem->proposalExaminers->where('lecturer_id', $selectedLecturerId)->first();
            $upcomingExams->push((object)[
                'id' => $sem->id,
                'kind' => 'proposal',
                'type_label' => 'Seminar Proposal',
                'type_color' => 'info',
                'date' => $sem->seminar_date,
                'room' => $sem->room ?? 'Ruang TBA',
                'student_name' => $sem->thesis?->student?->user?->name ?? 'Mahasiswa',
                'student_nim' => $sem->thesis?->student?->nim ?? '-',
                'thesis_title' => $sem->thesis?->title ?? '-',
                'role_label' => ($examiner && $examiner->position === 'chairman') ? 'Ketua Penguji' : 'Penguji',
                'status' => $sem->status,
                'is_evaluated' => ($examiner && $examiner->status && $examiner->status !== 'pending'),
                'eval_status' => $examiner?->status,
                'notes' => $examiner?->notes,
                'score' => null,
            ]);
        }

        foreach ($defenseExams as $def) {
            $examiner = $def->defenseExaminers->where('lecturer_id', $selectedLecturerId)->first();
            $roleLabel = 'Anggota Penguji';
            if ($examiner) {
                if ($examiner->position === 'chairman') $roleLabel = 'Ketua Penguji';
                elseif ($examiner->position === 'secretary') $roleLabel = 'Sekretaris';
            }

            $upcomingExams->push((object)[
                'id' => $def->id,
                'kind' => 'defense',
                'type_label' => 'Sidang Skripsi',
                'type_color' => 'primary',
                'date' => $def->defense_date,
                'room' => $def->room ?? 'Ruang TBA',
                'student_name' => $def->thesis?->student?->user?->name ?? 'Mahasiswa',
                'student_nim' => $def->thesis?->student?->nim ?? '-',
                'thesis_title' => $def->thesis?->title ?? '-',
                'role_label' => $roleLabel,
                'status' => $def->status,
                'is_evaluated' => ($examiner && $examiner->score !== null),
                'eval_status' => null,
                'notes' => $examiner?->notes,
                'score' => $examiner?->score,
            ]);
        }

        // Filter to only show active/scheduled exams on dashboard
        $upcomingExams = $upcomingExams->filter(function ($item) {
            return $item->status === 'scheduled';
        })->sort(function ($a, $b) {
            return $a->date <=> $b->date; // nearest future first
        })->values();

        // Calculate Advisee counts
        $totalAdvisees = $adviseeTheses->count();
        $completedAdvisees = $adviseeTheses->filter(function ($t) {
            return in_array($t->status, ['graduated', 'revision_approved', 'yudisium_ready']);
        })->count();
        $activeAdvisees = $totalAdvisees - $completedAdvisees;
        
        $activeAdviseesList = $adviseeTheses->filter(function ($t) {
            return !in_array($t->status, ['graduated', 'revision_approved', 'yudisium_ready']);
        })->values();

        // Calculate ready for defense ACC
        $readyForDefenseCount = $adviseeTheses->filter(function ($t) use ($selectedLecturerId) {
            $adv = $t->thesisAdvisors->where('lecturer_id', $selectedLecturerId)->first();
            if (!$adv || $adv->is_approved_for_defense) return false;
            $approvedLogs = $t->mentoringLogs->where('thesis_advisor_id', $adv->id)->where('status', 'approved')->count();
            return $approvedLogs >= 10;
        })->count();

        $totalPendingActions = $pendingLogs->count() + $pendingRevisions->count() + $readyForDefenseCount;
        $upcomingExamsCount = $upcomingExams->count();

        $stats = [
            'total_advisees' => $totalAdvisees,
            'active_advisees' => $activeAdvisees,
            'completed_advisees' => $completedAdvisees,
            'pending_logs' => $pendingLogs->count(),
            'pending_revisions' => $pendingRevisions->count(),
            'ready_for_defense' => $readyForDefenseCount,
            'total_pending_actions' => $totalPendingActions,
            'upcoming_exams' => $upcomingExamsCount,
            'total_exams' => $upcomingExams->count(),
        ];

        return view('dashboard.dosen', compact(
            'lecturers',
            'lecturer',
            'stats',
            'adviseeTheses',
            'activeAdviseesList',
            'pendingLogs',
            'upcomingExams',
            'pendingRevisions'
        ));
    }
}

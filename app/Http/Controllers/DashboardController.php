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
        $stats = [
            'total_students' => Student::count(),
            'pending_coursework' => Student::where('is_coursework_completed', false)->count(),
            'pending_proposals' => ThesisProposal::where('is_baak_approved', false)->count(),
            'scheduled_seminars' => ProposalSeminar::where('status', 'scheduled')->count(),
            'scheduled_defenses' => ThesisDefense::where('status', 'scheduled')->count(),
            'total_yudisiums' => Yudisium::count(),
        ];

        $pendingStudents = Student::with('user')->where('is_coursework_completed', false)->take(10)->get();
        $pendingProposals = ThesisProposal::with('thesis.student.user')->where('is_baak_approved', false)->take(10)->get();
        $upcomingSeminars = ProposalSeminar::with('thesis.student.user')->where('status', 'scheduled')->get();
        $upcomingDefenses = ThesisDefense::with('thesis.student.user')->where('status', 'scheduled')->get();

        return view('dashboard.baak', compact('stats', 'pendingStudents', 'pendingProposals', 'upcomingSeminars', 'upcomingDefenses'));
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

        $stats = [
            'total_students' => $totalStudents,
            'paid_count' => $paidCount,
            'unpaid_count' => $unpaidCount,
            'paid_percentage' => $paidPercentage,
            'pending_proposals' => ThesisProposal::where('is_finance_approved', false)->count(),
        ];

        $students = Student::with('user')->orderBy('is_paid', 'asc')->get();
        $pendingProposals = ThesisProposal::with('thesis.student.user')->where('is_finance_approved', false)->get();

        return view('dashboard.finance', compact('stats', 'students', 'pendingProposals'));
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

        $students = Student::with('user')->orderBy('is_library_clear', 'asc')->get();

        return view('dashboard.perpustakaan', compact('stats', 'students'));
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

        // Advisees (Mahasiswa bimbingan)
        $adviseeTheses = Thesis::whereHas('thesisAdvisors', function ($q) use ($selectedLecturerId) {
            $q->where('lecturer_id', $selectedLecturerId);
        })->with(['student.user', 'mentoringLogs'])->get();

        // Pending Mentoring Logs to review
        $pendingLogs = MentoringLog::whereHas('thesisAdvisor', function ($q) use ($selectedLecturerId) {
            $q->where('lecturer_id', $selectedLecturerId);
        })->where('status', 'submitted')->with(['thesis.student.user'])->get();

        // Proposal Seminars as examiner
        $proposalExams = ProposalSeminar::whereHas('proposalExaminers', function ($q) use ($selectedLecturerId) {
            $q->where('lecturer_id', $selectedLecturerId);
        })->with(['thesis.student.user', 'proposalExaminers'])->get();

        // Thesis Defenses as examiner
        $defenseExams = ThesisDefense::whereHas('defenseExaminers', function ($q) use ($selectedLecturerId) {
            $q->where('lecturer_id', $selectedLecturerId);
        })->with(['thesis.student.user', 'defenseExaminers'])->get();

        // Pending Revisions to approve
        $pendingRevisions = DefenseRevision::where('lecturer_id', $selectedLecturerId)
            ->where('is_approved', false)
            ->with(['thesisDefense.thesis.student.user'])
            ->get();

        $stats = [
            'total_advisees' => $adviseeTheses->count(),
            'pending_logs' => $pendingLogs->count(),
            'proposal_exams' => $proposalExams->count(),
            'defense_exams' => $defenseExams->count(),
            'pending_revisions' => $pendingRevisions->count(),
        ];

        return view('dashboard.dosen', compact(
            'lecturers',
            'lecturer',
            'stats',
            'adviseeTheses',
            'pendingLogs',
            'proposalExams',
            'defenseExams',
            'pendingRevisions'
        ));
    }
}

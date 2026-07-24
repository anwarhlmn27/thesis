<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\MentoringLog;
use App\Models\Thesis;
use App\Models\ThesisAdvisor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MentoringController extends Controller
{
    public function index()
    {
        $studentId = Auth::user()->student->id;
        $thesis = Thesis::where('student_id', $studentId)->first();
        
        $logs = [];
        $advisors = collect();
        if ($thesis) {
            $logs = MentoringLog::where('thesis_id', $thesis->id)
                ->with('thesisAdvisor.lecturer.user')
                ->orderBy('mentoring_date', 'desc')
                ->get();
            $advisors = ThesisAdvisor::where('thesis_id', $thesis->id)->with('lecturer.user')->get();
        }

        return view('student.mentoring.index', compact('thesis', 'logs', 'advisors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'thesis_advisor_id' => 'required|exists:thesis_advisors,id',
            'mentoring_date' => 'required|date',
            'notes' => 'required|string',
            'document_file' => 'nullable|file|max:10240', // 10MB
        ]);

        $path = null;
        if ($request->hasFile('document_file')) {
            $path = $request->file('document_file')->store('mentoring_docs', 'public');
        }

        MentoringLog::create([
            'thesis_id' => $request->thesis_id,
            'thesis_advisor_id' => $request->thesis_advisor_id,
            'mentoring_date' => $request->mentoring_date,
            'notes' => $request->notes,
            'document_path' => $path,
            'status' => 'submitted'
        ]);

        return redirect()->back()->with('success', 'Log Bimbingan berhasil disubmit dan menunggu persetujuan dosen.');
    }
}

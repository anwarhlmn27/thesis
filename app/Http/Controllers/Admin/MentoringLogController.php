<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MentoringLog;
use App\Models\Thesis;
use App\Models\ThesisAdvisor;
use Illuminate\Http\Request;

class MentoringLogController extends Controller
{
    public function index()
    {
        $logs = MentoringLog::with(['thesis.student.user', 'thesisAdvisor.lecturer.user'])->orderBy('id', 'desc')->get();
        $theses = Thesis::with('student.user')->get();
        $advisors = ThesisAdvisor::with(['thesis.student.user', 'lecturer.user'])->get();
        return view('admin.mentoring_logs.index', compact('logs', 'theses', 'advisors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'thesis_advisor_id' => 'required|exists:thesis_advisors,id',
            'mentoring_date' => 'required|date',
            'notes' => 'required|string',
            'document_path' => 'nullable|string|max:255',
            'status' => 'required|in:submitted,approved,rejected',
            'feedback' => 'nullable|string',
        ]);

        MentoringLog::create($request->all());

        return redirect()->back()->with('success', 'Log Bimbingan berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $log = MentoringLog::findOrFail($id);

        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'thesis_advisor_id' => 'required|exists:thesis_advisors,id',
            'mentoring_date' => 'required|date',
            'notes' => 'required|string',
            'document_path' => 'nullable|string|max:255',
            'status' => 'required|in:submitted,approved,rejected',
            'feedback' => 'nullable|string',
        ]);

        $log->update($request->all());

        return redirect()->back()->with('success', 'Log Bimbingan berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $log = MentoringLog::findOrFail($id);
        $log->delete();

        return redirect()->back()->with('success', 'Log Bimbingan berhasil dihapus.');
    }
}

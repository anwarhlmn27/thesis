<?php

namespace App\Http\Controllers\Lecturer;

use App\Http\Controllers\Controller;
use App\Models\MentoringLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MentoringLogController extends Controller
{
    /**
     * Tampilkan log bimbingan untuk mahasiswa bimbingan dosen yang sedang login.
     */
    public function index()
    {
        $lecturerId = Auth::user()->lecturer->id;

        $logs = MentoringLog::whereHas('thesisAdvisor', function ($query) use ($lecturerId) {
            $query->where('lecturer_id', $lecturerId);
        })->with(['thesis.student.user', 'thesisAdvisor.lecturer.user'])->orderBy('mentoring_date', 'desc')->get();

        return view('dosen.mentoring_logs.index', compact('logs'));
    }

    /**
     * Update status & berikan feedback pada log bimbingan.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
            'feedback' => 'nullable|string',
        ]);

        $lecturerId = Auth::user()->lecturer->id;

        $log = MentoringLog::whereHas('thesisAdvisor', function ($query) use ($lecturerId) {
            $query->where('lecturer_id', $lecturerId);
        })->findOrFail($id);

        $log->update([
            'status' => $request->status,
            'feedback' => $request->feedback,
        ]);

        return redirect()->back()->with('success', 'Log bimbingan berhasil diupdate.');
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Thesis;
use App\Models\ThesisDefense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DefenseController extends Controller
{
    public function index()
    {
        $studentId = Auth::user()->student->id;
        $thesis = Thesis::where('student_id', $studentId)->first();
        
        $defense = null;
        if ($thesis) {
            $defense = ThesisDefense::where('thesis_id', $thesis->id)->with('defenseExaminers.lecturer.user')->first();
        }

        // Cek syarat sidang: log bimbingan >= 10 disetujui, bebas perpus, bebas UKT
        $eligibleForDefense = false;
        $approvedLogs = 0;
        if ($thesis) {
            $approvedLogs = $thesis->mentoringLogs()->where('status', 'approved')->count();
            $student = Auth::user()->student;
            if ($approvedLogs >= 10 && $student->is_paid && $student->is_library_clear) {
                $eligibleForDefense = true;
            }
        }

        return view('student.defense.index', compact('thesis', 'defense', 'eligibleForDefense', 'approvedLogs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'final_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $studentId = Auth::user()->student->id;
        $thesis = Thesis::where('student_id', $studentId)->firstOrFail();

        $path = $request->file('final_file')->store('theses/final', 'public');
        $thesis->update(['final_file_path' => $path]);

        // Daftar sidang (status pending menunggu BAAK)
        ThesisDefense::firstOrCreate(
            ['thesis_id' => $thesis->id],
            ['status' => 'pending']
        );

        return redirect()->back()->with('success', 'Berhasil mendaftar sidang. Silakan tunggu jadwal dari BAAK.');
    }
}

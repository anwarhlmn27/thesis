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
        $isAdvisorApproved = false;

        if ($thesis) {
            $approvedLogs = $thesis->mentoringLogs()->where('status', 'approved')->count();
            
            $isAdvisorApproved = \App\Models\ThesisAdvisor::where('thesis_id', $thesis->id)
                ->where('is_approved_for_defense', true)
                ->exists();

            $student = Auth::user()->student;
            if ($approvedLogs >= 10 && $isAdvisorApproved && $student->is_paid && $student->is_library_clear && $student->is_coursework_completed) {
                $eligibleForDefense = true;
            }
        }

        return view('student.defense.index', compact('thesis', 'defense', 'eligibleForDefense', 'approvedLogs', 'isAdvisorApproved'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'final_file' => 'required|file|mimes:pdf|max:10240',
        ]);

        $studentId = Auth::user()->student->id;
        $thesis = Thesis::where('student_id', $studentId)->firstOrFail();

        $path = $request->file('final_file')->store('theses/final', 'public');
        $thesis->update([
            'title' => $request->title,
            'final_file_path' => $path
        ]);

        // Daftar sidang (status registered menunggu jadwal BAAK)
        ThesisDefense::firstOrCreate(
            ['thesis_id' => $thesis->id],
            ['status' => 'registered']
        );

        return redirect()->back()->with('success', 'Berhasil mendaftar sidang. Silakan tunggu jadwal dari BAAK.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'final_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $studentId = Auth::user()->student->id;
        $thesis = Thesis::where('student_id', $studentId)->firstOrFail();
        
        $defense = ThesisDefense::where('thesis_id', $thesis->id)->firstOrFail();
        if ($defense->status !== 'registered') {
            return redirect()->back()->with('error', 'Gagal: Pendaftaran sidang tidak bisa diubah karena sudah dijadwalkan oleh BAAK.');
        }

        $dataToUpdate = ['title' => $request->title];

        if ($request->hasFile('final_file')) {
            $path = $request->file('final_file')->store('theses/final', 'public');
            $dataToUpdate['final_file_path'] = $path;
        }

        $thesis->update($dataToUpdate);

        return redirect()->back()->with('success', 'Pendaftaran sidang berhasil diubah.');
    }

    public function destroy()
    {
        $studentId = Auth::user()->student->id;
        $thesis = Thesis::where('student_id', $studentId)->firstOrFail();
        
        $defense = ThesisDefense::where('thesis_id', $thesis->id)->firstOrFail();
        
        if ($defense->status !== 'registered') {
            return redirect()->back()->with('error', 'Gagal: Pendaftaran sidang tidak bisa dibatalkan karena sudah diproses atau dijadwalkan oleh BAAK.');
        }

        $defense->delete();

        return redirect()->back()->with('success', 'Pendaftaran sidang berhasil dibatalkan.');
    }
}

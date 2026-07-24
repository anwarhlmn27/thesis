<?php

namespace App\Http\Controllers\Baak;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ThesisProposal;
use Illuminate\Http\Request;

class ClearanceController extends Controller
{
    public function index()
    {
        $students = Student::with('user')->get();
        $proposals = ThesisProposal::with('thesis.student.user')->orderBy('created_at', 'desc')->get();

        return view('baak.clearance.index', compact('students', 'proposals'));
    }

    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update([
            'is_coursework_completed' => $request->has('is_coursework_completed')
        ]);
        return redirect()->back()->with('success', 'Status kelayakan akademik (SKS) berhasil diperbarui.');
    }

    public function updateProposal(Request $request, $id)
    {
        $proposal = ThesisProposal::findOrFail($id);
        $proposal->update([
            'is_baak_approved' => $request->has('is_baak_approved'),
            'baak_approved_at' => $request->has('is_baak_approved') ? now() : null,
        ]);
        return redirect()->back()->with('success', 'Status validasi proposal (Akademik) berhasil diperbarui.');
    }
}

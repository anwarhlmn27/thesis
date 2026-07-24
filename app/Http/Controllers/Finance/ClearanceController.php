<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\ThesisProposal;
use Illuminate\Http\Request;

class ClearanceController extends Controller
{
    public function index()
    {
        // All students
        $students = Student::with('user')->get();
        
        // Proposals that need finance approval
        $proposals = ThesisProposal::with('thesis.student.user')->orderBy('created_at', 'desc')->get();

        return view('finance.clearance.index', compact('students', 'proposals'));
    }

    public function updateStudent(Request $request, $id)
    {
        $student = Student::findOrFail($id);
        $student->update([
            'is_paid' => $request->has('is_paid')
        ]);
        return redirect()->back()->with('success', 'Status keuangan UKT mahasiswa berhasil diperbarui.');
    }

    public function updateProposal(Request $request, $id)
    {
        $proposal = ThesisProposal::findOrFail($id);
        $proposal->update([
            'is_finance_approved' => $request->has('is_finance_approved'),
            'finance_approved_at' => $request->has('is_finance_approved') ? now() : null,
        ]);
        return redirect()->back()->with('success', 'Status keuangan proposal berhasil diperbarui.');
    }
}

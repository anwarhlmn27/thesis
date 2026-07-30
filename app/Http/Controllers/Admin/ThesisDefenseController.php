<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MentoringLog;
use App\Models\Student;
use App\Models\Thesis;
use App\Models\ThesisAdvisor;
use App\Models\ThesisDefense;
use Illuminate\Http\Request;

class ThesisDefenseController extends Controller
{
    public function index()
    {
        $defenses = ThesisDefense::with(['thesis.student.user', 'defenseExaminers.lecturer.user'])->orderBy('id', 'desc')->get();
        $theses = Thesis::with(['student.user', 'thesisAdvisors'])->orderBy('id', 'desc')->get();

        // Calculate mentoring count and eligibility for each thesis
        foreach ($theses as $thesis) {
            $approvedLogsCount = MentoringLog::where('thesis_id', $thesis->id)
                ->where('status', 'approved')
                ->count();

            $isAdvisorApproved = ThesisAdvisor::where('thesis_id', $thesis->id)
                ->where('is_approved_for_defense', true)
                ->exists();

            $student = $thesis->student;

            $thesis->approved_mentoring_count = $approvedLogsCount;
            $thesis->is_advisor_approved = $isAdvisorApproved;
            $thesis->is_eligible_for_defense = (
                $approvedLogsCount >= 10 &&
                $isAdvisorApproved &&
                optional($student)->is_paid &&
                optional($student)->is_coursework_completed &&
                optional($student)->is_library_clear
            );
        }

        return view('admin.thesis_defenses.index', compact('defenses', 'theses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'defense_date' => 'nullable|date',
            'room' => 'nullable|string|max:255',
            'status' => 'required|in:registered,scheduled,passed,failed',
            'score' => 'nullable|numeric|min:0|max:100',
            'grade' => 'nullable|string|max:5',
            'final_file_path' => 'nullable|string|max:255',
        ]);

        $thesis = Thesis::with('student')->find($request->thesis_id);
        if ($thesis) {
            $approvedLogsCount = MentoringLog::where('thesis_id', $thesis->id)->where('status', 'approved')->count();
            $isAdvisorApproved = ThesisAdvisor::where('thesis_id', $thesis->id)->where('is_approved_for_defense', true)->exists();
            $student = $thesis->student;

            $isEligible = (
                $approvedLogsCount >= 10 &&
                $isAdvisorApproved &&
                optional($student)->is_paid &&
                optional($student)->is_coursework_completed &&
                optional($student)->is_library_clear
            );

            if (!$isEligible && $request->status !== 'registered') {
                return redirect()->back()->with('error', 'Gagal: Mahasiswa belum eligible untuk sidang skripsi. Pastikan bimbingan min 10x, disetujui Pembimbing, serta bebas Finance, BAAK, dan Library.');
            }
        }

        $data = [
            'thesis_id' => $request->thesis_id,
            'defense_date' => $request->defense_date,
            'room' => $request->room,
            'status' => $request->status,
            'is_advisor_approved' => true,
        ];
        
        if ($request->has('final_file_path')) $data['final_file_path'] = $request->final_file_path;
        if ($request->has('score')) $data['score'] = $request->score;
        if ($request->has('grade')) $data['grade'] = $request->grade;

        ThesisDefense::create($data);

        if ($thesis) {
            $thesis->update(['status' => 'defense_scheduled']);
        }

        return redirect()->back()->with('success', 'Jadwal / Pendaftaran Sidang Skripsi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $defense = ThesisDefense::findOrFail($id);

        $request->validate([
            'thesis_id' => 'required|exists:theses,id',
            'defense_date' => 'nullable|date',
            'room' => 'nullable|string|max:255',
            'status' => 'required|in:registered,scheduled,passed,failed',
            'score' => 'nullable|numeric|min:0|max:100',
            'grade' => 'nullable|string|max:5',
            'final_file_path' => 'nullable|string|max:255',
        ]);

        $data = [
            'thesis_id' => $request->thesis_id,
            'defense_date' => $request->defense_date,
            'room' => $request->room,
            'status' => $request->status,
        ];
        
        if ($request->has('final_file_path')) $data['final_file_path'] = $request->final_file_path;
        if ($request->has('score')) $data['score'] = $request->score;
        if ($request->has('grade')) $data['grade'] = $request->grade;

        $defense->update($data);

        return redirect()->back()->with('success', 'Jadwal / Status Sidang Skripsi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $defense = ThesisDefense::findOrFail($id);
        $defense->delete();

        return redirect()->back()->with('success', 'Jadwal Sidang Skripsi berhasil dihapus.');
    }
}

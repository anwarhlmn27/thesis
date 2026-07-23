<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thesis extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'title',
        'abstract',
        'proposal_file_path',
        'final_file_path',
        'signed_revision_proof_path',
        'status',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function thesisAdvisors()
    {
        return $this->hasMany(ThesisAdvisor::class);
    }

    public function proposalSeminars()
    {
        return $this->hasMany(ProposalSeminar::class);
    }

    public function mentoringLogs()
    {
        return $this->hasMany(MentoringLog::class);
    }

    public function thesisDefenses()
    {
        return $this->hasMany(ThesisDefense::class);
    }

    public function yudisiums()
    {
        return $this->hasMany(Yudisium::class);
    }

    public function proposals()
    {
        return $this->hasMany(ThesisProposal::class);
    }

    public function latestProposal()
    {
        return $this->hasOne(ThesisProposal::class)->latestOfMany();
    }
}

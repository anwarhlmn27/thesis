<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nidn',
        'prodi',
        'is_kaprodi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function thesisAdvisors()
    {
        return $this->hasMany(ThesisAdvisor::class);
    }

    public function proposalExaminers()
    {
        return $this->hasMany(ProposalExaminer::class);
    }

    public function proposalComments()
    {
        return $this->hasMany(ProposalComment::class);
    }

    public function defenseExaminers()
    {
        return $this->hasMany(DefenseExaminer::class);
    }

    public function defenseRevisions()
    {
        return $this->hasMany(DefenseRevision::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'proposal_seminar_id',
        'lecturer_id',
        'comment',
    ];

    public function proposalSeminar()
    {
        return $this->belongsTo(ProposalSeminar::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalSeminar extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'seminar_date',
        'room',
        'status',
    ];

    protected $casts = [
        'seminar_date' => 'datetime',
    ];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    public function proposalExaminers()
    {
        return $this->hasMany(ProposalExaminer::class);
    }

    public function proposalComments()
    {
        return $this->hasMany(ProposalComment::class);
    }
}

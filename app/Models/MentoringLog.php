<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentoringLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'thesis_advisor_id',
        'mentoring_date',
        'notes',
        'document_path',
        'status',
        'feedback',
    ];

    protected $casts = [
        'mentoring_date' => 'date',
    ];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    public function thesisAdvisor()
    {
        return $this->belongsTo(ThesisAdvisor::class);
    }
}

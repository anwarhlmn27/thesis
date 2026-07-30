<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefenseExaminer extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_defense_id',
        'lecturer_id',
        'position',
        'score',
        'notes',
    ];

    protected $casts = [
        'score' => 'double',
    ];

    public function thesisDefense()
    {
        return $this->belongsTo(ThesisDefense::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }
}

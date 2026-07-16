<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisAdvisor extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'lecturer_id',
        'type',
        'is_approved_for_defense',
        'approved_at',
    ];

    protected $casts = [
        'is_approved_for_defense' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Lecturer::class);
    }

    public function mentoringLogs()
    {
        return $this->hasMany(MentoringLog::class);
    }
}

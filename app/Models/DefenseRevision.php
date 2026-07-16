<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefenseRevision extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_defense_id',
        'lecturer_id',
        'description',
        'is_approved',
        'approved_at',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
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

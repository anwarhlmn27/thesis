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
        'revision_file_path',
        'is_approved',
        'approved_at',
        'is_approved_by_examiner',
        'examiner_approved_at',
        'is_approved_by_kaprodi',
        'kaprodi_approved_at',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'is_approved_by_examiner' => 'boolean',
        'examiner_approved_at' => 'datetime',
        'is_approved_by_kaprodi' => 'boolean',
        'kaprodi_approved_at' => 'datetime',
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

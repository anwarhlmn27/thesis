<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisDefense extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'defense_date',
        'room',
        'status',
        'score',
        'grade',
    ];

    protected $casts = [
        'defense_date' => 'datetime',
        'score' => 'double',
    ];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
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

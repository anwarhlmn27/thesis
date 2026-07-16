<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nim',
        'prodi',
        'semester',
        'is_paid',
        'is_library_clear',
        'is_coursework_completed',
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'is_library_clear' => 'boolean',
        'is_coursework_completed' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function theses()
    {
        return $this->hasMany(Thesis::class);
    }

    public function yudisiums()
    {
        return $this->hasMany(Yudisium::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yudisium extends Model
{
    use HasFactory;

    protected $table = 'yudisiums';

    protected $fillable = [
        'sk_number',
        'academic_year',
        'sk_file_path',
        'graduation_date',
        'dekan_name',
        'dekan_nip',
        'status',
    ];

    protected $casts = [
        'graduation_date' => 'date',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'yudisium_students')
            ->withPivot('ipk', 'predicate')
            ->withTimestamps();
    }
}

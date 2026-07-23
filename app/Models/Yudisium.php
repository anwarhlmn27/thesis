<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yudisium extends Model
{
    use HasFactory;

    protected $table = 'yudisiums';

    protected $fillable = [
        'student_id',
        'thesis_id',
        'sk_number',
        'sk_file_path',
        'graduation_date',
        'dekan_name',
        'dekan_nip',
        'status',
    ];

    protected $casts = [
        'graduation_date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }
}

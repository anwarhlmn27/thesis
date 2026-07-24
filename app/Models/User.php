<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function lecturer()
    {
        return $this->hasOne(Lecturer::class);
    }

    public function getRoleAttribute()
    {
        // Check if admin
        if ($this->email === 'admin@example.com' || str_starts_with($this->email, 'admin')) {
            return 'admin';
        }

        // Check if staff (baak, finance, library)
        if ($this->staff) {
            return 'staff_' . strtolower($this->staff->department);
        }

        // Check if student
        if ($this->student) {
            return 'student';
        }

        // Check if lecturer
        if ($this->lecturer) {
            return 'lecturer';
        }

        return 'unknown';
    }
}

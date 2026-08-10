<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThesisProposal extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_id',
        'proposal_file_path',
        'submission_date',
        'is_baak_approved',
        'baak_approved_at',
        'baak_notes',
        'is_finance_approved',
        'finance_approved_at',
        'finance_notes',
        'is_kaprodi_approved',
        'kaprodi_approved_at',
        'kaprodi_notes',
        'eligibility_status',
    ];

    protected $casts = [
        'is_baak_approved' => 'boolean',
        'is_finance_approved' => 'boolean',
        'is_kaprodi_approved' => 'boolean',
        'submission_date' => 'datetime',
        'baak_approved_at' => 'datetime',
        'finance_approved_at' => 'datetime',
        'kaprodi_approved_at' => 'datetime',
    ];

    public function thesis()
    {
        return $this->belongsTo(Thesis::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($proposal) {
            if ($proposal->is_baak_approved && 
                $proposal->is_finance_approved && 
                $proposal->is_kaprodi_approved) {
                $proposal->eligibility_status = 'eligible';
            } else {
                $proposal->eligibility_status = 'pending';
            }
        });
    }

    public function checkAndUpdateEligibility()
    {
        if ($this->is_baak_approved && $this->is_finance_approved && $this->is_kaprodi_approved) {
            $this->eligibility_status = 'eligible';
        } else {
            $this->eligibility_status = 'pending';
        }
        $this->save();
    }
}

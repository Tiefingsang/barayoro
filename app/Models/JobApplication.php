<?php
// app/Models/JobApplication.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobApplication extends Model
{
    protected $table = 'job_applications';

    protected $fillable = [
        'uuid',
        'job_offer_id',
        'full_name',
        'email',
        'phone',
        'cover_letter',
        'cv_path',
        'expected_salary',
        'available_from',
        'status',
        'reviewer_notes',
        'reviewed_at',
        'reviewed_by',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expected_salary' => 'decimal:2',
        'available_from' => 'date',
        'reviewed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function jobOffer()
    {
        return $this->belongsTo(JobOffer::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeReviewed($query)
    {
        return $query->where('status', 'reviewed');
    }
}
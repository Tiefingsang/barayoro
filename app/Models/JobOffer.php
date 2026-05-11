<?php
// app/Models/JobOffer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobOffer extends Model
{
    use SoftDeletes;

    protected $table = 'job_offers';

    protected $fillable = [
        'uuid',
        'company_id',
        'title',
        'slug',
        'description',
        'requirements',
        'contract_type',
        'location',
        'salary_min',
        'salary_max',
        'experience_level',
        'is_urgent',
        'views',
        'expires_at',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_urgent' => 'boolean',
        'views' => 'integer',
        'salary_min' => 'decimal:2',
        'salary_max' => 'decimal:2',
        'expires_at' => 'datetime',
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }
}
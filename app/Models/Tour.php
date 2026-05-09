<?php
// app/Models/Tour.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tour extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'title', 'slug', 'description', 'category',
        'price', 'duration_days', 'start_date', 'end_date', 'max_participants',
        'location', 'image', 'is_active', 'status', 'views'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'views' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->title) . '-' . Str::random(6);
            }
        });
    }

    public function bookings()
    {
        return $this->hasMany(TourBooking::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('status', 'active');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now());
    }
}
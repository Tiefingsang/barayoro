<?php
// app/Models/TourBooking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TourBooking extends Model
{
    protected $fillable = [
        'uuid', 'tour_id', 'user_id', 'participants', 'special_requests',
        'booking_date', 'total_amount', 'status', 'payment_status',
        'payment_method', 'transaction_id', 'notes'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'booking_date' => 'date',
        'participants' => 'integer',
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

    public function tour()
    {
        return $this->belongsTo(Tour::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }
}
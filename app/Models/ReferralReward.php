<?php
// app/Models/ReferralReward.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReferralReward extends Model
{
    protected $table = 'referral_rewards';

    protected $fillable = [
        'uuid', 'user_id', 'referral_id', 'amount', 'type', 'status', 'description', 'claimed_at', 'expires_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'claimed_at' => 'datetime',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeClaimed($query)
    {
        return $query->where('status', 'claimed');
    }
}
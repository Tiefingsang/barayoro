<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CompanySubscription extends Model
{
    protected $fillable = [
        'uuid', 'company_id', 'plan', 'amount', 'currency',
        'started_at', 'expires_at', 'renewal_at', 'cancelled_at',
        'status', 'payment_method', 'transaction_id', 'invoice_id', 'metadata'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'renewal_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });

        static::created(function ($model) {
            $model->company->update([
                'subscription_status' => $model->status,
                'subscription_started_at' => $model->started_at,
                'subscription_expires_at' => $model->expires_at,
                'subscription_renewal_at' => $model->renewal_at,
                'subscription_price' => $model->amount,
                'subscription_invoice_id' => $model->invoice_id,
            ]);
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('expires_at', '>', now());
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expires_at->isFuture();
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function daysUntilExpiration(): int
    {
        return Carbon::now()->diffInDays($this->expires_at, false);
    }

    public function renew()
    {
        $this->update([
            'started_at' => now(),
            'expires_at' => now()->addYear(),
            'renewal_at' => now()->addYear(),
            'status' => 'active',
        ]);
    }

    public function cancel()
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);
    }
}

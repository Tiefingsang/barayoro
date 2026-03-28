<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'client_id', 'created_by',
        'invoice_number', 'issue_date', 'due_date', 'paid_date',
        'status', 'type',
        'subtotal', 'tax', 'discount', 'total', 'paid', 'balance',
        'currency', 'exchange_rate',
        'notes', 'terms', 'items', 'tax_details', 'metadata'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid' => 'decimal:2',
        'balance' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'items' => 'array',
        'tax_details' => 'array',
        'metadata' => 'array',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->invoice_number)) {
                $model->invoice_number = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
            $model->balance = $model->total - $model->paid;
        });

        static::updating(function ($model) {
            $model->balance = $model->total - $model->paid;
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeOverdue($query)
    {
        return $query->where('status', 'pending')
                     ->where('due_date', '<', now());
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isOverdue(): bool
    {
        return $this->status === 'pending' && $this->due_date < now();
    }

    public function recordPayment($amount)
    {
        $this->paid += $amount;
        $this->balance = $this->total - $this->paid;

        if ($this->balance <= 0) {
            $this->status = 'paid';
            $this->paid_date = now();
        }

        $this->save();
    }
}

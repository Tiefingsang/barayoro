<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'invoice_id', 'client_id', 'received_by', 'bank_account_id',
        'payment_number', 'payment_date', 'deposit_date', 'payment_time',
        'amount', 'fee_amount', 'tax_amount', 'net_amount', 'exchange_rate',
        'currency', 'received_currency',
        'method', 'reference', 'transaction_id', 'check_number',
        'card_last4', 'card_brand', 'mobile_number', 'mobile_operator',
        'bank_name', 'bank_account', 'bank_swift', 'sender_name', 'sender_account',
        'status', 'confirmation_status',
        'processed_at', 'confirmed_at', 'refunded_at', 'cancelled_at',
        'notes', 'rejection_reason', 'failure_reason',
        'receipt_path', 'proof_path', 'attachments',
        'metadata', 'payment_details', 'webhook_data',
        'ip_address', 'user_agent'
    ];

    protected $casts = [
        'payment_date' => 'date',
        'deposit_date' => 'date',
        'payment_time' => 'datetime',
        'amount' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'attachments' => 'array',
        'metadata' => 'array',
        'payment_details' => 'array',
        'webhook_data' => 'array',
        'processed_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'refunded_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->payment_number)) {
                $model->payment_number = 'PAY-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
            $model->net_amount = $model->amount - $model->fee_amount - $model->tax_amount;
        });

        static::created(function ($model) {
            if ($model->invoice && $model->status === 'completed') {
                $model->invoice->recordPayment($model->amount);
            }
        });
    }

    /**
     * Relations
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function bankAccount()
    {
        return $this->belongsTo(BankAccount::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('payment_date', [$startDate, $endDate]);
    }

    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    public function scopeByInvoice($query, $invoiceId)
    {
        return $query->where('invoice_id', $invoiceId);
    }

    /**
     * Methods
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
            'processed_at' => now(),
        ]);
    }

    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'confirmed_at' => now(),
        ]);
    }

    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
        ]);
    }

    public function markAsRefunded($reason = null)
    {
        $this->update([
            'status' => 'refunded',
            'refunded_at' => now(),
            'notes' => $reason,
        ]);
    }

    public function confirm($userId = null)
    {
        $this->update([
            'confirmation_status' => 'confirmed',
            'confirmed_at' => now(),
            'received_by' => $userId ?? auth()->id(),
        ]);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            'refunded' => 'orange',
            'cancelled' => 'gray',
            'on_hold' => 'purple',
            default => 'gray',
        };
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'En attente',
            'processing' => 'En traitement',
            'completed' => 'Complété',
            'failed' => 'Échoué',
            'refunded' => 'Remboursé',
            'cancelled' => 'Annulé',
            'on_hold' => 'En attente',
            default => ucfirst($this->status),
        };
    }

    public function getMethodIconAttribute()
    {
        $icons = [
            'cash' => '💵',
            'bank_transfer' => '🏦',
            'check' => '📝',
            'credit_card' => '💳',
            'debit_card' => '💳',
            'mobile_money' => '📱',
            'paypal' => '🅿️',
            'stripe' => '💳',
            'flutterwave' => '🌊',
            'orange_money' => '🟠',
            'wave' => '🌊',
        ];
        return $icons[$this->method] ?? '💰';
    }

    public function getAmountFormattedAttribute()
    {
        return number_format($this->amount, 2, ',', ' ') . ' ' . $this->currency;
    }
}

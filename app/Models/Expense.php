<?php
// app/Models/Expense.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'expense_category_id', 'project_id', 'created_by',
        'approved_by', 'paid_by', 'vendor_id',
        'expense_number', 'expense_date', 'due_date', 'paid_date',
        'amount', 'tax_amount', 'discount_amount', 'total_amount',
        'currency', 'exchange_rate',
        'title', 'description', 'notes',
        'status', 'payment_method', 'recurrence',
        'payment_reference', 'transaction_id', 'bank_account', 'check_number',
        'receipt_path', 'invoice_path', 'attachments',
        'rejection_reason', 'approved_at', 'rejected_at', 'paid_at',
        'metadata', 'tax_details'
    ];

    protected $casts = [
        'expense_date' => 'date',
        'due_date' => 'date',
        'paid_date' => 'date',
        'amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'attachments' => 'array',
        'metadata' => 'array',
        'tax_details' => 'array',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'paid_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->expense_number)) {
                $model->expense_number = 'EXP-' . date('Ymd') . '-' . strtoupper(Str::random(6));
            }
        });
    }

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function vendor()
    {
        return $this->belongsTo(Client::class, 'vendor_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }

    // Methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function approve()
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
    }

    public function reject($reason)
    {
        $this->update([
            'status' => 'rejected',
            'rejection_reason' => $reason,
            'rejected_at' => now(),
        ]);
    }

    public function markAsPaid($reference = null, $paidDate = null)
    {
        $this->update([
            'status' => 'paid',
            'paid_by' => auth()->id(),
            'paid_date' => $paidDate ?? now(),
            'payment_reference' => $reference,
            'paid_at' => now(),
        ]);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'blue',
            'paid' => 'green',
            'rejected' => 'red',
            default => 'gray',
        };
    }

    public function getAmountFormattedAttribute()
    {
        return number_format($this->amount, 2, ',', ' ') . ' FCFA';
    }

    public function getTotalAmountFormattedAttribute()
    {
        return number_format($this->total_amount, 2, ',', ' ') . ' FCFA';
    }
}

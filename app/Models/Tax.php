<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Tax extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'name', 'code', 'rate',
        'type', 'is_compound', 'is_active', 'description'
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'is_compound' => 'boolean',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
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

    public function invoiceItems()
    {
        return $this->belongsToMany(InvoiceItem::class, 'invoice_item_taxes')
                    ->withPivot('rate', 'amount')
                    ->withTimestamps();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function calculateTax($amount)
    {
        return $amount * ($this->rate / 100);
    }

    public function getRateFormattedAttribute()
    {
        return $this->rate . '%';
    }
}

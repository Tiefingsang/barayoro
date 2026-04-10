<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InvoiceItem extends Model
{
    protected $table = 'invoice_items';

    protected $fillable = [
        'uuid', 'invoice_id', 'product_id', 'description',
        'quantity', 'unit_price', 'subtotal', 'discount',
        'discount_amount', 'tax_rate', 'tax_amount', 'total',
        'metadata', 'code', 'notes', 'unit', 'discount_percentage',
        'discount_type', 'is_taxable', 'sort_order', 'custom_fields'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'metadata' => 'array',
        'custom_fields' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            // Calculer le subtotal si non fourni
            if (empty($model->subtotal)) {
                $model->subtotal = $model->quantity * $model->unit_price;
            }
            // Calculer la taxe si non fournie
            if (empty($model->tax_amount)) {
                $model->tax_amount = ($model->subtotal - ($model->discount_amount ?? 0)) * (($model->tax_rate ?? 0) / 100);
            }
            // Calculer le total si non fourni
            if (empty($model->total)) {
                $model->total = $model->subtotal - ($model->discount_amount ?? 0) + $model->tax_amount;
            }
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

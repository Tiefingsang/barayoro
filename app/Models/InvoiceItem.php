<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InvoiceItem extends Model
{
    protected $fillable = [
        'uuid', 'invoice_id', 'product_id', 'description',
        'quantity', 'unit_price', 'discount', 'tax_rate',
        'tax_amount', 'total', 'metadata'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            $model->tax_amount = ($model->unit_price * $model->quantity - $model->discount) * ($model->tax_rate / 100);
            $model->total = ($model->unit_price * $model->quantity) - $model->discount + $model->tax_amount;
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

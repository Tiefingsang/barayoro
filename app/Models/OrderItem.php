<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    protected $fillable = [
        'uuid', 'order_id', 'product_id', 'product_name', 'product_sku',
        'product_description', 'quantity', 'unit_price', 'tax_rate',
        'tax_amount', 'discount_amount', 'total', 'delivered_quantity', 'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'delivered_quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            $model->total = ($model->quantity * $model->unit_price) - $model->discount_amount;
            $model->tax_amount = ($model->quantity * $model->unit_price) * ($model->tax_rate / 100);
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getRemainingQuantityAttribute()
    {
        return $this->quantity - $this->delivered_quantity;
    }

    public function isFullyDelivered()
    {
        return $this->delivered_quantity >= $this->quantity;
    }
}

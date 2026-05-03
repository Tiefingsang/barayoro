<?php
// app/Models/PricingPlan.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    protected $fillable = [
        'name', 'subtitle', 'price', 'period', 'features', 'button_text', 
        'button_url', 'icon', 'is_popular', 'sort_order', 'is_active'
    ];
    
    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];
}
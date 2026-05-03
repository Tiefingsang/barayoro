<?php
// app/Models/HelpArticle.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpArticle extends Model
{
    protected $fillable = [
        'title', 'slug', 'content', 'category_id', 'views', 
        'is_popular', 'is_active', 'sort_order'
    ];
    
    protected $casts = [
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
        'views' => 'integer',
        'sort_order' => 'integer',
    ];
    
    public function category()
    {
        return $this->belongsTo(HelpCategory::class, 'category_id');
    }
}
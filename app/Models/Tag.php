<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    protected $fillable = [
        'uuid', 'company_id', 'name', 'slug', 'color',
        'description', 'icon', 'is_active', 'usage_count', 'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'usage_count' => 'integer',
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function taggables()
    {
        return $this->hasMany(Taggable::class);
    }

    public function projects()
    {
        return $this->morphedByMany(Project::class, 'taggable');
    }

    public function tasks()
    {
        return $this->morphedByMany(Task::class, 'taggable');
    }

    public function clients()
    {
        return $this->morphedByMany(Client::class, 'taggable');
    }

    public function products()
    {
        return $this->morphedByMany(Product::class, 'taggable');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function incrementUsage()
    {
        $this->increment('usage_count');
    }

    public function decrementUsage()
    {
        $this->decrement('usage_count');
    }
}

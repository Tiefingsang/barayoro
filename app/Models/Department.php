<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'name', 'code', 'description', 'manager_id',
        'is_active', 'settings', 'sync_status',
        'synced_at',
        'local_updated_at',
        ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
        'deleted_at' => 'datetime',
        'synced_at' => 'datetime',
    'local_updated_at' => 'datetime',

    ];

   protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->sync_status)) {
                $model->sync_status = 'synced';
            }
        });

        static::updating(function ($model) {
            if ($model->isDirty() && $model->sync_status !== 'pending') {
                $model->sync_status = 'pending';
                $model->local_updated_at = now();
            }
        });
    }
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

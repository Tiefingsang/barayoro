<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ActivityLog extends Model
{
    protected $fillable = [
        'uuid', 'company_id', 'user_id', 'action', 'entity_type',
        'entity_id', 'entity_uuid', 'old_values', 'new_values',
        'changes', 'ip_address', 'user_agent', 'device', 'location', 'metadata'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'changes' => 'array',
        'metadata' => 'array',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeForEntity($query, $entityType, $entityId)
    {
        return $query->where('entity_type', $entityType)
                     ->where('entity_id', $entityId);
    }

    public function scopeOfAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public static function record($action, Model $entity, $oldValues = null, $newValues = null)
    {
        $changes = null;
        if ($oldValues && $newValues) {
            $changes = array_diff_assoc($newValues, $oldValues);
        }

        return self::create([
            'company_id' => auth()->user()?->company_id ?? $entity->company_id,
            'user_id' => auth()->id(),
            'action' => $action,
            'entity_type' => get_class($entity),
            'entity_id' => $entity->id,
            'entity_uuid' => $entity->uuid ?? null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'changes' => $changes,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'metadata' => [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
            ],
        ]);
    }
}

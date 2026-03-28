<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class AuditTrail extends Model
{
    protected $fillable = [
        'uuid', 'company_id', 'user_id', 'action', 'resource_type',
        'resource_id', 'resource_uuid', 'old_data', 'new_data',
        'changes', 'ip_address', 'user_agent', 'device', 'location', 'metadata'
    ];

    protected $casts = [
        'old_data' => 'array',
        'new_data' => 'array',
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

    public function scopeForResource($query, $resourceType, $resourceId)
    {
        return $query->where('resource_type', $resourceType)
                     ->where('resource_id', $resourceId);
    }

    public function scopeOfAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public static function record($action, Model $resource, $oldData = null, $newData = null)
    {
        $changes = null;
        if ($oldData && $newData) {
            $changes = array_diff_assoc($newData, $oldData);
        }

        return self::create([
            'company_id' => auth()->user()?->company_id ?? $resource->company_id,
            'user_id' => auth()->id(),
            'action' => $action,
            'resource_type' => get_class($resource),
            'resource_id' => $resource->id,
            'resource_uuid' => $resource->uuid ?? null,
            'old_data' => $oldData,
            'new_data' => $newData,
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

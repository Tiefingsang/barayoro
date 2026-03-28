<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SyncQueue extends Model
{
    protected $table = 'sync_queue';

    protected $fillable = [
        'uuid', 'company_id', 'user_id',
        'entity_type', 'entity_uuid', 'entity_id',
        'operation', 'direction',
        'data', 'old_data', 'changes', 'conflict_data',
        'status', 'attempts', 'max_attempts', 'next_attempt_at',
        'error_message', 'error_trace',
        'priority', 'priority_level',
        'queued_at', 'started_at', 'processed_at', 'completed_at',
        'batch_id', 'correlation_id', 'metadata', 'response',
        'client_version', 'device_id', 'ip_address'
    ];

    protected $casts = [
        'data' => 'array',
        'old_data' => 'array',
        'changes' => 'array',
        'conflict_data' => 'array',
        'attempts' => 'integer',
        'max_attempts' => 'integer',
        'priority' => 'integer',
        'metadata' => 'array',
        'response' => 'array',
        'queued_at' => 'datetime',
        'started_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
        'next_attempt_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->queued_at)) {
                $model->queued_at = now();
            }
            if (empty($model->status)) {
                $model->status = 'pending';
            }
            if (empty($model->attempts)) {
                $model->attempts = 0;
            }
            if (empty($model->max_attempts)) {
                $model->max_attempts = 5;
            }
        });
    }

    /**
     * Relations
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending')
                     ->where(function($q) {
                         $q->whereNull('next_attempt_at')
                           ->orWhere('next_attempt_at', '<=', now());
                     });
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeByEntity($query, $entityType, $entityUuid)
    {
        return $query->where('entity_type', $entityType)
                     ->where('entity_uuid', $entityUuid);
    }

    public function scopeByBatch($query, $batchId)
    {
        return $query->where('batch_id', $batchId);
    }

    public function scopeHighPriority($query)
    {
        return $query->orderBy('priority', 'desc')
                     ->orderBy('queued_at', 'asc');
    }

    /**
     * Methods
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isConflict(): bool
    {
        return $this->status === 'conflict';
    }

    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    public function markAsCompleted($response = null)
    {
        $this->update([
            'status' => 'completed',
            'response' => $response,
            'completed_at' => now(),
        ]);
    }

    public function markAsFailed($error, $trace = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
            'error_trace' => $trace,
            'attempts' => $this->attempts + 1,
        ]);

        if ($this->attempts < $this->max_attempts) {
            $this->scheduleRetry();
        }
    }

    public function markAsConflict($conflictData = null)
    {
        $this->update([
            'status' => 'conflict',
            'conflict_data' => $conflictData,
        ]);
    }

    public function scheduleRetry($delayMinutes = 5)
    {
        $this->update([
            'status' => 'pending',
            'next_attempt_at' => now()->addMinutes($delayMinutes * $this->attempts),
        ]);
    }

    public function canRetry(): bool
    {
        return $this->attempts < $this->max_attempts;
    }

    public function retry()
    {
        if ($this->canRetry()) {
            $this->update([
                'status' => 'pending',
                'error_message' => null,
                'error_trace' => null,
                'next_attempt_at' => null,
            ]);
            return true;
        }
        return false;
    }
}

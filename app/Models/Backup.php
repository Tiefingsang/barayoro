<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class Backup extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'created_by', 'restored_by',
        'name', 'description', 'type',
        'filename', 'path', 'disk', 'mime_type', 'size', 'hash', 'checksum',
        'is_compressed', 'compression_type', 'compression_level',
        'is_encrypted', 'encryption_method',
        'tables', 'directories', 'excluded_tables', 'excluded_directories',
        'status', 'retention_policy',
        'started_at', 'completed_at', 'expires_at', 'restored_at',
        'duration_seconds', 'memory_usage', 'cpu_usage',
        'error_message', 'error_details', 'summary', 'logs',
        'notify_on_success', 'notify_on_failure', 'notification_recipients',
        'is_scheduled', 'schedule_cron', 'last_run_at', 'next_run_at',
        'metadata', 'environment',
        'app_version', 'database_version', 'php_version'
    ];

    protected $casts = [
        'size' => 'integer',
        'is_compressed' => 'boolean',
        'compression_level' => 'integer',
        'is_encrypted' => 'boolean',
        'tables' => 'array',
        'directories' => 'array',
        'excluded_tables' => 'array',
        'excluded_directories' => 'array',
        'duration_seconds' => 'integer',
        'memory_usage' => 'integer',
        'cpu_usage' => 'integer',
        'error_details' => 'array',
        'summary' => 'array',
        'logs' => 'array',
        'notify_on_success' => 'boolean',
        'notify_on_failure' => 'boolean',
        'notification_recipients' => 'array',
        'is_scheduled' => 'boolean',
        'metadata' => 'array',
        'environment' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
        'restored_at' => 'datetime',
        'last_run_at' => 'datetime',
        'next_run_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->name)) {
                $model->name = 'backup_' . date('Y-m-d_H-i-s');
            }
            if (empty($model->filename)) {
                $extension = $model->is_compressed ? ($model->compression_type === 'zip' ? 'zip' : 'tar.gz') : 'sql';
                $model->filename = $model->name . '.' . $extension;
            }
        });

        static::deleting(function ($model) {
            if ($model->isForceDeleting() && $model->path && Storage::disk($model->disk)->exists($model->path)) {
                Storage::disk($model->disk)->delete($model->path);
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function restorer()
    {
        return $this->belongsTo(User::class, 'restored_by');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeScheduled($query)
    {
        return $query->where('is_scheduled', true);
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeToKeep($query)
    {
        return $query->where('expires_at', '>', now())
                     ->orWhere('retention_policy', 'forever');
    }

    /**
     * Accessors
     */
    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getSizeFormattedAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->size;
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }

    public function getDurationFormattedAttribute()
    {
        if (!$this->duration_seconds) {
            return '-';
        }
        $hours = floor($this->duration_seconds / 3600);
        $minutes = floor(($this->duration_seconds % 3600) / 60);
        $seconds = $this->duration_seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'processing' => 'blue',
            'completed' => 'green',
            'failed' => 'red',
            'partial' => 'orange',
            'cancelled' => 'gray',
            default => 'gray',
        };
    }

    /**
     * Methods
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function markAsProcessing()
    {
        $this->update([
            'status' => 'processing',
            'started_at' => now(),
        ]);
    }

    public function markAsCompleted($summary = null)
    {
        $this->update([
            'status' => 'completed',
            'summary' => $summary,
            'completed_at' => now(),
            'duration_seconds' => $this->started_at ? $this->started_at->diffInSeconds(now()) : null,
        ]);
    }

    public function markAsFailed($error, $details = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $error,
            'error_details' => $details,
            'duration_seconds' => $this->started_at ? $this->started_at->diffInSeconds(now()) : null,
        ]);
    }

    public function deleteFile()
    {
        if ($this->path && Storage::disk($this->disk)->exists($this->path)) {
            return Storage::disk($this->disk)->delete($this->path);
        }
        return false;
    }

    public function restore($userId = null)
    {
        $this->update([
            'restored_by' => $userId ?? auth()->id(),
            'restored_at' => now(),
        ]);
    }

    public function getFileContent()
    {
        if ($this->path && Storage::disk($this->disk)->exists($this->path)) {
            return Storage::disk($this->disk)->get($this->path);
        }
        return null;
    }

    public function download()
    {
        if ($this->path && Storage::disk($this->disk)->exists($this->path)) {
            return Storage::disk($this->disk)->download($this->path, $this->filename);
        }
        return null;
    }
}

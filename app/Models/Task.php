<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\HasComments;

class Task extends Model
{
    use SoftDeletes, HasComments; 

    protected $fillable = [
        'uuid', 'company_id', 'project_id', 'department_id',
        'assigned_to', 'created_by', 'parent_task_id',
        'code', 'title', 'description', 'status', 'priority',
        'start_date', 'due_date', 'completed_at',
        'estimated_hours', 'actual_hours', 'progress',
        'sync_status', 'pending_changes', 'synced_at', 'local_updated_at',
        'attachments', 'metadata'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_at' => 'date',
        'estimated_hours' => 'integer',
        'actual_hours' => 'integer',
        'progress' => 'integer',
        'sync_status' => 'string',
        'pending_changes' => 'array',
        'synced_at' => 'datetime',
        'local_updated_at' => 'datetime',
        'attachments' => 'array',
        'metadata' => 'array',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->code)) {
                $model->code = 'TASK-' . strtoupper(Str::random(8));
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

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_task_id');
    }

    public function children()
    {
        return $this->hasMany(Task::class, 'parent_task_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'task_user')
                    ->withPivot('role', 'assigned_at', 'completed_at', 'spent_hours')
                    ->withTimestamps();
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeNeedSync($query)
    {
        return $query->where('sync_status', '!=', 'synced');
    }

    // Methods
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'progress' => 100,
            'sync_status' => 'pending',
        ]);
    }

    public function markAsInProgress()
    {
        $this->update([
            'status' => 'in_progress',
            'completed_at' => null,
            'sync_status' => 'pending',
        ]);
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'in_progress' => 'blue',
            'review' => 'purple',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'low' => 'gray',
            'medium' => 'blue',
            'high' => 'orange',
            'urgent' => 'red',
            default => 'gray',
        };
    }
}

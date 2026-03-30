<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\HasComments;


class Project extends Model
{
    use SoftDeletes, HasComments;

    protected $fillable = [
        'uuid', 'company_id', 'department_id', 'client_id', 'project_manager_id',
        'code', 'name', 'description', 'status', 'priority',
        'start_date', 'due_date', 'completed_at',
        'budget', 'actual_cost', 'progress', 'tags', 'metadata'
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date' => 'date',
        'completed_at' => 'date',
        'budget' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'progress' => 'integer',
        'tags' => 'array',
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
                $model->code = 'PROJ-' . strtoupper(Str::random(8));
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'project_manager_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_user')
                    ->withPivot('role', 'hourly_rate', 'allocated_hours', 'assigned_at')
                    ->withTimestamps();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    /* public function expenses()
    {
        return $this->hasMany(Expense::class);
    } */

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
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['planned', 'in_progress']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Methods
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isOverdue(): bool
    {
        return $this->due_date && $this->due_date->isPast() && !$this->isCompleted();
    }

    public function updateProgress()
    {
        $totalTasks = $this->tasks()->count();
        if ($totalTasks === 0) {
            $this->progress = 0;
        } else {
            $completedTasks = $this->tasks()->where('status', 'completed')->count();
            $this->progress = round(($completedTasks / $totalTasks) * 100);
        }
        $this->saveQuietly();
    }
}

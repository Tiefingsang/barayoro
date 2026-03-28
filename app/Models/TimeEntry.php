<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TimeEntry extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'user_id', 'task_id', 'project_id',
        'start_time', 'end_time', 'duration_minutes',
        'description', 'status', 'is_billable',
        'hourly_rate', 'amount', 'metadata'
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_minutes' => 'integer',
        'is_billable' => 'boolean',
        'hourly_rate' => 'decimal:2',
        'amount' => 'decimal:2',
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
        });

        static::saving(function ($model) {
            if ($model->start_time && $model->end_time) {
                $start = Carbon::parse($model->start_time);
                $end = Carbon::parse($model->end_time);
                $model->duration_minutes = $start->diffInMinutes($end);

                if ($model->hourly_rate) {
                    $model->amount = ($model->hourly_rate / 60) * $model->duration_minutes;
                }
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

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function scopeRunning($query)
    {
        return $query->where('status', 'running');
    }

    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    public function isRunning(): bool
    {
        return $this->status === 'running';
    }

    public function stop()
    {
        $this->update([
            'end_time' => now(),
            'status' => 'stopped'
        ]);
    }

    public function getDurationFormattedAttribute()
    {
        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;
        return sprintf('%02d:%02d', $hours, $minutes);
    }
}

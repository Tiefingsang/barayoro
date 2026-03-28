<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'department_id', 'manager_id',
        'name', 'email', 'avatar', 'phone', 'position', 'employee_id',
        'hire_date', 'employment_type', 'hourly_rate',
        'password', 'preferences', 'offline_data',
        'timezone', 'language', 'theme',
        'is_active', 'last_login_at', 'last_activity_at', 'last_sync_at',
        'last_ip', 'last_user_agent',
        'password_changed_at', 'two_factor_enabled', 'two_factor_secret',
        'two_factor_recovery_codes'
    ];

    protected $hidden = [
        'password', 'remember_token', 'two_factor_secret', 'two_factor_recovery_codes'
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'hire_date' => 'date',
        'hourly_rate' => 'decimal:2',
        'preferences' => 'array',
        'offline_data' => 'array',
        'last_login_at' => 'datetime',
        'last_activity_at' => 'datetime',
        'last_sync_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'is_active' => 'boolean',
        'two_factor_enabled' => 'boolean',
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
    }

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_user')
                    ->withPivot('role', 'joined_at', 'left_at')
                    ->withTimestamps();
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_user')
                    ->withPivot('role', 'hourly_rate', 'allocated_hours', 'assigned_at')
                    ->withTimestamps();
    }

    public function tasks()
    {
        return $this->belongsToMany(Task::class, 'task_user')
                    ->withPivot('role', 'assigned_at', 'completed_at', 'spent_hours')
                    ->withTimestamps();
    }

    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function projectsManaged()
    {
        return $this->hasMany(Project::class, 'project_manager_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function approvedLeaves()
    {
        return $this->hasMany(Leave::class, 'approved_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'received_by');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'created_by');
    }

    public function approvedExpenses()
    {
        return $this->hasMany(Expense::class, 'approved_by');
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function auditTrails()
    {
        return $this->hasMany(AuditTrail::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function apiTokens()
    {
        return $this->hasMany(ApiToken::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCompany($query, $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    // Accessors
    public function getAvatarUrlAttribute()
    {
        return $this->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    public function getFullNameAttribute()
    {
        return $this->name;
    }

    public function getMainRoleAttribute()
    {
        return $this->roles->first()->name ?? 'user';
    }

    // Methods
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isManager(): bool
    {
        return $this->hasRole('manager');
    }

    public function updateLastActivity()
    {
        $this->update(['last_activity_at' => now()]);
    }

    public function recordLogin()
    {
        $this->update([
            'last_login_at' => now(),
            'last_ip' => request()->ip(),
            'last_user_agent' => request()->userAgent(),
        ]);
    }

    public function canAccessCompany(Company $company): bool
    {
        return $this->company_id === $company->id || $this->isAdmin();
    }

    public function canManageUser(User $user): bool
    {
        return $this->company_id === $user->company_id && ($this->isAdmin() || $this->id === $user->manager_id);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'name', 'slug', 'email', 'phone', 'logo', 'address',
        'city', 'country', 'postal_code', 'tax_number', 'registration_number',
        'subscription_status', 'subscription_started_at', 'subscription_expires_at',
        'subscription_renewal_at', 'subscription_price', 'subscription_invoice_id',
        'max_users', 'max_storage_mb', 'unlimited_users',
        'is_active', 'is_trial', 'trial_ends_at',
        'settings', 'offline_settings'
    ];

    protected $casts = [
        'subscription_started_at' => 'datetime',
        'subscription_expires_at' => 'datetime',
        'subscription_renewal_at' => 'datetime',
        'trial_ends_at' => 'datetime',
        'is_active' => 'boolean',
        'is_trial' => 'boolean',
        'unlimited_users' => 'boolean',
        'settings' => 'array',
        'offline_settings' => 'array',
        'deleted_at' => 'datetime',
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

    // Relations
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function expenseCategories()
    {
        return $this->hasMany(ExpenseCategory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(TimeEntry::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function holidays()
    {
        return $this->hasMany(Holiday::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(CompanySubscription::class);
    }

    public function currentSubscription()
    {
        return $this->hasOne(CompanySubscription::class)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function auditTrails()
    {
        return $this->hasMany(AuditTrail::class);
    }

    public function backups()
    {
        return $this->hasMany(Backup::class);
    }

    public function settings()
    {
        return $this->hasMany(Setting::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeSubscriptionActive($query)
    {
        return $query->where('subscription_status', 'active')
            ->where('subscription_expires_at', '>', now());
    }

    // Methods
    public function isSubscriptionValid(): bool
    {
        return $this->subscription_status === 'active' &&
               $this->subscription_expires_at &&
               $this->subscription_expires_at->isFuture();
    }

    public function hasReachedUserLimit(): bool
    {
        if ($this->unlimited_users) {
            return false;
        }
        return $this->users()->count() >= $this->max_users;
    }

    public function getStorageUsage(): int
    {
        return $this->attachments()->sum('size') / 1024 / 1024; // MB
    }

    public function canUploadFile($fileSizeMB): bool
    {
        return ($this->getStorageUsage() + $fileSizeMB) <= $this->max_storage_mb;
    }
}

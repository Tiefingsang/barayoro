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
        'settings', 'offline_settings','subscription_ends_at','last_payment_date','next_payment_date', 'sync_status',
        'synced_at',
        'local_updated_at',
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
        'subscription_ends_at' => 'datetime',

        'last_payment_date' => 'date',
        'next_payment_date' => 'date',
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
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
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


    /**
     * Vérifier si l'entreprise est en période d'essai
     */
    public function isOnTrial(): bool
    {
        return $this->subscription_status === 'trial' &&
            $this->trial_ends_at &&
            $this->trial_ends_at->isFuture();
    }

    /**
     * Vérifier si l'abonnement est actif
     */
    public function isSubscribed(): bool
    {
        return $this->subscription_status === 'active' &&
            $this->subscription_ends_at &&
            $this->subscription_ends_at->isFuture();
    }

    /**
     * Vérifier si l'abonnement est expiré
     */
    public function isExpired(): bool
    {
        return $this->subscription_status === 'expired' ||
            ($this->subscription_ends_at && $this->subscription_ends_at->isPast());
    }

    /**
     * Vérifier si l'entreprise peut accéder à la plateforme
     */
    public function canAccess(): bool
    {
        return $this->isOnTrial() || $this->isSubscribed();
    }

    /**
     * Obtenir les jours restants d'essai
     */
    public function getTrialDaysRemaining(): int
    {
        if (!$this->trial_ends_at) {
            return 0;
        }
        return max(0, now()->diffInDays($this->trial_ends_at, false));
    }

    /**
     * Obtenir les jours restants d'abonnement
     */
    public function getSubscriptionDaysRemaining(): int
    {
        if (!$this->subscription_ends_at) {
            return 0;
        }
        return max(0, now()->diffInDays($this->subscription_ends_at, false));
    }

    /**
     * Démarrer la période d'essai
     */
    public function startTrial(int $days = 30): void
    {
        $this->update([
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays($days),
            'subscription_ends_at' => null,
            'subscription_started_at' => now(),
        ]);
    }

    /**
     * Activer l'abonnement annuel
     */
    public function activateSubscription(float $amount = 49000): void
    {
        $this->update([
            'subscription_status' => 'active',
            'subscription_started_at' => now(),
            'subscription_ends_at' => now()->addYear(),
            'subscription_price' => $amount,
            'last_payment_date' => now(),
            'next_payment_date' => now()->addYear(),
            'trial_ends_at' => null,
        ]);
    }

    /**
     * Renouveler l'abonnement
     */
    public function renewSubscription(): void
    {
        $this->update([
            'subscription_status' => 'active',
            'subscription_ends_at' => $this->subscription_ends_at ? $this->subscription_ends_at->addYear() : now()->addYear(),
            'last_payment_date' => now(),
            'next_payment_date' => $this->next_payment_date ? $this->next_payment_date->addYear() : now()->addYear(),
        ]);
    }

    /**
     * Expirer l'abonnement
     */
    public function expireSubscription(): void
    {
        $this->update([
            'subscription_status' => 'expired',
        ]);
    }

    /**
     * Annuler l'abonnement
     */
    public function cancelSubscription(): void
    {
        $this->update([
            'subscription_status' => 'cancelled',
        ]);
    }




}

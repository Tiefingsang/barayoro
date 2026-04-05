<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Traits\HasComments;

class Client extends Model
{
    use SoftDeletes, HasComments;

    protected $fillable = [
        'uuid', 'company_id', 'code', 'name', 'email', 'phone', 'mobile',
        'website', 'contact_person', 'contact_email', 'contact_phone',
        'address', 'city', 'country', 'postal_code',
        'tax_number', 'vat_number', 'status', 'notes', 'settings', 'metadata', 'sync_status',
        'synced_at',
        'local_updated_at',
        ];

    protected $casts = [
        'status' => 'string',
        'settings' => 'array',
        'metadata' => 'array',
        'deleted_at' => 'datetime',
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
            if (empty($model->code)) {
                $model->code = 'CLI-' . strtoupper(Str::random(8));
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

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
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

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getFullAddressAttribute()
    {
        $parts = [$this->address, $this->city, $this->postal_code, $this->country];
        return implode(', ', array_filter($parts));
    }
}

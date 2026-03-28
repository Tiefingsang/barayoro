<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Setting extends Model
{
    protected $fillable = [
        'uuid', 'company_id', 'group', 'key', 'value',
        'type', 'is_public', 'is_editable', 'metadata'
    ];

    protected $casts = [
        'value' => 'string',
        'is_public' => 'boolean',
        'is_editable' => 'boolean',
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

    public function scopeGroup($query, $group)
    {
        return $query->where('group', $group);
    }

    public function getTypedValueAttribute()
    {
        return match($this->type) {
            'boolean' => (bool) $this->value,
            'integer' => (int) $this->value,
            'float' => (float) $this->value,
            'array' => json_decode($this->value, true),
            default => $this->value,
        };
    }

    public function setTypedValueAttribute($value)
    {
        $this->value = match($this->type) {
            'array' => json_encode($value),
            default => (string) $value,
        };
    }

    public static function getValue($key, $default = null, $companyId = null)
    {
        $companyId = $companyId ?? auth()->user()?->company_id;

        $setting = self::where('company_id', $companyId)
                       ->where('key', $key)
                       ->first();

        return $setting ? $setting->typed_value : $default;
    }

    public static function setValue($key, $value, $companyId = null, $group = 'general')
    {
        $companyId = $companyId ?? auth()->user()?->company_id;

        $type = match(true) {
            is_bool($value) => 'boolean',
            is_int($value) => 'integer',
            is_float($value) => 'float',
            is_array($value) => 'array',
            default => 'string',
        };

        return self::updateOrCreate(
            ['company_id' => $companyId, 'key' => $key],
            [
                'group' => $group,
                'value' => $type === 'array' ? json_encode($value) : (string) $value,
                'type' => $type,
            ]
        );
    }
}

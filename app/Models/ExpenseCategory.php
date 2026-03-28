<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ExpenseCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'parent_id',
        'name', 'code', 'slug', 'description', 'color', 'icon',
        'type', 'is_taxable', 'is_billable', 'is_active',
        'budget_limit', 'budget_period', 'sort_order', 'metadata'
    ];

    protected $casts = [
        'is_taxable' => 'boolean',
        'is_billable' => 'boolean',
        'is_active' => 'boolean',
        'budget_limit' => 'decimal:2',
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
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function parent()
    {
        return $this->belongsTo(ExpenseCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ExpenseCategory::class, 'parent_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'expense_category_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function getTotalExpensesAttribute()
    {
        return $this->expenses()->sum('amount');
    }

    public function getBudgetUtilizationAttribute()
    {
        if (!$this->budget_limit) {
            return null;
        }
        return ($this->total_expenses / $this->budget_limit) * 100;
    }
}

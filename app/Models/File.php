<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class File extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'user_id', 'name', 'type', 'parent_id',
        'mime_type', 'size', 'extension', 'path', 'url'
    ];

    protected $casts = [
        'size' => 'integer'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parent()
    {
        return $this->belongsTo(File::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(File::class, 'parent_id');
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes >= 1024 && $i < 4; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIconAttribute()
    {
        $icons = [
            'folder' => 'fa-folder',
            'jpg' => 'fa-file-image',
            'jpeg' => 'fa-file-image',
            'png' => 'fa-file-image',
            'gif' => 'fa-file-image',
            'pdf' => 'fa-file-pdf',
            'doc' => 'fa-file-word',
            'docx' => 'fa-file-word',
            'xls' => 'fa-file-excel',
            'xlsx' => 'fa-file-excel',
            'ppt' => 'fa-file-powerpoint',
            'pptx' => 'fa-file-powerpoint',
            'txt' => 'fa-file-alt',
            'zip' => 'fa-file-archive',
            'rar' => 'fa-file-archive',
            'mp4' => 'fa-file-video',
            'mp3' => 'fa-file-audio',
            'default' => 'fa-file'
        ];

        if ($this->type === 'folder') {
            return $icons['folder'];
        }

        return $icons[$this->extension] ?? $icons['default'];
    }

    public function getColorAttribute()
    {
        $colors = [
            'folder' => 'text-yellow-500',
            'jpg' => 'text-blue-500',
            'jpeg' => 'text-blue-500',
            'png' => 'text-blue-500',
            'pdf' => 'text-red-500',
            'doc' => 'text-blue-700',
            'docx' => 'text-blue-700',
            'xls' => 'text-green-600',
            'xlsx' => 'text-green-600',
            'default' => 'text-gray-500'
        ];

        if ($this->type === 'folder') {
            return $colors['folder'];
        }

        return $colors[$this->extension] ?? $colors['default'];
    }
}

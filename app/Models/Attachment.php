<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Attachment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'user_id',
        'attachable_type', 'attachable_id',
        'name', 'filename', 'path', 'disk', 'mime_type',
        'extension', 'size', 'hash',
        'visibility', 'is_image', 'is_thumbnail', 'is_compressed',
        'width', 'height', 'thumbnail_path', 'original_name',
        'description', 'metadata', 'exif_data',
        'expires_at', 'access_token', 'download_count',
        'status', 'processed_at'
    ];

    protected $casts = [
        'size' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'is_image' => 'boolean',
        'is_thumbnail' => 'boolean',
        'is_compressed' => 'boolean',
        'download_count' => 'integer',
        'metadata' => 'array',
        'exif_data' => 'array',
        'expires_at' => 'datetime',
        'processed_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->access_token)) {
                $model->access_token = Str::random(64);
            }
        });

        static::deleting(function ($model) {
            if ($model->isForceDeleting() && $model->path && Storage::disk($model->disk)->exists($model->path)) {
                Storage::disk($model->disk)->delete($model->path);

                if ($model->thumbnail_path && Storage::disk($model->disk)->exists($model->thumbnail_path)) {
                    Storage::disk($model->disk)->delete($model->thumbnail_path);
                }
            }
        });
    }

    /**
     * Relations
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function attachable()
    {
        return $this->morphTo();
    }

    /**
     * Accessors
     */
    public function getUrlAttribute()
    {
        if ($this->visibility === 'private') {
            return route('attachments.download', ['token' => $this->access_token]);
        }
        return Storage::disk($this->disk)->url($this->path);
    }

    public function getThumbnailUrlAttribute()
    {
        if ($this->thumbnail_path) {
            return Storage::disk($this->disk)->url($this->thumbnail_path);
        }
        return null;
    }

    public function getSizeFormattedAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->size;
        $i = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 2) . ' ' . $units[$i];
    }

    public function getFileIconAttribute()
    {
        $icons = [
            'pdf' => '📄',
            'doc' => '📝',
            'docx' => '📝',
            'xls' => '📊',
            'xlsx' => '📊',
            'ppt' => '📽️',
            'pptx' => '📽️',
            'jpg' => '🖼️',
            'jpeg' => '🖼️',
            'png' => '🖼️',
            'gif' => '🖼️',
            'svg' => '🖼️',
            'mp4' => '🎬',
            'mp3' => '🎵',
            'zip' => '📦',
            'rar' => '📦',
            'txt' => '📃',
            'md' => '📃',
        ];

        return $icons[$this->extension] ?? '📎';
    }

    /**
     * Scopes
     */
    public function scopePublic($query)
    {
        return $query->where('visibility', 'public');
    }

    public function scopePrivate($query)
    {
        return $query->where('visibility', 'private');
    }

    public function scopeImages($query)
    {
        return $query->where('is_image', true);
    }

    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<', now());
    }

    public function scopeForAttachable($query, $attachable)
    {
        return $query->where('attachable_type', get_class($attachable))
                     ->where('attachable_id', $attachable->id);
    }

    /**
     * Methods
     */
    public function isImage(): bool
    {
        return $this->is_image;
    }

    public function isPublic(): bool
    {
        return $this->visibility === 'public';
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    public function canBeAccessed(): bool
    {
        if ($this->status !== 'ready') {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->visibility === 'private' && !auth()->check()) {
            return false;
        }

        return true;
    }

    public function incrementDownloadCount()
    {
        $this->increment('download_count');
    }

    public function generateSecureUrl()
    {
        if (empty($this->access_token)) {
            $this->access_token = Str::random(64);
            $this->saveQuietly();
        }

        return route('attachments.download', ['token' => $this->access_token]);
    }

    public static function createFromFile($file, $attachable = null, $visibility = 'private')
    {
        $path = $file->store('attachments', 'public');
        $filename = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        $mimeType = $file->getMimeType();
        $size = $file->getSize();
        $isImage = strpos($mimeType, 'image/') === 0;

        return self::create([
            'company_id' => auth()->user()?->company_id,
            'user_id' => auth()->id(),
            'attachable_type' => $attachable ? get_class($attachable) : null,
            'attachable_id' => $attachable?->id,
            'name' => pathinfo($filename, PATHINFO_FILENAME),
            'filename' => $filename,
            'path' => $path,
            'disk' => 'public',
            'mime_type' => $mimeType,
            'extension' => $extension,
            'size' => $size,
            'hash' => md5_file($file->getRealPath()),
            'visibility' => $visibility,
            'is_image' => $isImage,
            'original_name' => $filename,
            'status' => 'ready',
        ]);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid', 'company_id', 'user_id', 'commentable_type', 'commentable_id',
        'parent_id', 'edited_by',
        'content', 'raw_content', 'content_type',
        'mentions', 'tags', 'attachments',
        'reactions', 'likes_count', 'replies_count',
        'status', 'is_edited', 'is_pinned', 'is_resolved', 'is_private',
        'moderated_by', 'moderation_reason', 'moderated_at',
        'edited_at', 'published_at', 'resolved_at', 'pinned_at',
        'metadata', 'ip_address', 'user_agent'
    ];

    protected $casts = [
        'mentions' => 'array',
        'tags' => 'array',
        'attachments' => 'array',
        'reactions' => 'array',
        'likes_count' => 'integer',
        'replies_count' => 'integer',
        'is_edited' => 'boolean',
        'is_pinned' => 'boolean',
        'is_resolved' => 'boolean',
        'is_private' => 'boolean',
        'metadata' => 'array',
        'edited_at' => 'datetime',
        'published_at' => 'datetime',
        'resolved_at' => 'datetime',
        'pinned_at' => 'datetime',
        'moderated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->published_at)) {
                $model->published_at = now();
            }
            if (empty($model->ip_address)) {
                $model->ip_address = request()->ip();
            }
            if (empty($model->user_agent)) {
                $model->user_agent = request()->userAgent();
            }
        });

        static::created(function ($model) {
            // Incrémenter le compteur de réponses du commentaire parent
            if ($model->parent_id) {
                $model->parent()->increment('replies_count');
            }

            // Incrémenter le compteur de commentaires de l'entité parente
            $model->incrementCommentableCount();
        });

        static::deleted(function ($model) {
            // Décrémenter le compteur de réponses du commentaire parent
            if ($model->parent_id) {
                $model->parent()->decrement('replies_count');
            }

            // Décrémenter le compteur de commentaires de l'entité parente
            $model->decrementCommentableCount();
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

    public function commentable()
    {
        return $this->morphTo();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->where('status', 'published')
                    ->orderBy('created_at', 'asc');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'edited_by');
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }

    public function reactions()
    {
        return $this->hasMany(CommentReaction::class);
    }

    public function attachments()
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    /**
     * Scopes
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopePrivate($query)
    {
        return $query->where('is_private', true);
    }

    public function scopeForCommentable($query, $commentable)
    {
        return $query->where('commentable_type', get_class($commentable))
                     ->where('commentable_id', $commentable->id);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Methods
     */
    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPinned(): bool
    {
        return $this->is_pinned;
    }

    public function isResolved(): bool
    {
        return $this->is_resolved;
    }

    public function isPrivate(): bool
    {
        return $this->is_private;
    }

    public function canEdit(User $user = null): bool
    {
        $user = $user ?? Auth::user();
        if (!$user) {
            return false;
        }

        return $user->id === $this->user_id || $user->hasRole('admin');
    }

    public function canDelete(User $user = null): bool
    {
        $user = $user ?? Auth::user();
        if (!$user) {
            return false;
        }

        return $user->id === $this->user_id || $user->hasRole('admin') || $user->hasRole('manager');
    }

    public function publish()
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function hide()
    {
        $this->update(['status' => 'hidden']);
    }

    public function markAsSpam()
    {
        $this->update(['status' => 'spam']);
    }

    public function pin()
    {
        $this->update([
            'is_pinned' => true,
            'pinned_at' => now(),
        ]);
    }

    public function unpin()
    {
        $this->update([
            'is_pinned' => false,
            'pinned_at' => null,
        ]);
    }

    public function resolve()
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
        ]);
    }

    public function unresolve()
    {
        $this->update([
            'is_resolved' => false,
            'resolved_at' => null,
        ]);
    }

    public function addReaction($userId, $reaction)
    {
        $existing = $this->reactions()->where('user_id', $userId)->where('reaction', $reaction)->first();

        if ($existing) {
            $existing->delete();
            return false;
        }

        $this->reactions()->create([
            'user_id' => $userId,
            'reaction' => $reaction,
        ]);

        return true;
    }

    public function getReactionCountsAttribute()
    {
        return $this->reactions()
                    ->select('reaction', \DB::raw('count(*) as count'))
                    ->groupBy('reaction')
                    ->pluck('count', 'reaction')
                    ->toArray();
    }

    public function incrementCommentableCount()
    {
        if ($this->commentable && method_exists($this->commentable, 'incrementCommentsCount')) {
            $this->commentable->incrementCommentsCount();
        }
    }

    public function decrementCommentableCount()
    {
        if ($this->commentable && method_exists($this->commentable, 'decrementCommentsCount')) {
            $this->commentable->decrementCommentsCount();
        }
    }

    public function getExcerptAttribute($length = 150)
    {
        return Str::limit(strip_tags($this->content), $length);
    }

    public function getTimeAgoAttribute()
    {
        return $this->created_at->diffForHumans();
    }

    public function getAvatarAttribute()
    {
        return $this->user?->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode('Anonymous') . '&color=7F9CF5&background=EBF4FF';
    }

    public function getUserNameAttribute()
    {
        return $this->user?->name ?? 'Utilisateur supprimé';
    }
}

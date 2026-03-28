<?php

namespace App\Traits;

use App\Models\Comment;

trait HasComments
{
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')
                    ->whereNull('parent_id')
                    ->orderBy('created_at', 'desc');
    }

    public function allComments()
    {
        return $this->morphMany(Comment::class, 'commentable')
                    ->orderBy('created_at', 'desc');
    }

    public function pinnedComments()
    {
        return $this->comments()->where('is_pinned', true);
    }

    public function addComment($content, $userId = null, $parentId = null)
    {
        return Comment::create([
            'company_id' => $this->company_id ?? auth()->user()?->company_id,
            'user_id' => $userId ?? auth()->id(),
            'commentable_type' => get_class($this),
            'commentable_id' => $this->id,
            'parent_id' => $parentId,
            'content' => $content,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->count();
    }

    public function incrementCommentsCount()
    {
        if ($this->hasAttribute('comments_count')) {
            $this->increment('comments_count');
        }
    }

    public function decrementCommentsCount()
    {
        if ($this->hasAttribute('comments_count')) {
            $this->decrement('comments_count');
        }
    }
}

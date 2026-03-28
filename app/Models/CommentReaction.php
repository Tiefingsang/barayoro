<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentReaction extends Model
{
    protected $fillable = [
        'comment_id', 'user_id', 'reaction'
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByReaction($query, $reaction)
    {
        return $query->where('reaction', $reaction);
    }
}

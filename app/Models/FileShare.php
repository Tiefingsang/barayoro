<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileShare extends Model
{
    protected $fillable = [
        'uuid', 'file_id', 'user_id', 'token', 'password', 'expires_at'
    ];

    protected $casts = [
        'expires_at' => 'datetime'
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

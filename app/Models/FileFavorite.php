<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileFavorite extends Model
{
    protected $fillable = ['user_id', 'file_id'];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

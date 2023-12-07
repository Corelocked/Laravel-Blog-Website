<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id', 'title', 'excerpt', 'body', 'image_path', 'slug', 'is_published', 'additional_info',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}

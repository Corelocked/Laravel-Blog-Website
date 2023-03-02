<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'excerpt', 'body', 'image_path', 'is_published'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}

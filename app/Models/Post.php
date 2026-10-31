<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps = true;

    protected $table = 'posts';

    protected $fillable = [
        'title',
        'content',
        'slug',
        'status',
        'views_count',
        'user_id',
        'published_at',
    ];
}
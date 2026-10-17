<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    // Post -> table_name = posts
    // custome table name :
    // protected $table='table_name'

    // define cloumn name
    protected $fillable = array('title', 'content', 'status', 'user_id');
}

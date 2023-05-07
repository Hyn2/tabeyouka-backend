<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = [
        'author_id', 
        'restaurant_id', 
        'rating', 
        'review_text', 
        'image_file'
    ];
}

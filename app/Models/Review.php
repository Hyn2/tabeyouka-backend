<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';

    protected $fillable = [
        'author_id', 
        'restaurant_id', 
        'rating', 
        'review_text', 
        'image_file'
    ];
}

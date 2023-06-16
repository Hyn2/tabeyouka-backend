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
        'nickname',
        'restaurant_id', 
        'rating', 
        'review_text', 
        'image_file'
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function restaurant()
    {
        return $this->belongsTo(User::class, 'restaurant_id');
    }
}

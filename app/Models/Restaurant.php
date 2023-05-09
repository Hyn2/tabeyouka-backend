<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Restaurant extends Model
{
    use HasFactory;

    protected $table = 'restaurants';

    protected $fillable = [
        'title',
        'address',
        'menu_type',
        'phone_number',
        'total_points',
        'total_votes',
    ];

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }
}

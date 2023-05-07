<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Restaurant extends Model
{
    protected $table = 'restaurants';

    protected $fillable = [
        'title',
        'address',
        'menu_type',
        'phone_number',
        'total_points',
        'total_votes',
    ];
}

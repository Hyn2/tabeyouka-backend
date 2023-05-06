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

    public static function createTable()
    {
        Schema::create('restaurants', function ($table) {
            $table->id();
            $table->string('title');
            $table->string('address');
            $table->string('menu_type');
            $table->string('phone_number');
            $table->integer('total_points');
            $table->integer('total_votes');
            $table->timestamps();
        });
    }
}

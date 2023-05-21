<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teammate extends Model
{
    use HasFactory;

    protected $table = 'teammates';

    protected $fillable = [
        'student_id',
        'name',
        'profile_image',
        'part',
        'description',
        'github_link',
    ];
}

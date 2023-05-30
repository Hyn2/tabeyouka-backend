<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalSemesterComments extends Model
{
    use HasFactory;

    protected $table = 'local_semester_comments';

    protected $fillable = [
        'author_id',
        'comment_text',
    ];
}

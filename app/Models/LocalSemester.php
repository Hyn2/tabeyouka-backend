<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocalSemester extends Model
{
    use HasFactory;

    // protected 접근 제어자는 클래스 내부, 상속받은 클래스들만 접근 가능
    protected $table = 'local_semester';

    // $fillable 변수를 통해 입력을 받을 컬럼을 지정함으로써 다른 컬럼은 보호
    protected $fillable = [
        'article',
    ];

}

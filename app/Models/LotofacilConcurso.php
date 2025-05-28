<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotofacilConcurso extends Model
{
    protected $fillable = ['concurso', 'data', 'dezenas'];

    protected $casts = [
        'data'    => 'date',
        'dezenas' => 'array',
    ];
}

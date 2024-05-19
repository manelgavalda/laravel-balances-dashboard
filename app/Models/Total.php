<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Total extends Model
{
    protected $guarded = [];

    protected $casts = [
        'debt' => 'float',
        'total' => 'float',
    ];
}

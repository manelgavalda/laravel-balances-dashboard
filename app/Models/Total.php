<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Total extends Model
{
    protected $guarded = [];

    protected $casts = [
        'price' => 'float',
        'balance' => 'float',
        'price_eur' => 'float'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $guarded = [];

    protected $casts = [
        'btc' => 'float',
        'eth' => 'float',
        'eur' => 'float',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Total extends Model
{
    protected $guarded = [];

    protected $casts = [
        'debt' => 'float',
        'price' => 'float',
        'balance' => 'float',
        'price_eur' => 'float',
        'btc_price' => 'float',
        'btc_price_eur' => 'float'
    ];
}

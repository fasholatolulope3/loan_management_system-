<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collateral extends Model
{
    protected $fillable = ['loan_id', 'type', 'description', 'purchase_price', 'market_value', 'liquidation_value'];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'market_value' => 'decimal:2',
        'liquidation_value' => 'decimal:2', // We will calculate this automatically (50-80%)
    ];
}

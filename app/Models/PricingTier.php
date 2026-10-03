<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'min_users' => 'integer',
        'max_users' => 'integer',
        'annual_price_per_user' => 'decimal:2',
        'is_active' => 'boolean',
    ];
}

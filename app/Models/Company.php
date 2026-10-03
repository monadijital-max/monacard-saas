<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'staff_features' => 'array',
        'social_links' => 'array',
        'user_quota' => 'integer',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function businessCards(): HasMany
    {
        return $this->hasMany(BusinessCard::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class)->orderBy('sort_order', 'asc');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    public function integrations(): HasMany
    {
        return $this->hasMany(CompanyIntegration::class);
    }
}

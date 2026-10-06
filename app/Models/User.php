<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function businessCard(): HasOne
    {
        return $this->hasOne(BusinessCard::class);
    }

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function subordinates(): HasMany
    {
        return $this->hasMany(User::class, 'leader_id');
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'staff_id');
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class, 'staff_id');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class, 'user_id');
    }

    public function targets(): HasMany
    {
        return $this->hasMany(StaffTarget::class, 'user_id');
    }

    public function staffTargets(): HasMany
    {
        return $this->hasMany(StaffTarget::class, 'user_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isCompanyAdmin(): bool
    {
        return $this->role === 'company_admin';
    }

    public function isStaff(): bool
    {
        return $this->role === 'staff';
    }
}

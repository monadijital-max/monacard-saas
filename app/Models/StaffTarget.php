<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StaffTarget extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'month' => 'integer',
        'year' => 'integer',
        'monthly_meeting_goal' => 'integer',
        'monthly_hot_lead_goal' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    protected $fillable = [
        'student_id',
        'group_id',
        'amount',
        'paid',
        'due_date',
    ];

    protected $casts = [
        'amount' => 'float',
        'paid' => 'float',
        'due_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function getRemainingAttribute(): float
    {
        $remaining = $this->amount - $this->paid;

        return $remaining > 0 ? $remaining : 0.0;
    }
}


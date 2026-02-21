<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'specialization',
        'salary_type',
        'commission_rate',
    ];

    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'teacher_subject_group')
            ->withPivot('group_id')
            ->withTimestamps();
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'teacher_subject_group')
            ->withPivot('subject_id')
            ->withTimestamps();
    }

    public function homeworks(): HasMany
    {
        return $this->hasMany(Homework::class);
    }
}

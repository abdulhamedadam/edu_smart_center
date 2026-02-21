<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Student extends Model
{
    protected $fillable = [
        'student_code',
        'public_token',
        'name',
        'phone',
        'parent_id',
        'parent_name',
        'parent_phone',
        'grade_id',
        'avatar_path',
    ];

    protected static function booted(): void
    {
        static::creating(function (self $student) {
            if (!$student->public_token) {
                $student->public_token = Str::uuid()->toString();
            }
        });
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(StudentParent::class, 'parent_id');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function homeworkSubmissions(): HasMany
    {
        return $this->hasMany(HomeworkSubmission::class);
    }

    public function examResults(): HasMany
    {
        return $this->hasMany(ExamResult::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function getQrPayloadAttribute(): string
    {
        return json_encode([
            'code' => $this->student_code,
            'name' => $this->name,
            'phone' => $this->phone,
            'parent_name' => $this->parent_name,
            'parent_phone' => $this->parent_phone,
            'grade' => $this->grade?->name,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function publicProfileUrl(): ?string
    {
        if (!$this->public_token) {
            return null;
        }

        return route('public.students.show', $this->public_token);
    }
}

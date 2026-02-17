<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $fillable = [
        'student_code',
        'name',
        'phone',
        'parent_id',
        'parent_name',
        'parent_phone',
        'grade_id',
        'avatar_path',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(StudentParent::class, 'parent_id');
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
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
}

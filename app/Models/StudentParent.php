<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentParent extends Model
{
    protected $table = 'parents';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'relation',
    ];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'parent_id');
    }
}

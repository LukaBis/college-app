<?php

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\Factory;

class Project extends Model
{
    use HasFactory;

    public function course(): BelongsTo
    {
        $this->belongsTo(Course::class, 'course_id');
    }
}

<?php

namespace App\Models;

use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): CourseFactory
    {
        return CourseFactory::new();
    }

    public function courseAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'course_admin_id');
    }
}

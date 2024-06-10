<?php

namespace App\Models;

use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): CourseFactory
    {
        return CourseFactory::new();
    }

    public function courseAdmin(): HasOne
    {
        return $this->hasOne(User::class, 'course_id', 'id');
    }
}

<?php

namespace App\Models;

use Database\Factories\CourseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function newFactory(): CourseFactory
    {
        return CourseFactory::new();
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'course_user', 'course_id', 'user_id');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function admins(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'admin_course', 'course_id', 'user_id')->withPivot(['user_id', 'course_id']);
    }

    public function valuationTerms(): HasMany
    {
        return $this->hasMany(ValuationTerm::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function marks(): HasMany
    {
        return $this->hasMany(Mark::class);
    }
}

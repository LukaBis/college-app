<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomDeadline extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'custom_deadline_user', 'custom_deadline_id', 'user_id');
    }

    public function customDeadlineUserPivot(): HasMany
    {
        return $this->hasMany(CustomDeadlineUser::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($customDeadline) {
            $students = $customDeadline->course->students;

            foreach ($students as $student) {
                CustomDeadlineUser::create([
                    'custom_deadline_id' => $customDeadline->id,
                    'user_id' => $student->id,
                ]);
            }
        });
    }
}

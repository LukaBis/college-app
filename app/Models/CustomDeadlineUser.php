<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomDeadlineUser extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'custom_deadline_user';

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected function studentFullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->student->full_name,
        );
    }

    protected function studentEmail(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->student->email,
        );
    }
}

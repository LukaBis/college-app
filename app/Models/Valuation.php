<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Valuation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'valuation' => 'array',
    ];

    public function valuationTerm(): BelongsTo
    {
        return $this->belongsTo(ValuationTerm::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_evaluator_id');
    }

    public function ratedStudent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rated_student_id');
    }
}

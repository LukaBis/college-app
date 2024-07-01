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

    public function totalPoints(): int
    {
        $givenMarks = array_values($this->valuation);
        $courseMarks = $this->project->course->marks->pluck('mark', 'points');

        // Create a map of marks to points
        $marksToPoints = [];
        foreach ($courseMarks as $points => $mark) {
            $marksToPoints[$mark] = $points;
        }

        // Calculate total points
        $totalPoints = 0;
        foreach ($givenMarks as $mark) {
            if (isset($marksToPoints[$mark])) {
                $totalPoints += $marksToPoints[$mark];
            }
        }

        return $totalPoints;
    }

}

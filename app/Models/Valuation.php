<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Valuation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'valuation' => 'array',
    ];

    protected function isFilled(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->valuation !== null,
        );
    }

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

    /**
     * Valuation field is json object. Key is id of the question and value is given mark.
     * This method returns sum of points from this single valuation.
     *
     * @return int
     */
    public function totalPoints(): int
    {
        // Initialize total points to 0
        $totalPoints = 0;

        // Decode the valuation JSON field to an associative array
        $valuation = $this->valuation;

        // Loop through each key-value pair in the valuation array
        foreach ($valuation as $questionId => $markValue) {
            // Find the question by its ID
            $question = Question::find($questionId);

            // If the question exists
            if ($question) {
                // Find the mark related to this question that matches the mark value
                $mark = $question->marks()->where('mark', $markValue)->first();

                // If the mark exists, add its points to the total points
                if ($mark) {
                    $totalPoints += $mark->points;
                }
            }
        }

        // Return the total points
        return $totalPoints;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class ValuationTerm extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($valuationTerm) {
            $projects = Project::where('course_id', $valuationTerm->course_id)->get();

            $projects->each(function ($project) use ($valuationTerm) {
                $students = $project->students;

                // Loop through each student as evaluator
                $students->each(function ($evaluator) use ($students, $valuationTerm, $project) {
                    // Loop through each student as the rated student
                    $students->each(function ($ratedStudent) use ($evaluator, $valuationTerm, $project) {
                        // Create the valuation
                        Valuation::create([
                            'valuation_term_id' => $valuationTerm->id,
                            'student_evaluator_id' => $evaluator->id,
                            'rated_student_id' => $ratedStudent->id,
                            'project_id' => $project->id,
                            'self_evaluation' => $evaluator->id === $ratedStudent->id, // true if self-evaluation
                        ]);
                    });
                });
            });
        });
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function valuations(): HasMany
    {
        return $this->hasMany(Valuation::class);
    }
}

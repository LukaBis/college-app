<?php

namespace App\Exports;

use App\Models\Course;
use App\Models\Valuation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ValuationsExport implements FromCollection, WithHeadings
{
    protected $questionTitles = [];

    public function __construct(Course $course)
    {
        $this->collectQuestionTitles($course);
    }

    public function collection(): Collection
    {
        // TBD: query only valuations from this course
        $valuations = Valuation::with(['valuationTerm', 'evaluator', 'ratedStudent', 'project'])->get();

        $data = $valuations->map(function ($valuation) {
            $row = [
                'valuation_term' => $valuation->valuationTerm->title,
                'student_evaluator' => $valuation->evaluator->full_name,
                'rated_student' => $valuation->ratedStudent->full_name,
                'project' => $valuation->project->name,
                'self_evaluation' => $valuation->self_evaluation,
                'extra_comment' => $valuation->extra_comment,
                'created_at' => $valuation->created_at,
            ];

            // Adding questions and their marks
            $questions = $valuation->valuation;
            if ($questions !== null) {
                foreach ($questions as $questionId => $mark) {
                    $questionTitle = \App\Models\Question::find($questionId)->title;
                    $row[$questionTitle] = $mark;
                }
            }

            return $row;
        });

        return collect($data);
    }

    public function headings(): array
    {
        // Static headings
        $headings = [
            'Valuation Term',
            'Student Evaluator',
            'Rated Student',
            'Project',
            'Self Evaluation',
            'Extra Comment',
            'Created At',
        ];

        // Dynamic headings from questions
        foreach ($this->questionTitles as $title) {
            $headings[] = $title;
        }

        return $headings;
    }

    protected function collectQuestionTitles(Course $course)
    {
        $questions = $course->questions;

        foreach ($questions as $question) {
            $this->questionTitles[$question->id] = $question->title;
        }
    }
}

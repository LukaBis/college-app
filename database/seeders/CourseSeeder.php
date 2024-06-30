<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Course;
use App\Models\Mark;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\Question;
use Database\Factories\MarkFactory;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::factory(5)->create()->each(function ($course) {
            Project::factory(3)->create([
                'course_id' => $course->id,
            ])->each(function ($project) {
                Meeting::factory(3)->create([
                    'project_id' => $project->id,
                ]);

                Activity::factory(3)->create([
                    'project_id' => $project->id,
                ]);
            });

            Mark::factory(5)->create([
                'course_id' => $course->id,
            ]);

            Question::factory(4)->create([
                'course_id' => $course->id,
            ]);
        });
    }
}

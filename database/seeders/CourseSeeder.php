<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\Course;
use App\Models\Mark;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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

            $questions = Question::factory(4)->create([
                'course_id' => $course->id,
            ]);

            $questions->each(function ($question) {
                Mark::factory(4)->create([
                    'question_id' => $question->id,
                ]);
            });
        });

        $this->createCourseAdmin();
    }

    private function createCourseAdmin()
    {
        $courseAdmin = User::create([
            'name' => 'CA pero',
            'surname' => 'peric',
            'email' => 'pero@email.com',
            'password' => Hash::make('password'),
        ]);

        $courseAdmin->assignRole('Course Admin');

        $oneCourse = Course::factory()->create();
        $oneCourse->admins()->attach($courseAdmin->id);

        Project::factory(3)->create([
            'course_id' => $oneCourse->id,
        ])->each(function ($project) {
            Meeting::factory(3)->create([
                'project_id' => $project->id,
            ]);

            Activity::factory(3)->create([
                'project_id' => $project->id,
            ]);
        });

        $questions = Question::factory(4)->create([
            'course_id' => $oneCourse->id,
        ]);

        $questions->each(function ($question) {
            Mark::factory(4)->create([
                'question_id' => $question->id,
            ]);
        });
    }
}

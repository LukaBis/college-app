<?php

namespace App\Observers;

use App\Models\Course;
use App\Models\User;
use App\Services\CsvService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CourseObserver
{
    /**
     * Handle the Course "created" event.
     */
    public function created(Course $course): void
    {
        if ($course->student_file === null) {
            return;
        }

        $this->createNewStudents($course);
    }

    /**
     * Handle the Course "updated" event.
     */
    public function updated(Course $course): void
    {
        if (! $course->isDirty('student_file')) {
            return;
        }

        $this->createNewStudents($course);
    }

    /**
     * Handle the Course "deleted" event.
     */
    public function deleted(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "restored" event.
     */
    public function restored(Course $course): void
    {
        //
    }

    /**
     * Handle the Course "force deleted" event.
     */
    public function forceDeleted(Course $course): void
    {
        //
    }

    /**
     * @throws \Exception
     */
    public function createNewStudents(Course $course): void
    {
        if (! Storage::disk('student-files')->exists($course->student_file)) {
            return;
        }

        $fileContents = Storage::disk('student-files')->get($course->student_file);

        $csvService = new CsvService($fileContents);
        $csvService->validate();

        foreach ($csvService->getItems() as $student) {

            if ($course->students()->count() === $course->max_students) {
                return;
            }

            $student = User::create([
                'name' => $student['name'],
                'surname' => $student['surname'],
                'email' => $student['email'],
                'jmbag' => $student['jmbag'],
                'password' => Hash::make(config('students.default-password')),
            ]);

            $student->assignRole('Student');
            $student->attendingCourse()->attach($course);
        }
    }
}

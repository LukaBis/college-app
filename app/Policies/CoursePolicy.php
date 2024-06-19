<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {
        $courseAdmin = $user->id === $course->course_admin_id;
        $student = $user->attendingCourse()->get()->contains($course);

        return $user->checkPermissionTo('view Course') && ($courseAdmin | $student);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Course');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        $courseAdmin = $user->id === $course->course_admin_id;
        $student = $user->attendingCourse()->get()->contains($course);

        return $user->checkPermissionTo('update Course') && ($courseAdmin | $student);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->checkPermissionTo('delete Course');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Course $course): bool
    {
        return $user->checkPermissionTo('restore Course');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        return $user->checkPermissionTo('force-delete Course');
    }
}

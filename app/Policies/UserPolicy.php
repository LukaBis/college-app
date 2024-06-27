<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
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
    public function view(User $user, User $model): bool
    {
        $studentAttendingCourses = $model->attendingCourse()->get();
        $courseAdminManagingCourses = $user->course()->get();
        $intersection = $studentAttendingCourses->intersect($courseAdminManagingCourses);

        $isCorrespondingCourseAdmin = $intersection->isNotEmpty();

        return $user->checkPermissionTo('view User') && ($user->id === $model->id || $isCorrespondingCourseAdmin);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create User');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        $studentAttendingCourses = $model->attendingCourse()->get();
        $courseAdminManagingCourses = $user->course()->get();
        $intersection = $studentAttendingCourses->intersect($courseAdminManagingCourses);

        $isCorrespondingCourseAdmin = $intersection->isNotEmpty();

        return $user->checkPermissionTo('update User') && ($user->id === $model->id || $isCorrespondingCourseAdmin);
    }

    /**
     * Determine if user can mark student as active or not active.
     */
    public function updateActive(User $user, User $model)
    {
        return $user->hasRole('Course Admin');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        return $user->checkPermissionTo('delete User');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): bool
    {
        return $user->checkPermissionTo('restore User');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->checkPermissionTo('force-delete User');
    }
}

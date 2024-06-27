<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
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
    public function view(User $user, Project $project): bool
    {
        $courseAdmin = $project->course->admins()->get()->contains($user);
        $student = $user->attendingCourse()->get()->contains($project->course);

        return $user->checkPermissionTo('view Project') && ($courseAdmin | $student);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Project');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        $courseAdmin = $project->course->admins()->get()->contains($user);
        $student = $user->attendingCourse()->get()->contains($project->course);

        return $user->checkPermissionTo('update Project') && ($courseAdmin | $student);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $user->checkPermissionTo('delete Project');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return $user->checkPermissionTo('restore Project');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return $user->checkPermissionTo('force-delete Project');
    }
}

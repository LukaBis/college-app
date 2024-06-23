<?php

namespace App\Observers;

use App\Models\User;
use Carbon\Carbon;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Avoid recursion by checking the flag
        if ($user->processingUpdate) {
            return;
        }

        if (! $user->isDirty('active')) {
            return;
        }

        $user->processingUpdate = true;

        $now = Carbon::now('Europe/Zagreb')->format('d-m-y H:i:s');
        $action = $user->active === true ? 'activated' : 'deactivated';

        $activationDates = $user->activation_dates ?? [];

        $activationDates[$now] = $action;

        $user->activation_dates = $activationDates;
        $user->save();

        $user->processingUpdate = false;
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}

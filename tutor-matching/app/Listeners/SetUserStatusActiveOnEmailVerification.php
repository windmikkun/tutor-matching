<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;

class SetUserStatusActiveOnEmailVerification
{
    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        $user = $event->user;
        if ($user->status !== 'active') {
            $user->status = 'active';
            $user->save();
        }
    }
}

<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;

class UpdateUserStatus
{
    public function handle(Login $event)
    {
        $user = $event->user;

        $user->status = 'active';
        $user->save();
    }
}

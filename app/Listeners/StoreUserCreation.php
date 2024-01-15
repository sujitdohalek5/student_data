<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\EventRegistration;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreUserCreation
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {

        EventRegistration::create([
            'user_id' =>  $event->user->id
        ]);
    }
}

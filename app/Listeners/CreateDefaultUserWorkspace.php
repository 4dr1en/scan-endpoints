<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;


class CreateDefaultUserWorkspace
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $event->user->workspaces()->create([
            'name' => __('Default workspace'),
            'description' => __(
                'This is :username default workspace.',
                ['username' => $event->user->first_name]
            )
            ], ['role' => 'owner']
        );
    }
}

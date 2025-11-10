<?php

namespace App\Listeners;

use App\Events\MissionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateMissionTasks
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
    public function handle(MissionCreated $event): void
    {
        $event->mission->addDefaultTasksSilently();
    }
}

<?php

namespace App\Listeners;

use App\Events\ModerationFileStored;
use App\Events\ModerationSuccess;
use App\Services\FileServices;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreModerationFile
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ModerationSuccess  $event
     * @return void
     */
    public function handle(ModerationSuccess $event)
    {
        $file = new FileServices($event->content);
        $file->save();

        ModerationFileStored::dispatch([$file->fileName, $event->response]);
    }
}

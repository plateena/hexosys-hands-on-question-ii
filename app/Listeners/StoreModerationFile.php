<?php

namespace App\Listeners;

use App\Events\ModerationFileStored;
use App\Events\ModerationSuccess;
use App\Services\FileServices;

/**
 * StoreModerationFile
 *
 * @copyright 2021 plateena
 * @author plateena <plateena711@gmail.com>
 */
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

        // trigger the event file stored
        ModerationFileStored::dispatch([$file->fileName, $event->response]);
    }
} // End class StoreModerationFile

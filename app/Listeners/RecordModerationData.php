<?php

namespace App\Listeners;

use App\Events\ModerationFileStored;
use App\Models\Sample;
use Illuminate\Support\Facades\DB;

/**
 * RecordModerationData
 *
 * Will record the file moderation result to the database
 *
 * @copyright 2021 plateena
 * @author plateena <plateena711@gmail.com>
 */
class RecordModerationData
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
     * Save moderation result to the database
     *
     * @param  ModetationFileStored  $event
     * @return void
     */
    public function handle(ModerationFileStored $event)
    {
        DB::transaction(function () use ($event) {
            $sample = new Sample();

            $sample->path = $event->fileName;
            $sample->moderation_data = json_encode($event->response);
            $sample->save();


            if (collect($event->response)->has('ModerationLabels')) {
                foreach ($event->response->ModerationLabels as $key => $val) {
                    $sample->moderationLabels()->create([
                        'name' => $key,
                        'confidence' => $val->Confidence
                    ]);
                }
            }
        });
    }
} // End class RecordModerationData

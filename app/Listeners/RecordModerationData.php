<?php

namespace App\Listeners;

use App\Events\ModerationFileStored;
use App\Models\Sample;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

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


            foreach ($event->response->ModerationLabels as $key => $val) {
                $sample->moderationLabels()->create([
                    'name' => $key,
                    'confidence' => $val->Confidence
                ]);
            }
        });
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * ModerationFileStored
 *
 * @copyright 2021 plateena
 * @author plateena <plateena711@gmail.com>
 */
class ModerationFileStored
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $fileName;
    public $response;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        list($this->fileName, $this->response) = $data;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
} // End class ModerationFileStored

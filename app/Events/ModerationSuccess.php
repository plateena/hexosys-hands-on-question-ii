<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * ModerationSuccess
 *
 * @copyright 2021 plateena
 * @author plateena <plateena711@gmail.com>
 */
class ModerationSuccess
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $response;
    public $content;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        list($this->response, $this->content) = $data;
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
} // End class ModerationSuccess

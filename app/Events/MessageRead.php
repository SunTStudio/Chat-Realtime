<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageRead implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_id;
    public $receiver_id;

    /**
     * Create a new event instance.
     */
    public function __construct($user_id, $receiver_id)
    {
        $this->user_id = $user_id;
        $this->receiver_id = $receiver_id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $ids = [$this->user_id, $this->receiver_id];
        sort($ids);
        return [new PrivateChannel('chat.' . $ids[0] . '.' . $ids[1])];
    }
}
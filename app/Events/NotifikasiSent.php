<?php

namespace App\Events;

use App\Models\Notifikasi;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifikasiSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Notifikasi $notifikasi)
    {
        $this->notifikasi->load('pengirim'); 
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('notifikasi.' . $this->notifikasi->user_id),
        ];
    }
    public function broadcastWith(): array
    {
        return [
            'id' => $this->notifikasi->id,
            'notifikasi' => $this->notifikasi->notifikasi,
            'pengirim_id' => $this->notifikasi->pengirim_id,
            'pengirim' => $this->notifikasi->pengirim ? $this->notifikasi->pengirim->name : null,
            'created_at' => $this->notifikasi->created_at->toDateTimeString(),
        ];
    }
}

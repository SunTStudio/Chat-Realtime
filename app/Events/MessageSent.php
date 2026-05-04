<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

// implements ShouldBroadcast: Memberitahu Laravel bahwa Event ini wajib disiarkan (broadcast) ke WebSocket
class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        // Memuat (load) data tabel relasi 'user' agar kita bisa mengambil nama pengirim pesan nanti
        $this->message->load('user');
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        // Mengurutkan ID pengirim dan penerima dari yang terkecil ke terbesar.
        // Tujuannya agar nama channel selalu konsisten (misal "chat.1.2") tidak peduli siapa yang mengirim pesan duluan.
        $ids = collect([$this->message->user_id, $this->message->receiver_id])->sort()->values();
        
        // Membuat private channel (hanya user yang berhak/terlibat yang bisa mendengarkan channel ini)
        return [
            new PrivateChannel("chat.{$ids[0]}.{$ids[1]}"),
        ];
    }

    // Method ini opsional, digunakan untuk memformat data yang akan dikirim ke Javascript
    public function broadcastWith(): array
    {
        // Menentukan data spesifik apa saja yang diterima oleh Javascript (frontend)
        return [
            'id'         => $this->message->id,
            'content'    => $this->message->content,
            'user_id'    => $this->message->user_id,
            'user'       => $this->message->user->name,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}

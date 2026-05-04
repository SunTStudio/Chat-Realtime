<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Events\NotifikasiSent;
use App\Events\MessageRead;
use App\Models\Message;
use App\Models\Notifikasi;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        // Mengambil semua user dari database, KECUALI user yang saat ini sedang login
        $users = User::where('id', '!=', auth()->id())->get();
        // Menghitung jumlah pesan yang belum dibaca untuk setiap user
        $unreadCount = Message::where('receiver_id', auth()->id())
            ->where('read', false)
            ->groupBy('user_id')
            ->selectRaw('user_id, COUNT(*) as count')
            ->pluck('count', 'user_id'); // Hasilnya: [user_id => unread_count, ...]
            

        return view('chat.index', compact('users', 'unreadCount'));
    }

    public function show(User $user)
    {
        $myId    = auth()->id(); // ID kita (yang sedang login)
        $otherId = $user->id;    // ID lawan bicara dari parameter URL

        // Mengambil riwayat pesan antara kita dan lawan bicara
        $messages = Message::with('user')
            ->where(function ($q) use ($myId, $otherId) {
                // Kondisi 1: Pesan yang KITA kirim ke DIA
                $q->where('user_id', $myId)->where('receiver_id', $otherId);
            })
            ->orWhere(function ($q) use ($myId, $otherId) {
                // Kondisi 2: Pesan yang DIA kirim ke KITA
                $q->where('user_id', $otherId)->where('receiver_id', $myId);
            })
            ->oldest() // Urutkan dari pesan terlama ke terbaru (dari atas ke bawah)
            ->get();
        
           
        return view('chat.show', compact('user', 'messages'));
    }

    public function store(Request $request, $user)
    {
        // Validasi: pesan wajib diisi, berupa teks, dan maksimal 1000 karakter
        $request->validate(['content' => 'required|string|max:1000']);
        
        // Simpan pesan baru tersebut ke database
        $message = Message::create([
            'user_id'     => auth()->id(),
            'receiver_id' => $user, // ID penerima
            'content'     => $request->content,
            'read'        => false, // Set status pesan sebagai belum dibaca
        ]);

        // Siarkan (broadcast) event MessageSent ke WebSocket.
        // ->toOthers() artinya: "Kirim ke semua orang di channel tersebut, KECUALI saya sendiri"
        // Ini mencegah kita menerima pesan kita sendiri dua kali di frontend.
        broadcast(new MessageSent($message))->toOthers();

        // kirim notifikasi real-time ke penerima pesan
        $notifikasi = Notifikasi::create([
            'pengirim_id' => auth()->id(),
            'user_id'     => $user, // ID penerima notifikasi
            'notifikasi'  => 'Pesan baru dari ' . auth()->user()->name,
        ]);

        // Trigger event untuk notifikasi real-time
        event(new NotifikasiSent($notifikasi));

        // Kembalikan respons JSON berisi data pesan untuk dirender secara instan oleh JS (fetch)
        return response()->json($message->load('user'));
    }

    public function checkMessages($user)
    {
        $myId = auth()->id(); // ID kita (yang sedang login)

        // Ambil semua pesan yang belum dibaca dari lawan bicara tertentu
        $unreadMessages = Message::where('user_id', $user) // Pesan yang dikirim oleh lawan bicara
            ->where('receiver_id', $myId) // Pesan yang ditujukan kepada kita
            ->where('read', false) // Pesan yang belum dibaca
            ->get();

        if ($unreadMessages->isNotEmpty()) {
            // Tandai semua pesan tersebut sebagai sudah dibaca
            Message::whereIn('id', $unreadMessages->pluck('id'))->update(['read' => true]);
            // Broadcast event bahwa pesan telah dibaca ke pengirim
            broadcast(new MessageRead($user, $myId))->toOthers();
        }

        // Kembalikan respons JSON berisi pesan-pesan yang tadi belum dibaca
        return response()->json($unreadMessages);
    }
}

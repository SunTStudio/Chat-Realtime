<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use App\Models\User;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $myId = auth()->id();
        
        // Ambil ID dari semua user yang sudah berteman dengan kita
        $friendIds = Friend::where('user_id', $myId)->pluck('friend_id')
            ->merge(Friend::where('friend_id', $myId)->pluck('user_id'))
            ->toArray();
            
        $friendIds[] = $myId; // Tambahkan ID sendiri agar tidak muncul di list

        // Ambil data user yang belum berteman dengan kita
        $users = User::whereNotIn('id', $friendIds)->get();
        $usersWaitingAccept = User::whereIn('id', Friend::where('user_id', $myId)->where('status', 'pending')->pluck('friend_id'))->get();

        return view('friend.index', compact('users', 'usersWaitingAccept'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'friend_id' => 'required|exists:users,id'
        ]);

        Friend::create([
            'user_id' => auth()->id(),
            'friend_id' => $request->friend_id
        ]);

        return redirect()->back()->with('success', 'Berhasil menambahkan teman baru!');
    }

    public function request()
    {
        $myId = auth()->id();
        $requests = Friend::where('friend_id', $myId)->where('status', 'pending')->with('user')->get();
        return view('friend.request', compact('requests'));
    }
    public function requestStore(Request $request)
    {
        // ini untuk menerima permintaan teman
        $request->validate([
            'friend_id' => 'required|exists:users,id'
        ]);
        Friend::where('user_id', $request->friend_id)
            ->where('friend_id', auth()->id())
            ->update(['status' => 'accepted']);

        return redirect()->back()->with('success', 'Berhasil menerima permintaan pertemanan!');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Friend $friend)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Friend $friend)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Friend $friend)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Friend $friend)
    {
        //
    }
}

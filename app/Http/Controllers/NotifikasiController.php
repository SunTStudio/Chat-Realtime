<?php

namespace App\Http\Controllers;

use App\Events\NotifikasiSent;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function trigger(Request $request, $user)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'notifikasi' => 'required|string|max:255',
        ]);

        // Simpan notifikasi baru ke database
        $notifikasi = Notifikasi::create([
            'pengirim_id' => auth()->id(),
            'user_id'     => $user, // ID penerima notifikasi
            'notifikasi'  => $request->notifikasi,
        ]);

        // Trigger event untuk notifikasi real-time
        event(new NotifikasiSent($notifikasi));

        return response()->json(['message' => 'Notifikasi berhasil dikirim.']);
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Notifikasi $notifikasi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notifikasi $notifikasi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notifikasi $notifikasi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notifikasi $notifikasi)
    {
        //
    }
}

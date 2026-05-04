<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{user}', [ChatController::class, 'store']);
    // notifikasi
    // trigger-notifikasi
    Route::post('/trigger-notifikasi/{user}', [NotifikasiController::class, 'trigger'])->name('trigger.notifikasi');
    // /check-messages/{{ $user->id }}
    Route::get('/check-messages/{user}',[ChatController::class, 'checkMessages'])->name('check.messages');
    // friends.index
    Route::get('/friends', [FriendController::class, 'index'])->name('friends.index');
    Route::post('/friends/store', [FriendController::class, 'store'])->name('friends.store');
    // hapsus teman
    Route::delete('/friends/{friend}', [FriendController::class, 'destroy'])->name('friends.destroy');
    // friends.request
    Route::get('/friends/request', [FriendController::class, 'request'])->name('friends.request');
    Route::post('/friends/request', [FriendController::class, 'requestStore'])->name('friends.requestStore');
    
});
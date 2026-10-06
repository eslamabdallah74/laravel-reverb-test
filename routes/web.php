<?php

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/send');
});

Route::get('/send', function () {
    return view('send');
});

Route::post('/send', function (Request $request) {
    $request->validate([
        'message' => 'required|string|max:500',
    ]);

    $message = $request->input('message');

    // Broadcast the message through Reverb
    broadcast(new MessageSent($message));

    return response()->json(['status' => 'success', 'message' => $message]);
});

Route::get('/receive', function () {
    return view('receive');
});

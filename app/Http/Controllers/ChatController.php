<?php

namespace App\Http\Controllers;

use App\Events\MessageSentEvent;
use Illuminate\Http\Request;
use Illuminate\Mail\Events\MessageSent;

class ChatController extends Controller
{
    public function showChat() {
        return view('chat');
    }

    public function messageReceived(Request $request) {
        $rules = [
            'message' => 'required'
        ];

        $request->validate($rules);

        broadcast(new MessageSentEvent($request->user(), $request->message));

        return response()->json("Message Broadcast");
    }
}

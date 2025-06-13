<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\design_pattern\factory\factory_creator\NotificationFactory;
use App\Events\Message as EventsMessage;
use App\Models\Message;

class MessageController extends Controller
{
    public function store(Request $request) {
        $data= $request->all();
        $message = Message::create($data);
        broadcast(new EventsMessage($message))->toOthers();
        $message->load('user:id,name');
        return response()->json($message,200);
    }
}

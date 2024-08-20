<?php

namespace App\Http\Controllers;

use App\Events\Chat;
use App\Events\messageevent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;



class MessageController extends Controller
{
    public function index($receiver_id)
    {
        $messages = Message::where(function ($query) use ($receiver_id) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $receiver_id);
        })->orWhere(function ($query) use ($receiver_id) {
            $query->where('sender_id', $receiver_id)
                ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();
        $sender = Auth::id();
        $role = 'client';
        $receiverRole = 'client';

        return view('dashboard', ['messages' => $messages, 'sender' => $sender, 'receiver' => $receiver_id, 'role' => $role, 'receiverRole' => $receiverRole]);
    }
    public function sendMessage(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);
        $receiver_id = $request->receiver_id;
        $messages = Message::where(function ($query) use ($receiver_id) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $receiver_id);
        })->orWhere(function ($query) use ($receiver_id) {
            $query->where('sender_id', $receiver_id)
                ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();
        $sender = Auth::id();
        $role = 'client';
        $receiverRole = 'client';

        return view('dashboard', ['messages' => $messages, 'sender' => $sender, 'receiver' => $receiver_id, 'role' => $role, 'receiverRole' => $receiverRole]);
    }

    public function getMessages($receiver_id)
    {
        $messages = Message::where(function ($query) use ($receiver_id) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $receiver_id);
        })->orWhere(function ($query) use ($receiver_id) {
            $query->where('sender_id', $receiver_id)
                ->where('receiver_id', Auth::id());
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    public function sentMessage(Request $request): JsonResponse
    {
        // return response()->json(['success' => $request->all()]);
        event(new Chat(
            $request->input('username'),
            $request->input('message'),
        ));
    
        return response()->json(['success' => true]);
    }
    
}

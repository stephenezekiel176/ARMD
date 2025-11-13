<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class FacilitatorMessageHistoryController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $messages = Message::where('sender_id', $user->id)
            ->with(['recipient', 'department'])
            ->latest()
            ->paginate(20);

        return view('facilitator.messages.index', compact('messages'));
    }

    public function show(Message $message)
    {
        // Ensure the message belongs to the facilitator
        if ($message->sender_id !== auth()->id()) {
            abort(403, 'Unauthorized access to this message.');
        }

        $message->load(['recipient', 'department']);

        return view('facilitator.messages.show', compact('message'));
    }

    public function create()
    {
        $user = auth()->user();
        
        // Get personnel for individual messaging
        $personnel = \App\Models\User::where('role', 'personnel')
            ->where('department_id', $user->department_id)
            ->get();

        return view('facilitator.messages.create', compact('personnel'));
    }
}

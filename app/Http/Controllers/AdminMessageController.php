<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class AdminMessageController extends Controller
{
    public function index()
    {
        $messages = Message::with(['sender', 'recipient'])
            ->latest()
            ->paginate(20);

        return view('admin.messages.index', compact('messages'));
    }

    public function create()
    {
        $facilitators = User::where('role', 'facilitator')
            ->with('department')
            ->orderBy('fullname')
            ->get();

        return view('admin.messages.create', compact('facilitators'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
            'recipient_type' => 'required|in:individual,all',
            'recipient_id' => 'required_if:recipient_type,individual|exists:users,id',
        ]);

        $admin = auth()->user();

        if ($request->recipient_type === 'all') {
            // Send to all facilitators
            $facilitators = User::where('role', 'facilitator')->get();
            
            foreach ($facilitators as $facilitator) {
                Message::create([
                    'sender_id' => $admin->id,
                    'recipient_id' => $facilitator->id,
                    'subject' => $request->subject,
                    'body' => $request->body,
                ]);
            }

            return redirect()->route('admin.messages.index')
                ->with('success', "Message sent to all {$facilitators->count()} facilitators!");
        } else {
            // Send to individual facilitator
            Message::create([
                'sender_id' => $admin->id,
                'recipient_id' => $request->recipient_id,
                'subject' => $request->subject,
                'body' => $request->body,
            ]);

            $recipient = User::find($request->recipient_id);
            return redirect()->route('admin.messages.index')
                ->with('success', "Message sent to {$recipient->fullname}!");
        }
    }

    public function show(Message $message)
    {
        $message->load(['sender', 'recipient']);
        return view('admin.messages.show', compact('message'));
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('admin.messages.index')
            ->with('success', 'Message deleted successfully!');
    }
}

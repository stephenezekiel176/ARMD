<?php

namespace App\Http\Controllers\Facilitator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Personnel;
use Illuminate\Support\Facades\Auth;

class FacilitatorMessageController extends Controller
{
    /**
     * Store a message (group message to department or personal message to a user)
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string|max:5000',
            'recipient_id' => 'nullable|exists:users,id',
            'message_type' => 'required|in:individual,department',
        ]);

        $user = Auth::user();

        // Additional validation for individual messages
        if ($request->input('message_type') === 'individual') {
            $request->validate([
                'recipient_id' => 'required|exists:users,id',
            ]);
            
            // Verify recipient belongs to the same department
            $recipient = \App\Models\User::find($request->input('recipient_id'));
            if ($recipient->department_id !== $user->department_id) {
                return back()->withErrors(['recipient_id' => 'You can only send messages to personnel in your department.']);
            }
        }

        // Create the message
        $message = Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $request->input('message_type') === 'individual' ? $request->input('recipient_id') : null,
            'department_id' => $request->input('message_type') === 'department' ? $user->department_id : null,
            'subject' => $request->input('subject'),
            'body' => $request->input('body'),
        ]);

        $messageType = $request->input('message_type') === 'individual' ? 'individual' : 'department';
        $successMessage = $messageType === 'individual' 
            ? 'Message sent to personnel successfully.' 
            : 'Message sent to department successfully.';

        return redirect()->route('facilitator.messages.index')
            ->with('success', $successMessage);
    }
}

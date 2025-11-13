<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Setting;
use App\Mail\HelpRequestMail;

class HelpController extends Controller
{
    /**
     * Show the help form or admin settings
     */
    public function index()
    {
        // If user is admin (any admin role), show settings to configure help email
        if (auth()->check() && in_array(auth()->user()->role, ['admin', 'secondary_admin'])) {
            $settings = Setting::all()->keyBy('key');
            return view('help.admin-settings', compact('settings'));
        }
        
        // For regular users, show help form
        $helpEmail = Setting::getHelpEmail();
        return view('help.index', compact('helpEmail'));
    }

    /**
     * Send help request
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|min:10|max:1000',
        ]);

        // Only authenticated users can send messages
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to send help requests.');
        }

        $user = auth()->user();
        $helpEmail = Setting::getHelpEmail();

        try {
            // Send email to support
            Mail::to($helpEmail)->send(new HelpRequestMail($user, $request->message));

            return redirect()->back()->with('success', 'Your message has been sent successfully. We will get back to you soon!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send message. Please try again later.');
        }
    }

    /**
     * Update help email settings (admin only)
     */
    public function updateSettings(Request $request)
    {
        // Only admin (any admin role) can update settings
        if (!auth()->check() || !in_array(auth()->user()->role, ['admin', 'secondary_admin'])) {
            return redirect()->route('home')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        $request->validate([
            'help_email' => 'required|email',
        ]);

        try {
            Setting::updateOrCreate(
                ['key' => 'help_email'],
                [
                    'value' => $request->help_email,
                    'type' => 'email',
                    'description' => 'Email address for user support requests'
                ]
            );

            return redirect()->back()->with('success', 'Help email updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update help email. Please try again.');
        }
    }
}

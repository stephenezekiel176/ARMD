<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class AdminSettingsController extends Controller
{
    /**
     * Display settings page
     */
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'help_email' => 'required|email',
        ]);

        // Update help email
        Setting::set('help_email', $request->help_email, 'email', 'Email address for user support requests');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully!');
    }
}

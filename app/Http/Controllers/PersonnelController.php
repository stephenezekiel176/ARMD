<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class PersonnelController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $personnel = User::where('role', 'personnel')
            ->where('department_id', $user->department_id)
            ->with(['enrollments.course', 'submissions.assessment'])
            ->latest()
            ->paginate(15);

        return view('facilitator.personnel.index', compact('personnel'));
    }

    public function show(User $personnel)
    {
        // Ensure the personnel belongs to the facilitator's department
        if ($personnel->department_id !== auth()->user()->department_id || $personnel->role !== 'personnel') {
            abort(403, 'Unauthorized access to personnel from different department.');
        }

        $personnel->load(['enrollments.course', 'submissions' => function($query) {
            $query->latest()->with('assessment');
        }]);

        return view('facilitator.personnel.show', compact('personnel'));
    }
}

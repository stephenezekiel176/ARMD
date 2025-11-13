<?php

namespace App\Http\Controllers;

use App\Models\Training;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PersonnelTrainingController extends Controller
{
    public function index()
    {
        // Get all company-wide trainings (admin-controlled)
        $trainings = Training::where('scope', 'company')
            ->where('status', 'published')
            ->with('department')
            ->latest()
            ->paginate(12);

        return view('personnel.trainings.index', compact('trainings'));
    }

    public function guidelines()
    {
        // Get all guidelines (admin-controlled)
        $guidelines = Training::where('scope', 'company')
            ->where('type', 'guideline')
            ->where('status', 'published')
            ->with('department')
            ->latest()
            ->paginate(12);

        return view('personnel.trainings.guidelines', compact('guidelines'));
    }

    public function seminars()
    {
        // Get all seminars (admin-controlled)
        $seminars = Training::where('scope', 'company')
            ->where('type', 'seminar')
            ->where('status', 'published')
            ->with('department')
            ->latest()
            ->paginate(12);

        return view('personnel.trainings.seminars', compact('seminars'));
    }

    public function show(Training $training)
    {
        // Ensure training is published and company-wide
        if ($training->status !== 'published' || $training->scope !== 'company') {
            abort(404);
        }

        $training->load('department');

        return view('personnel.trainings.show', compact('training'));
    }

    public function getFile(Training $training)
    {
        // Ensure training is published and company-wide
        if ($training->status !== 'published' || $training->scope !== 'company') {
            abort(404);
        }

        if (!$training->file_path) {
            abort(404);
        }

        $filePath = storage_path('app/public/' . $training->file_path);
        
        if (!file_exists($filePath)) {
            abort(404);
        }

        return response()->download($filePath, $training->title . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }
}

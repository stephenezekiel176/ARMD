<?php

namespace App\Http\Controllers;

use App\Models\Workshop;
use Illuminate\Http\Request;

class WorkshopController extends Controller
{
    public function index()
    {
        $upcomingWorkshops = Workshop::upcoming()->latest('workshop_date')->get();
        $completedWorkshops = Workshop::completed()->latest('workshop_date')->paginate(9);

        return view('pages.training.workshops', compact('upcomingWorkshops', 'completedWorkshops'));
    }

    public function show(Workshop $workshop)
    {
        return view('pages.training.workshop-details', compact('workshop'));
    }
}

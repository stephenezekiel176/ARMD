<?php

namespace App\Http\Controllers;

use App\Models\Symposium;
use Illuminate\Http\Request;

class SymposiumController extends Controller
{
    public function index()
    {
        $upcomingSymposiums = Symposium::upcoming()->latest('symposium_date')->get();
        $completedSymposiums = Symposium::completed()->latest('symposium_date')->paginate(9);

        return view('pages.training.symposium', compact('upcomingSymposiums', 'completedSymposiums'));
    }

    public function show(Symposium $symposium)
    {
        return view('pages.training.symposium-details', compact('symposium'));
    }
}

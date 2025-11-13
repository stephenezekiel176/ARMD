<?php

namespace App\Http\Controllers;

class TrainingController extends Controller
{
    public function index()
    {
        return view('trainings.index');
    }
}

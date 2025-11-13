<?php

namespace App\Http\Controllers;

use App\Models\PracticalGuide;
use Illuminate\Http\Request;

class PracticalGuideController extends Controller
{
    public function index(Request $request)
    {
        $query = PracticalGuide::with('author')->published();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('difficulty')) {
            $query->where('difficulty_level', $request->difficulty);
        }

        $guides = $query->latest('published_at')->paginate(12);
        $categories = PracticalGuide::published()->distinct('category')->pluck('category');

        return view('pages.training.practical-guides', compact('guides', 'categories'));
    }

    public function show(PracticalGuide $guide)
    {
        if (!$guide->isPublished()) {
            abort(404);
        }

        $guide->incrementViews();

        $relatedGuides = PracticalGuide::published()
            ->where('id', '!=', $guide->id)
            ->where('category', $guide->category)
            ->latest('published_at')
            ->limit(3)
            ->get();

        return view('pages.training.guide-details', compact('guide', 'relatedGuides'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\MagazineIssue;
use Illuminate\Http\Request;

class MagazineController extends Controller
{
    public function index(Request $request)
    {
        $query = MagazineIssue::with('editor')->published();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $issues = $query->latest('issue_date')->paginate(9);

        return view('pages.magazine.index', compact('issues'));
    }

    public function show(MagazineIssue $magazine)
    {
        if ($magazine->status !== 'published') {
            abort(404);
        }

        $magazine->increment('downloads');

        $relatedIssues = MagazineIssue::published()
            ->where('id', '!=', $magazine->id)
            ->latest('issue_date')
            ->limit(3)
            ->get();

        return view('pages.magazine.show', compact('magazine', 'relatedIssues'));
    }

    public function currentIssue()
    {
        $currentIssue = MagazineIssue::published()
            ->latest('issue_date')
            ->first();

        if (!$currentIssue) {
            return view('pages.magazine.current-issue', ['currentIssue' => null]);
        }

        return view('pages.magazine.current-issue', compact('currentIssue'));
    }

    public function archives(Request $request)
    {
        $year = $request->input('year', date('Y'));

        $issues = MagazineIssue::published()
            ->whereYear('issue_date', $year)
            ->orderBy('issue_date', 'desc')
            ->get()
            ->groupBy(function($issue) {
                return $issue->issue_date->format('F');
            });

        $years = MagazineIssue::published()
            ->selectRaw('YEAR(issue_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('pages.magazine.archives', compact('issues', 'years', 'year'));
    }

    public function companyStories()
    {
        $stories = MagazineIssue::published()
            ->where('type', 'internal')
            ->orWhere('type', 'both')
            ->latest('issue_date')
            ->paginate(12);

        return view('pages.magazine.company-stories', compact('stories'));
    }
}

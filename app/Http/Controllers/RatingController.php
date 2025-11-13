<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\User;
use App\Models\Course;
use App\Models\Assessment;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Get ratings given by the user
        $ratingsGiven = Rating::with('rated')
            ->where('rater_id', $user->id)
            ->latest()
            ->paginate(10, ['*'], 'given');

        // Get ratings received by the user
        $ratingsReceived = Rating::with('rater')
            ->where('rated_id', $user->id)
            ->approved()
            ->latest()
            ->paginate(10, ['*'], 'received');

        $stats = Rating::getUserRatingStats($user->id);

        return view('ratings.index', compact('ratingsGiven', 'ratingsReceived', 'stats'));
    }

    public function create(Request $request)
    {
        $ratedUser = User::findOrFail($request->rated_id);
        $course = $request->course_id ? Course::findOrFail($request->course_id) : null;
        $assessment = $request->assessment_id ? Assessment::findOrFail($request->assessment_id) : null;

        // Determine rating type
        $ratingType = auth()->user()->role === 'personnel' ? 'personnel_to_facilitator' : 'facilitator_to_personnel';

        return view('ratings.create', compact('ratedUser', 'course', 'assessment', 'ratingType'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'rated_id' => 'required|exists:users,id',
            'rating_type' => 'required|in:personnel_to_facilitator,facilitator_to_personnel',
            'overall_rating' => 'required|integer|min:1|max:5',
            'communication_rating' => 'nullable|integer|min:1|max:5',
            'knowledge_rating' => 'nullable|integer|min:1|max:5',
            'professionalism_rating' => 'nullable|integer|min:1|max:5',
            'responsiveness_rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'course_id' => 'nullable|exists:courses,id',
            'assessment_id' => 'nullable|exists:assessments,id',
            'is_anonymous' => 'nullable|boolean',
        ]);

        $validated['rater_id'] = auth()->id();
        $validated['is_anonymous'] = $request->has('is_anonymous');

        Rating::create($validated);

        return redirect()->route('ratings.index')->with('success', 'Rating submitted successfully.');
    }

    public function show(User $user)
    {
        $stats = Rating::getUserRatingStats($user->id);
        
        $recentRatings = Rating::approved()
            ->forUser($user->id)
            ->with('rater')
            ->latest()
            ->limit(20)
            ->get();

        return view('ratings.show', compact('user', 'stats', 'recentRatings'));
    }

    // Admin methods
    public function adminIndex(Request $request)
    {
        $query = Rating::with(['rater', 'rated'])->latest();

        if ($request->filled('is_approved')) {
            $query->where('is_approved', $request->is_approved);
        }

        if ($request->filled('rating_type')) {
            $query->where('rating_type', $request->rating_type);
        }

        $ratings = $query->paginate(20);

        return view('admin.ratings.index', compact('ratings'));
    }

    public function approve(Rating $rating)
    {
        $rating->approve();
        return back()->with('success', 'Rating approved successfully.');
    }

    public function reject(Rating $rating)
    {
        $rating->reject();
        return back()->with('success', 'Rating rejected successfully.');
    }

    public function destroy(Rating $rating)
    {
        $rating->delete();
        return back()->with('success', 'Rating deleted successfully.');
    }
}

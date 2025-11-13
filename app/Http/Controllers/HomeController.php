<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Cache the entire homepage data for 30 minutes
        $homeData = Cache::remember('homepage_data', 1800, function () {
            return [
                'featured' => Course::latest()->take(6)->get(),
                'popular' => Course::withCount('enrollments')
                    ->orderBy('enrollments_count', 'desc')
                    ->limit(10)
                    ->get(),
                'stats' => [
                    'total_users' => \App\Models\User::count(),
                    'total_courses' => Course::count(),
                    'total_enrollments' => class_exists('\App\Models\Enrollment') ? \App\Models\Enrollment::count() : 0,
                ],
            ];
        });

        // Return view with cache headers
        $response = response()->view('home', $homeData);
        $response->header('Cache-Control', 'public, max-age=1800');
        $response->header('Expires', gmdate('D, d M Y H:i:s', time() + 1800) . ' GMT');
        
        return $response;
    }
}

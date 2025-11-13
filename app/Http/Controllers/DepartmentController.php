<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use App\Models\Course;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount(['users', 'courses'])
            ->with(['users' => function($query) {
                $query->select('id', 'department_id', 'role', 'fullname', 'position')
                      ->where('role', 'facilitator')
                      ->where('role', '!=', 'admin') // Exclude system administrator
                      ->take(3);
            }])
            ->latest()
            ->get();

        // Map database fields to view expectations
        $departments->each(function ($department) {
            $department->code = $department->special_code;
            $department->overview = $department->description;
            $department->core_responsibilities = $department->functionalities;
            $department->impact = $department->impact_statement;
        });

        return view('departments.index', compact('departments'));
    }

    public function show(Department $department)
    {
        $department->load([
            'users' => function($query) {
                $query->select('id', 'department_id', 'role', 'fullname', 'email', 'position')
                      ->where('role', '!=', 'admin') // Exclude system administrator
                      ->withCount(['courses', 'enrollments', 'submissions']);
            },
            'courses' => function($query) {
                $query->withCount('enrollments')
                      ->latest()
                      ->take(5);
            }
        ]);

        // Map database fields to view expectations
        $department->code = $department->special_code;
        $department->overview = $department->description;
        $department->core_responsibilities = $department->functionalities;
        $department->impact = $department->impact_statement;

        return view('departments.show', compact('department'));
    }

    public function resources(Department $department)
    {
        $resources = Course::where('department_id', $department->id)
            ->with(['department', 'facilitator'])
            ->withCount('enrollments')
            ->latest()
            ->paginate(12);

        return view('departments.resources', compact('department', 'resources'));
    }
}

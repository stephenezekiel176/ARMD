<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Services\SvgSanitizer;

class AdminDepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::withCount(['users', 'courses'])->latest()->get();
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments,special_code|max:20',
            'overview' => 'nullable|string|max:2000',
            'slogan' => 'nullable|string|max:255',
            'core_responsibilities' => 'nullable|string|max:2000',
            'impact' => 'nullable|string|max:2000',
            'head_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'code', 'overview', 'slogan', 'core_responsibilities', 'impact']);

        // Map form fields to database fields
        $mappedData = [
            'name' => $data['name'],
            'special_code' => $data['code'],
            'description' => $data['overview'] ?? null,
            'slogan' => $data['slogan'] ?? null,
            'functionalities' => $data['core_responsibilities'] ?? null,
            'impact_statement' => $data['impact'] ?? null,
        ];

        // Handle department head image upload
        if ($request->hasFile('head_image')) {
            $file = $request->file('head_image');
            if ($file->isValid()) {
                $path = $file->store('public/department-heads');
                $mappedData['head_image'] = str_replace('public/', '', $path);
            }
        }

        // Handle icon upload or raw svg
        if ($request->hasFile('icon_file')) {
            $file = $request->file('icon_file');
            if ($file->isValid() && Str::lower($file->getClientOriginalExtension()) === 'svg') {
                $path = $file->store('public/department-icons');
                $mappedData['icon'] = Storage::url($path);
            }
        } elseif ($request->filled('icon_svg')) {
            // sanitize pasted svg
            $svg = $request->input('icon_svg');
            $mappedData['icon'] = SvgSanitizer::sanitize($svg);
        }

        Department::create($mappedData);
        return redirect()->route('admin.departments.index')->with('success', 'Department created successfully!');
    }

    public function edit(Department $department)
    {
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|unique:departments,special_code,' . $department->id . '|max:20',
            'overview' => 'nullable|string|max:2000',
            'slogan' => 'nullable|string|max:255',
            'core_responsibilities' => 'nullable|string|max:2000',
            'impact' => 'nullable|string|max:2000',
            'head_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'code', 'overview', 'slogan', 'core_responsibilities', 'impact']);

        // Map form fields to database fields
        $mappedData = [
            'name' => $data['name'],
            'special_code' => $data['code'],
            'description' => $data['overview'] ?? null,
            'slogan' => $data['slogan'] ?? null,
            'functionalities' => $data['core_responsibilities'] ?? null,
            'impact_statement' => $data['impact'] ?? null,
        ];

        // Handle department head image upload
        if ($request->hasFile('head_image')) {
            // Delete old image if exists
            if ($department->head_image) {
                Storage::delete('public/' . $department->head_image);
            }
            
            $file = $request->file('head_image');
            if ($file->isValid()) {
                $path = $file->store('public/department-heads');
                $mappedData['head_image'] = str_replace('public/', '', $path);
            }
        }

        // Handle icon upload or raw svg
        if ($request->hasFile('icon_file')) {
            $file = $request->file('icon_file');
            if ($file->isValid() && Str::lower($file->getClientOriginalExtension()) === 'svg') {
                $path = $file->store('public/department-icons');
                $mappedData['icon'] = Storage::url($path);
            }
        } elseif ($request->filled('icon_svg')) {
            $svg = $request->input('icon_svg');
            $mappedData['icon'] = SvgSanitizer::sanitize($svg);
        }

        $department->update($mappedData);
        return redirect()->route('admin.departments.index')->with('success', 'Department updated successfully!');
    }

    public function destroy(Department $department)
    {
        // Delete department head image if exists
        if ($department->head_image) {
            Storage::delete('public/' . $department->head_image);
        }

        // Delete icon file if it's a storage url
        if ($department->icon && Str::startsWith($department->icon, '/storage/')) {
            $file = str_replace('/storage/', 'public/', $department->icon);
            Storage::delete($file);
        }

        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Department deleted successfully!');
    }
}

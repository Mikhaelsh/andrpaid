<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class FindController extends Controller
{
    public function index(Request $request)
    {
        // 1. Start Query with Eager Loading
        $query = Lecturer::with(['user', 'province', 'affiliation.university.user', 'papers']);

        // 2. Search Logic (Name or Research Interests)
        if ($request->filled('q')) {
            $search = $request->q;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%");
            });
            // Optional: Add logic here to search research interests if you have that column/relation
        }

        // 3. Filter by Region (Province)
        if ($request->filled('region')) {
            $query->where('province_id', $request->region);
        }

        // 4. Sorting Logic
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'name_asc':
                    // Sorting by related user name requires a join or closure sort
                    // Simple approach:
                    $query->join('users', 'lecturers.user_id', '=', 'users.id')
                          ->orderBy('users.name', 'asc')
                          ->select('lecturers.*'); // Avoid column collision
                    break;
                default:
                    // Default relevance/id
                    $query->orderBy('id', 'desc');
            }
        } else {
            $query->latest(); // Default
        }

        $lecturers = $query->paginate(12)->withQueryString(); // Persist filters in pagination links

        // 5. Fetch Provinces for the Filter Dropdown
        $provinces = Province::orderBy('name')->get();

        return view('pages.find', [
            'lecturers' => $lecturers,
            'provinces' => $provinces,
            'navbarProfileData' => Auth::user(),
            'user' => Auth::user()
        ]);
    }
}
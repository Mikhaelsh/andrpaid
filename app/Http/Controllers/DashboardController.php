<?php

namespace App\Http\Controllers;

use App\Models\Affiliation;
use App\Models\CollaborationRequest;
use App\Models\Lecturer;
use App\Models\Paper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index($profileId)
    {
        $user = User::where("profileId", $profileId)->firstOrFail();

        // Common Data
        $data = [
            'user' => $user,
            'navbarProfileData' => $user,
            'messageCount' => 5,    // Mock
            'unreadMessages' => 2,  // Mock
            'citations' => 0,       // Mock default
        ];

        // ==========================================
        // SCENARIO A: USER IS A UNIVERSITY
        // ==========================================
        if ($user->university) {
            $university = $user->university;
            
            // 1. STATS
            // Count approved lecturers
            $approvedLecturerIds = Affiliation::where('university_id', $university->id)
                ->where('status', 'accepted')
                ->pluck('lecturer_id');
            
            $lecturerCount = $approvedLecturerIds->count();

            // Active Projects: Papers owned by my lecturers
            $activeProjectsCount = Paper::whereIn('lecturer_id', $approvedLecturerIds)->count();

            // Pending Requests: Lecturers asking to join this university
            $pendingRequestsCount = Affiliation::where('university_id', $university->id)
                ->where('status', 'pending')
                ->count();

            // 2. RECENT ACTIVITY LIST
            // Show latest papers published by this university's lecturers
            $activePapers = Paper::with(['lecturer.user', 'paperType'])
                ->whereIn('lecturer_id', $approvedLecturerIds)
                ->latest('created_at')
                ->take(5)
                ->get();

            // 3. RECOMMENDATIONS (Other Universities to partner with?)
            $recommendations = User::whereHas('university')
                ->where('id', '!=', $user->id)
                ->inRandomOrder()
                ->take(3)
                ->get();

            // Merge into data
            $data['isUniversity'] = true;
            $data['activeProjectsCount'] = $activeProjectsCount;
            $data['pendingRequestsCount'] = $pendingRequestsCount;
            $data['lecturerCount'] = $lecturerCount; // Specific to Uni
            $data['activePapers'] = $activePapers;
            $data['recommendations'] = $recommendations; // Pass as users or lecturers depending on view
            $data['citations'] = 8500; // Mock aggregate for Uni
        }

        // ==========================================
        // SCENARIO B: USER IS A LECTURER
        // ==========================================
        elseif ($user->lecturer) {
            $lecturer = $user->lecturer;

            // 1. STATS
            $activeProjectsCount = Paper::where('lecturer_id', $lecturer->id)
                ->orWhereHas('collaborations', function ($q) use ($lecturer) {
                    $q->where('lecturer_id', $lecturer->id);
                })->count();

            $pendingRequestsCount = CollaborationRequest::where('to_lecturer_id', $lecturer->id)
                ->where('status', 'pending')
                ->count();

            // 2. ACTIVE COLLABORATIONS LIST
            $activePapers = Paper::with(['lecturer.user', 'paperType'])
                ->where('lecturer_id', $lecturer->id)
                ->orWhereHas('collaborations', function ($q) use ($lecturer) {
                    $q->where('lecturer_id', $lecturer->id);
                })
                ->latest('updated_at')
                ->take(5)
                ->get();

            // 3. RECOMMENDATIONS (Other Lecturers)
            $recommendations = Lecturer::with(['user', 'affiliation.university.user'])
                ->where('id', '!=', $lecturer->id)
                ->inRandomOrder()
                ->take(3)
                ->get();

            // Merge into data
            $data['isUniversity'] = false;
            $data['activeProjectsCount'] = $activeProjectsCount;
            $data['pendingRequestsCount'] = $pendingRequestsCount;
            $data['activePapers'] = $activePapers;
            $data['recommendations'] = $recommendations;
            $data['citations'] = 1240; // Mock for Lecturer
        } 
        
        else {
            // Fallback for Admin or unknown roles
            abort(403, 'Unauthorized role');
        }

        return view('pages.dashboard', $data);
    }
}
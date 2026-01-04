<?php

namespace App\Http\Controllers;

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
        $lecturer = $user->lecturer;

        // 1. STATS DATA
        // Active Projects: Owned papers + Papers where I am a collaborator
        $activeProjectsCount = Paper::where('lecturer_id', $lecturer->id)
            ->orWhereHas('collaborations', function ($q) use ($lecturer) {
                $q->where('lecturer_id', $lecturer->id);
            })->count();

        // Pending Requests (Incoming invitations)
        $pendingRequestsCount = CollaborationRequest::where('to_lecturer_id', $lecturer->id)
            ->where('status', 'pending')
            ->count();

        // Messages & Citations (Mocked for now as we don't have these tables yet)
        $messageCount = 5;
        $unreadMessages = 2;
        $citations = 1240;

        // 2. ACTIVE COLLABORATIONS LIST
        // Fetch papers to display in the list
        $activePapers = Paper::with(['lecturer.user', 'paperType'])
            ->where('lecturer_id', $lecturer->id)
            ->orWhereHas('collaborations', function ($q) use ($lecturer) {
                $q->where('lecturer_id', $lecturer->id);
            })
            ->latest('updated_at')
            ->take(5)
            ->get();

        // 3. RECOMMENDATIONS
        $recommendations = Lecturer::with([
                'user', 
                'affiliation.university.user'
            ])
            ->where('id', '!=', $lecturer->id)
            ->inRandomOrder()
            ->take(3)
            ->get();

        return view('pages.dashboard', [
            'user' => $user,
            'navbarProfileData' => $user, 
            'activeProjectsCount' => $activeProjectsCount,
            'pendingRequestsCount' => $pendingRequestsCount,
            'messageCount' => $messageCount,
            'unreadMessages' => $unreadMessages,
            'citations' => $citations,
            'activePapers' => $activePapers,
            'recommendations' => $recommendations
        ]);
    }
}
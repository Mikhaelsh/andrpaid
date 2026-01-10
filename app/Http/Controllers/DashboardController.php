<?php

namespace App\Http\Controllers;

use App\Models\Affiliation;
use App\Models\CollaborationRequest;
use App\Models\Inbox;
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

        $messageCount = Inbox::where('to_user_id', $user->id)->count();

        $unreadMessages = Inbox::where('to_user_id', $user->id)
                            ->where('marked_read', false)
                            ->count();

        // Base Data
        $data = [
            'user' => $user,
            'navbarProfileData' => $user,
            'messageCount' => $messageCount,
            'unreadMessages' => $unreadMessages,
        ];

        if ($user->university) {
            $university = $user->university;

            $approvedLecturerIds = Affiliation::where('university_id', $university->id)
                ->where('status', 'accepted')
                ->pluck('lecturer_id');

            $data['lecturerCount'] = $approvedLecturerIds->count();

            $data['activeProjectsCount'] = Paper::whereIn('lecturer_id', $approvedLecturerIds)->count();

            $data['pendingRequestsCount'] = Affiliation::where('university_id', $university->id)
                ->where('status', 'pending')
                ->count();

            $data['activePapers'] = Paper::with(['lecturer.user', 'paperType'])
                ->whereIn('lecturer_id', $approvedLecturerIds)
                ->latest('updated_at')
                ->take(5)
                ->get();

            $data['totalStars'] = Paper::whereIn('lecturer_id', $approvedLecturerIds)
                ->withCount('paperStars')
                ->get()
                ->sum('paper_stars_count');

            $data['recommendations'] = User::whereHas('university')
                ->where('id', '!=', $user->id)
                ->inRandomOrder()
                ->take(3)
                ->get();

            $data['isUniversity'] = true;
        } elseif ($user->lecturer) {
            $lecturer = $user->lecturer;

            $data['activeProjectsCount'] = Paper::where('lecturer_id', $lecturer->id)
                ->orWhereHas('collaborations', fn($q) => $q->where('lecturer_id', $lecturer->id))
                ->count();

            $data['pendingRequestsCount'] = CollaborationRequest::where('to_lecturer_id', $lecturer->id)
                ->where('status', 'pending')
                ->count();

            $data['activePapers'] = Paper::with(['lecturer.user', 'paperType'])
                ->where('lecturer_id', $lecturer->id)
                ->orWhereHas('collaborations', fn($q) => $q->where('lecturer_id', $lecturer->id))
                ->latest('updated_at')
                ->take(5)
                ->get();

            $data['totalStars'] = Paper::where('lecturer_id', $lecturer->id)
                ->withCount('paperStars')
                ->get()
                ->sum('paper_stars_count');

            $data['recommendations'] = Lecturer::with(['user', 'affiliation.university.user'])
                ->where('id', '!=', $lecturer->id)
                ->inRandomOrder()
                ->take(3)
                ->get();

            $data['isUniversity'] = false;
        } else if($user->isAdmin()){
            return redirect("/admin-panel");
        } else {
            abort(403, 'Unauthorized role');
        }

        return view('pages.dashboard', $data);
    }
}

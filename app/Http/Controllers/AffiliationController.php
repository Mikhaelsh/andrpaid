<?php

namespace App\Http\Controllers;

use App\Models\Affiliation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->university) {
            abort(403, 'Only universities can manage affiliations.');
        }

        $universityId = $user->university->id;

        $stats = [
            'total_lecturers' => Affiliation::where('university_id', $universityId)->where('status', 'verified')->count(),
            'pending_requests' => Affiliation::where('university_id', $universityId)->where('status', 'pending')->count(),
            'rejected_requests' => Affiliation::where('university_id', $universityId)->where('status', 'rejected')->count(),
        ];

        $pendingRequests = Affiliation::with('lecturer.user')
            ->where('university_id', $universityId)
            ->where('status', 'pending')
            ->latest()
            ->get();

        $activeLecturers = Affiliation::with('lecturer.user')
            ->where('university_id', $universityId)
            ->where('status', 'verified')
            ->latest('updated_at')
            ->paginate(10);

        $navbarProfileData = ProfileController::getNavbarProfileUniversityData(Auth::user()->profileId);

        return view('pages.affiliations', compact('user', 'stats', 'pendingRequests', 'activeLecturers', 'navbarProfileData'));
    }

    public function acceptRequest(Request $request)
    {
        $affiliation = Affiliation::findOrFail($request->affiliation_id);

        if($affiliation->university_id !== Auth::user()->university->id) {
            abort(403);
        }

        $affiliation->status = 'verified';
        $affiliation->save();

        return back()->with('success', 'Lecturer affiliation verified successfully.');
    }

    public function rejectRequest(Request $request)
    {
        $affiliation = Affiliation::findOrFail($request->affiliation_id);

        if($affiliation->university_id !== Auth::user()->university->id) {
            abort(403);
        }

        $affiliation->status = 'rejected';
        $affiliation->rejection_reason = $request->reason;
        $affiliation->save();

        return back()->with('success', 'Request rejected.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function indexOverview($profileId){
        $user = User::where("profileId", $profileId)
            ->with(['lecturer.affiliation.university.user', 'lecturer.researchFields'])
            ->firstOrFail();

        $topPapers = [];
        $collabPapers = [];

        if ($user->lecturer) {
            $topPapers = $user->lecturer->papers()
                ->withCount('paperStars')
                ->orderByDesc('paper_stars_count')
                ->take(3)
                ->with(['paperType', 'researchFields'])
                ->get();

            // 3. Get Open Collaborations
            $collabPapers = $user->lecturer->papers()
                ->where('openCollaboration', true)
                ->latest()
                ->take(3)
                ->with(['paperType', 'researchFields'])
                ->get();
        }

        return view("pages.profile-overview", [
            "user" => $user,
            "topPapers" => $topPapers,
            "collabPapers" => $collabPapers,
            "navbarProfileData" => ProfileController::getNavbarProfileData($profileId),
        ]);
    }

    public static function getNavbarProfileData($profileId){
        $user = User::where("profileId", $profileId)->first();

        $lecturer = $user->lecturer;

        $papers = $lecturer->papers;

        $stars = $user->paperStars;

        return [
            "profileId" => $profileId,
            "papersCount" => $papers ? $papers->count() : 0,
            "starsCount" => $stars ? $stars->count() : 0,
        ];
    }
}

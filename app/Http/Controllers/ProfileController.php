<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Paper;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function indexOverview($profileId){
        $user = User::where("profileId", $profileId)
            ->with(['lecturer.affiliation.university.user', 'lecturer.researchFields'])
            ->firstOrFail();


        if ($user->lecturer) {
            $topPapers = [];
            $collabPapers = [];

            $topPapers = $user->lecturer->papers()
                ->withCount('paperStars')
                ->orderByDesc('paper_stars_count')
                ->take(3)
                ->with(['paperType', 'researchFields'])
                ->get();

            $collabPapers = $user->lecturer->papers()
                ->where('openCollaboration', true)
                ->latest()
                ->take(3)
                ->with(['paperType', 'researchFields'])
                ->get();

            return view("pages.profile-overview", [
                "user" => $user,
                "topPapers" => $topPapers,
                "collabPapers" => $collabPapers,
                "navbarProfileData" => ProfileController::getNavbarProfileLecturerData($profileId),
            ]);
        } else{
            $recentUnivPapers = Paper::whereHas('lecturer.affiliation', function ($q) use ($user){
                $q->where('university_id', $user->university->id);
            })->latest()->take(5)->with('lecturer.user')->get();

            return view("pages.profile-overview", [
                "user" => $user,
                "recentUnivPapers" => $recentUnivPapers,
                "navbarProfileData" => ProfileController::getNavbarProfileUniversityData($profileId),
            ]);
        }
    }

    public function indexResearchers($profileId){
        $user = User::where("profileId", $profileId)
            ->with(['lecturer.affiliation.university.user', 'lecturer.researchFields'])
            ->firstOrFail();

        $universityId = $user->university->id;

        $researchers = Lecturer::whereHas('affiliation', function($q) use ($universityId) {
            $q->where('university_id', $universityId);
        })->with(['user', 'province', 'researchFields'])->get();

        return view("pages.researchers", [
            "user" => $user,
            "researchers" => $researchers,
            "navbarProfileData" => ProfileController::getNavbarProfileUniversityData($profileId),
        ]);

    }

    public static function getNavbarProfileLecturerData($profileId){
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

    public static function getNavbarProfileUniversityData($profileId){
        $user = User::where("profileId", $profileId)->first();

        $universityId = $user->university->id;

        $researchers = Lecturer::whereHas('affiliation', function ($q) use ($universityId) {
            $q->where('university_id', $universityId);
        });

        $papers = Paper::whereHas('lecturer.affiliation', function ($q) use ($universityId) {
            $q->where('university_id', $universityId);
        });

        return [
            "profileId" => $profileId,
            "papersCount" => $papers->count(),
            "researchersCount" => $researchers->count(),
        ];
    }
}

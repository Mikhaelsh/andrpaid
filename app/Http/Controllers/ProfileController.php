<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function indexOverview($profileId){

        return view("pages.profile-overview", [
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

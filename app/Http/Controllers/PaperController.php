<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use App\Models\Paper;
use App\Models\PaperStar;
use App\Models\PaperType;
use App\Models\ResearchField;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaperController extends Controller
{
    public function indexPapers($profileId){
        $user = User::where("profileId",$profileId)->first();

        $lecturer = Lecturer::where("user_id",$user->id)->first();

        $papers = Paper::where("lecturer_id",$lecturer->id)->latest()->get();

        $paperTypes = PaperType::all();

        $researchFields = ResearchField::all();

        return view("pages.papers", [
            "navbarProfileData" => ProfileController::getNavbarProfileData($profileId),
            "user" => $user,
            "papers" => $papers,
            "paperTypes"=> $paperTypes,
            "researchFields"=> $researchFields,
        ]);
    }

    public function indexCreatePaper(){
        $researchFields = ResearchField::all();
        $paperTypes = PaperType::all();

        return view("pages.papers-create", [
            "researchFields" => $researchFields,
            "paperTypes" => $paperTypes,
        ]);
    }

    public function createNewPaper(Request $request){
        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'paperType'      => 'required|string|exists:paper_types,paperTypeId',
            'category_ids'   => 'required|array|min:1|max:3',
            'category_ids.*' => 'distinct|exists:research_fields,researchFieldId',
            'visibility'     => 'required|in:public,private',
        ]);

        $user = Auth::user();
        $lecturer = $user->lecturer;

        $paperType = PaperType::where("paperTypeId", $validated["paperType"])->first();

        $paper = Paper::create([
            "title" => $validated["title"],
            "description" => $validated["description"],
            "visibility" => $validated["visibility"],
            "lecturer_id" => $lecturer->id,
            "paper_type_id" => $paperType->id
        ]);

        $fieldIds = ResearchField::whereIn('researchFieldId', $validated['category_ids'])->pluck('id');
        $paper->researchFields()->attach($fieldIds);

        return redirect("/" . $user->profileId . "/papers")->with('successNewPaper', 'Your new paper has been created successfully!');
    }

    public function toggleStar($paperId){
        $user = Auth::user();
        $paper = Paper::where("paperId", $paperId)->first();

        $paperStar = PaperStar::where([
            ['user_id', '=', $user->id],
            ['paper_id', '=', $paper->id]
        ])->first();

        if($paperStar){
            PaperStar::where([
                ['user_id', '=', $user->id],
                ['paper_id', '=', $paper->id]
            ])->delete();
            $isStarred = false;
        } else{
            PaperStar::create([
                "user_id" => $user->id,
                "paper_id"=> $paper->id
            ]);
            $isStarred = true;
        }

        $newCount = PaperStar::where('paper_id', $paper->id)->count();

        return response()->json([
            'is_starred' => $isStarred,
            'new_count' => $newCount,
        ]);
    }

    public function indexStars($profileId){
        $user = User::where("profileId",$profileId)->first();
        $user->load('paperStars.paper');

        $papers = $user->paperStars->pluck('paper');

        return view("pages.stars", [
            "navbarProfileData" => ProfileController::getNavbarProfileData($profileId),
            "user" => $user,
            "papers" => $papers,
        ]);
    }
}

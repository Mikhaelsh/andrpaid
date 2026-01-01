<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\PaperStar;
use App\Models\PaperType;
use App\Models\ResearchField;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaperController extends Controller
{
    public function indexPapers(Request $request, $profileId){
        $user = User::where("profileId", $profileId)->with(['lecturer', 'university'])->firstOrFail();

        if ($user->university) {
            $universityId = $user->university->id;

            $query = Paper::whereHas('lecturer.affiliation', function ($q) use ($universityId) {
                $q->where('university_id', $universityId);
            });

            $navbarProfileData = ProfileController::getNavbarProfileUniversityData($profileId);

        } elseif ($user->lecturer) {
            $query = Paper::where("lecturer_id", $user->lecturer->id);

            $navbarProfileData = ProfileController::getNavbarProfileLecturerData($profileId);

        }


        if ($request->filled('status')) {
            $query->whereIn('status', $request->status);
        }

        if ($request->filled('visibility')) {
            $query->whereIn('visibility', $request->visibility);
        }

        if ($request->filled('collab')) {
            $query->whereIn('openCollaboration', $request->collab);
        }

        if ($request->filled('paper_type_id')) {
            $query->whereHas('paperType', function ($q) use ($request) {
                $q->whereIn('paperTypeId', $request->paper_type_id);
            });
        }

        if ($request->filled('research_field_id')) {
            $query->whereHas('researchFields', function ($q) use ($request) {
                $q->whereIn('researchFieldId', $request->research_field_id);
            });
        }

        $sort = $request->input('sort', 'newest');

        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'stars':
                $query->withCount('paperStars')->orderByDesc('paper_stars_count');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $papers = $query->with(['paperType', 'researchFields', 'paperStars', 'lecturer.user'])->get();

        $paperTypes = PaperType::all();
        $researchFields = ResearchField::all();

        return view("pages.papers", [
            "navbarProfileData" => $navbarProfileData,
            "user" => $user,
            "papers" => $papers,
            "paperTypes" => $paperTypes,
            "researchFields" => $researchFields,
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

        return redirect("/" . $user->profileId . "/papers")->with('success', 'Your new paper has been created successfully!');
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

    public function indexStars(Request $request, $profileId){
        $user = User::where("profileId", $profileId)->firstOrFail();

        $query = Paper::whereHas('paperStars', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        });

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Status
        if ($request->filled('status')) {
            $query->whereIn('status', (array) $request->status);
        }

        // Visibility
        if ($request->filled('visibility')) {
            $query->whereIn('visibility', (array) $request->visibility);
        }

        // Open Collaboration
        if ($request->filled('collab')) {
            $query->whereIn('openCollaboration', (array) $request->collab);
        }

        // Paper Type
        if ($request->filled('paper_type_id')) {
            $query->whereHas('paperType', function ($q) use ($request) {
                $q->whereIn('paperTypeId', (array) $request->paper_type_id);
            });
        }

        // Research Field
        if ($request->filled('research_field_id')) {
            $query->whereHas('researchFields', function ($q) use ($request) {
                $q->whereIn('researchFieldId', (array) $request->research_field_id);
            });
        }

        // Sorting
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->oldest();
                break;
            case 'stars':
                $query->withCount('paperStars')->orderByDesc('paper_stars_count');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $papers = $query->with(['paperType', 'researchFields', 'paperStars', 'lecturer.user'])->get();

        $paperTypes = PaperType::all();
        $researchFields = ResearchField::all();

        return view("pages.stars", [
            "navbarProfileData" => ProfileController::getNavbarProfileLecturerData($profileId),
            "user" => $user,
            "papers" => $papers,
            "paperTypes" => $paperTypes,
            "researchFields" => $researchFields,
        ]);
    }

    public function paperOverview($profileId, $paperId){
        $user = User::where("profileId", $profileId)->first();

        $paper = $user->lecturer->papers->where("paperId", $paperId)->first();

        return view("pages.paper", [
            "user" => $user,
            "paper" => $paper,
        ]);
    }

    public function paperWorkspace($profileId, $paperId){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        return view('pages.paper-workspace', [
            'user' => $user,
            'paper' => $paper
        ]);
    }
}

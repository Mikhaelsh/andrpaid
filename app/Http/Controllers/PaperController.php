<?php

namespace App\Http\Controllers;

use App\Models\Collaboration;
use App\Models\Paper;
use App\Models\PaperReference;
use App\Models\PaperStar;
use App\Models\PaperType;
use App\Models\ResearchField;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    public function LitReview($profileId, $paperId)
    {
        $user = User::where("profileId", $profileId)->firstOrFail();

        $paper = Paper::where('paperId', $paperId)->firstOrFail();

        return view('pages.literature-review', [
            'user' => $user,
            'paper' => $paper
        ]);
    }

    private function authorizeEditor($paper)
    {
        $user = Auth::user();

        // 1. Ensure user has a lecturer profile (since papers are owned/edited by lecturers)
        if (!$user->lecturer) {
            abort(403, 'Only lecturers can edit papers.');
        }

        // 2. Check if User is the Owner
        $isOwner = $paper->lecturer_id === $user->lecturer->id;

        // 3. Check if User is an Assigned Collaborator
        $isCollaborator = Collaboration::where('paper_id', $paper->id)
            ->where('lecturer_id', $user->lecturer->id)
            ->exists();

        // 4. Deny if neither
        if (!$isOwner && !$isCollaborator) {
            abort(403, 'You do not have permission to edit this workspace.');
        }
    }

    public function paperLitReview($profileId, $paperId)
    {
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->firstOrFail();

        // 1. Calculate Permissions
        $currentUser = Auth::user();
        $canEdit = false;

        if ($currentUser && $currentUser->lecturer) {
            $isOwner = $paper->lecturer_id === $currentUser->lecturer->id;
            
            // Check if they are a collaborator
            $isCollaborator = Collaboration::where('paper_id', $paper->id)
                ->where('lecturer_id', $currentUser->lecturer->id)
                ->exists();

            if ($isOwner || $isCollaborator) {
                $canEdit = true;
            }
        }

        // 2. Pass $canEdit to the view
        return view('pages.literature-review', [
            'user' => $user,
            'paper' => $paper,
            'canEdit' => $canEdit // <--- THIS LINE WAS MISSING
        ]);
    }

    public function addReference(Request $request, $profileId, $paperId)
    {
        $paper = Paper::where('paperId', $paperId)->firstOrFail();

        // SECURITY CHECK
        $this->authorizeEditor($paper);

        $validated = $request->validate([
            'title' => 'required|string',
            'author' => 'required|string',
            'year' => 'required|integer',
            'journal' => 'nullable|string',
            'url' => 'nullable|url',
            'key_points' => 'nullable|json',
            'is_analyzed' => 'nullable'
        ]);

        $newRef = [
            'id' => uniqid(),
            'title' => $validated['title'],
            'author' => $validated['author'],
            'year' => $validated['year'],
            'publication' => $validated['journal'] ?? '',
            'url' => $validated['url'] ?? '',
            'key_points' => json_decode($validated['key_points'] ?? '[]'),
            'is_analyzed' => $request->has('is_analyzed'),
            'added_by' => Auth::user()->name, 
            'created_at' => now()->toDateTimeString()
        ];

        $currentRefs = $paper->references_data ?? [];
        $currentRefs[] = $newRef;
        
        $paper->references_data = $currentRefs;
        $paper->save();

        return back()->with('success', 'Reference added successfully.');
    }

    public function saveSynthesis(Request $request, $profileId, $paperId)
    {
        $paper = Paper::where('paperId', $paperId)->firstOrFail();

        // SECURITY CHECK
        $this->authorizeEditor($paper);
        
        $paper->synthesis_text = $request->input('synthesis_text');
        $paper->save();

        return back()->with('success', 'Synthesis draft saved successfully.');
    }

    public function exportBibtex($profileId, $paperId)
    {
        $paper = Paper::where('paperId', $paperId)->firstOrFail();
        $references = $paper->references_data ?? [];

        if (empty($references)) {
            return back()->with('error', 'No references to export.');
        }

        $bibtex = "";

        foreach ($references as $ref) {
            // Create a unique citation key (e.g., AuthorYear)
            $lastname = explode(' ', trim($ref['author']))[0];
            $citKey = Str::slug($lastname) . $ref['year'];

            $bibtex .= "@article{{$citKey},\n";
            $bibtex .= "  title = {{$ref['title']}},\n";
            $bibtex .= "  author = {{$ref['author']}},\n";
            $bibtex .= "  journal = {{$ref['publication']}},\n";
            $bibtex .= "  year = {{$ref['year']}},\n";
            if (!empty($ref['url'])) {
                $bibtex .= "  url = {{$ref['url']}},\n";
            }
            $bibtex .= "}\n\n";
        }

        $filename = Str::slug($paper->title) . '-references.bib';

        return response($bibtex)
            ->header('Content-Type', 'application/x-bibtex')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    public function addTheme(Request $request, $profileId, $paperId)
    {
        $paper = Paper::where('paperId', $paperId)->firstOrFail();
        $request->validate(['theme_name' => 'required|string|max:50']);

        $currentThemes = $paper->themes ?? []; // Get existing array
        
        // Avoid duplicates
        if (!in_array($request->theme_name, $currentThemes)) {
            $currentThemes[] = $request->theme_name;
            $paper->themes = $currentThemes;
            $paper->save();
        }

        return back();
    }

    public function removeTheme(Request $request, $profileId, $paperId)
    {
        $paper = Paper::where('paperId', $paperId)->firstOrFail();
        $themeToRemove = $request->input('theme_name');

        $currentThemes = $paper->themes ?? [];
        
        // Filter out the theme to remove
        $updatedThemes = array_values(array_filter($currentThemes, function($theme) use ($themeToRemove) {
            return $theme !== $themeToRemove;
        }));

        $paper->themes = $updatedThemes;
        $paper->save();

        return back();
    }

    public function finalizeLitReview($profileId, $paperId)
    {
        $paper = Paper::where('paperId', $paperId)->firstOrFail();
        
        // Security check
        $this->authorizeEditor($paper);

        // Toggle the status (Finalized <-> Draft)
        $paper->lit_review_finalized = !$paper->lit_review_finalized;
        $paper->save();

        $status = $paper->lit_review_finalized ? 'finalized' : 're-opened';
        return back()->with('success', "Literature review has been $status.");
    }

    public function paperMethodology($profileId, $paperId)
    {
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->firstOrFail();

        // Calculate Permissions (Same logic as Lit Review)
        $currentUser = Auth::user();
        $canEdit = false;
        if ($currentUser && $currentUser->lecturer) {
            $isOwner = $paper->lecturer_id === $currentUser->lecturer->id;
            $isCollaborator = Collaboration::where('paper_id', $paper->id)
                ->where('lecturer_id', $currentUser->lecturer->id)->exists();
            if ($isOwner || $isCollaborator) $canEdit = true;
        }

        return view('pages.methodology', [
            'user' => $user,
            'paper' => $paper,
            'canEdit' => $canEdit
        ]);
    }

    public function saveMethodology(Request $request, $profileId, $paperId)
    {
        $paper = Paper::where('paperId', $paperId)->firstOrFail();
        
        // Security Check
        $this->authorizeEditor($paper);

        $paper->methodology_xml = $request->input('xml');
        $paper->save();

        return response()->json(['status' => 'success', 'message' => 'Diagram saved successfully']);
    }

    public function finalizeMethodology($profileId, $paperId)
    {
        $paper = Paper::where('paperId', $paperId)->firstOrFail();
        
        $this->authorizeEditor($paper);

        $paper->methodology_finalized = !$paper->methodology_finalized;
        $paper->save();

        $status = $paper->methodology_finalized ? 'finalized' : 're-opened';
        return back()->with('success', "Methodology has been $status.");
    }
}

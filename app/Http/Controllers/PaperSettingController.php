<?php

namespace App\Http\Controllers;

use App\Models\Paper;
use App\Models\PaperType;
use App\Models\ResearchField;
use App\Models\User;
use Illuminate\Http\Request;

class PaperSettingController extends Controller
{
    public function index($profileId, $paperId){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $researchFields = ResearchField::all();
        $paperTypes = PaperType::all();

        return view('pages.paper-settings', [
            'user' => $user,
            'paper' => $paper,
            'researchFields' => $researchFields,
            "paperTypes" => $paperTypes,
        ]);
    }

    public function updatePaper($profileId, $paperId, Request $request){
        $user = User::where("profileId", $profileId)->firstOrFail();
        $paper = Paper::where('paperId', $paperId)->with('lecturer.user')->firstOrFail();

        $visibility = isset($request["is_public"]) ? "public" : "private";

        $paper->update([
            "title" => $request["title"],
            "description" => $request["description"],
            "paper_type_id" => $request["type"],
            "visibility" => $visibility
        ]);

        $inputTags = $request->input('category_ids', []);

        $fieldIds = ResearchField::whereIn('researchFieldId', $inputTags)->pluck('id');

        $paper->researchFields()->sync($fieldIds);


        return redirect("/" . $user->profileId . "/paper/" . $paper->paperId . "/settings")->with('success', 'Your paper has been updated successfully!');
    }

    public function deletePaper($profileId, $paperId){
        $user = User::where("profileId", $profileId)->firstOrFail();

        Paper::where('paperId', $paperId)->delete();


        return redirect("/" . $user->profileId . "/papers")->with('success', 'Your paper has been deleted successfully!');
    }
}

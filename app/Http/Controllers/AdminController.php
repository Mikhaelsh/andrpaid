<?php

namespace App\Http\Controllers;

use App\Models\PaperType;
use App\Models\ResearchField;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(){
        return view("pages.admin");
    }

    public function indexResearchFields(){
        $researchFields = ResearchField::all();

        return view("pages.admin-masterData", [
            "researchFields" => $researchFields,
            "type" => "researchFields"
        ]);
    }

    public function createResearchFields(Request $request){
        $exists = ResearchField::where("name", $request["name"])
                    ->orWhere("researchFieldId", $request["slug"])
                    ->exists();

        if ($exists) {
            return redirect()->back()->with('error', "Can't add new Research Field. The Name or Field ID already exists.");
        }

        ResearchField::create([
            "name" => $request["name"],
            "researchFieldId" => $request["slug"]
        ]);

        return redirect()->back()->with('success', 'New Research Field has been added successfully!');
    }

    public function updateResearchFields(Request $request){
        $researchField = ResearchField::where("id", $request["id"])->first();

        $exists = ResearchField::where("name", $request["name"])
                    ->orWhere("researchFieldId", $request["slug"])->first();

        if ($exists && $exists->id != $researchField->id) {
            return redirect()->back()->with('error', "Can't update the Research Field. The Name or Field ID already exists.");
        }

        $researchField->update([
            "name" => $request["name"],
            "researchFieldId" => $request["slug"]
        ]);

        return redirect()->back()->with('success', 'Research Field has been updated successfully!');
    }

    public function deleteResearchFields(Request $request){
        ResearchField::where("id", $request["id"])->delete();

        return redirect()->back()->with('success', 'Research Field has been deleted successfully!');
    }

    public function indexPaperTypes(){
        $paperTypes = PaperType::all();

        return view("pages.admin-masterData", [
            "paperTypes" => $paperTypes,
            "type" => "paperTypes"
        ]);
    }

    public function createPaperTypes(Request $request){
        $exists = PaperType::where("name", $request["name"])
                    ->orWhere("paperTypeId", $request["slug"])
                    ->exists();

        if ($exists) {
            return redirect()->back()->with('error', "Can't add new Paper Type. The Name or Field ID already exists.");
        }

        PaperType::create([
            "name" => $request["name"],
            "paperTypeId" => $request["slug"]
        ]);

        return redirect()->back()->with('success', 'New Paper Type has been added successfully!');
    }

    public function updatePaperTypes(Request $request){
        $paperType = PaperType::where("id", $request["id"])->first();

        $exists = PaperType::where("name", $request["name"])
                    ->orWhere("paperTypeId", $request["slug"])->first();

        if ($exists && $exists->id != $paperType->id) {
            return redirect()->back()->with('error', "Can't update the Paper Type. The Name or Field ID already exists.");
        }

        $paperType->update([
            "name" => $request["name"],
            "paperTypeId" => $request["slug"]
        ]);

        return redirect()->back()->with('success', 'Paper Type has been updated successfully!');
    }

    public function deletePaperTypes(Request $request){
        PaperType::where("id", $request["id"])->delete();

        return redirect()->back()->with('success', 'Paper Type has been deleted successfully!');
    }
}

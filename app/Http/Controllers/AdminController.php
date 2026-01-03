<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Lecturer;
use App\Models\Paper;
use App\Models\PaperType;
use App\Models\ResearchField;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

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

    public function indexActivityLogs(Request $request){
        $query = ActivityLog::query();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $listQuery = clone $query;
        $activityLogs = $listQuery->with('user')->latest()->paginate(10)->withQueryString();

        $chartQuery = clone $query;

        if (!$request->filled('date_from') && !$request->filled('date_to')) {
            $chartQuery->where('created_at', '>=', Carbon::now()->subDays(7));
        }

        $chartData = $chartQuery->selectRaw('DATE(created_at) as date, type, count(*) as count')
            ->groupBy('date', 'type')
            ->orderBy('date', 'asc')
            ->get();

        $users = User::orderBy('name')->get(['id', 'name']);

        return view("pages.admin-monitoring", [
            "activityLogs" => $activityLogs,
            "chartData" => $chartData,
            "users" => $users,
            "type" => "activityLogs"
        ]);
    }

    public function indexGlobalStatistics(Request $request){
        // --- 1. OVERALL STATS ---
        $stats = [
            'universities' => University::count(),
            'lecturers'    => Lecturer::count(),
            'papers'       => Paper::count(),
        ];

        // --- 2. MAP DATA (Snapshot for Leaflet) ---
        // Only fetches necessary fields. No date filtering here (Map shows current state).
        $mapData = User::with(['university.province', 'lecturer.province'])
            ->get()
            ->map(function ($user) {
                $role = null;
                $province = null;

                if ($user->university) {
                    $role = 'university';
                    $province = $user->university->province->name ?? null;
                } elseif ($user->lecturer) {
                    $role = 'lecturer';
                    $province = $user->lecturer->province->name ?? null;
                }

                if (!$province) return null;

                return [
                    'name' => $user->name,
                    'role' => $role, // 'university' or 'lecturer'
                    'province' => $province,
                    'profile_id' => $user->profileId
                ];
            })
            ->filter()
            ->values();

        // --- 3. CHART DATA (Historical Growth) ---
        // Applies Date Filter if present, otherwise defaults to last 30 days
        $startDate = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->subDays(30);
        $endDate   = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now();

        // Helper to get daily counts
        $getGrowth = function($model) use ($startDate, $endDate) {
            return $model::selectRaw('DATE(created_at) as date, count(*) as count')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();
        };

        $chartData = [
            'universities' => $getGrowth(University::class),
            'lecturers'    => $getGrowth(Lecturer::class),
            'papers'       => $getGrowth(Paper::class),
        ];

        return view("pages.admin-monitoring", [
            "type" => "globalStatistics",
            "stats" => $stats,
            "mapData" => $mapData,
            "chartData" => $chartData
        ]);
    }
}

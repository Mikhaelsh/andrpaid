<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Lecturer;
use App\Models\Paper;
use App\Models\PaperType;
use App\Models\Report;
use App\Models\ReportType;
use App\Models\ResearchField;
use App\Models\University;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(){
        $totalUsers = User::count();
        $totalUniversities = University::count();
        $totalLecturers = Lecturer::count();
        $totalPapers = Paper::count();

        $recentUsers = User::latest()->take(5)->get();
        $recentActivities = ActivityLog::with('user')->latest()->take(5)->get();

        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(6);

        $logs = ActivityLog::select(DB::raw('DATE(created_at) as date'), 'type', DB::raw('count(*) as count'))
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->whereIn('type', ['login', 'logout'])
            ->groupBy('date', 'type')
            ->get();

        $chartLabels = [];
        $loginData = [];
        $logoutData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $shortDate = Carbon::now()->subDays($i)->format('d M');

            $chartLabels[] = $shortDate;

            $loginCount = $logs->where('date', $date)->where('type', 'login')->first()->count ?? 0;
            $logoutCount = $logs->where('date', $date)->where('type', 'logout')->first()->count ?? 0;

            $loginData[] = $loginCount;
            $logoutData[] = $logoutCount;
        }

        return view('pages.admin', compact(
            'totalUsers',
            'totalUniversities',
            'totalLecturers',
            'totalPapers',
            'recentUsers',
            'recentActivities',
            'chartLabels',
            'loginData',
            'logoutData'
        ));
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
        $stats = [
            'universities' => University::count(),
            'lecturers'    => Lecturer::count(),
            'papers'       => Paper::count(),
        ];

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
                    'role' => $role,
                    'province' => $province,
                    'profile_id' => $user->profileId
                ];
            })
            ->filter()
            ->values();

        $startDate = $request->date_from ? Carbon::parse($request->date_from) : Carbon::now()->subDays(30);
        $endDate   = $request->date_to ? Carbon::parse($request->date_to) : Carbon::now();

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

    public function indexUserReport(Request $request){
        $query = Report::with(['user', 'reportType']);

        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('type') && $request->type != 'all') {
            $query->where('report_type_id', $request->type);
        }

        $reports = $query->latest()->paginate(10);
        $reportTypes = ReportType::all();

        $stats = [
            'total'     => Report::count(),
            'pending'   => Report::where('status', 'pending')->count(),
            'resolved'  => Report::where('status', 'resolved')->count(),
        ];

        $dates = collect();
        $incomingData = collect();
        $resolvedData = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dates->push($date->format('M d'));

            $incomingData->push(Report::whereDate('created_at', $date)->count());
            $resolvedData->push(Report::whereDate('updated_at', $date)->where('status', 'resolved')->count());
        }

        return view('pages.admin-request', [
            'reports' => $reports,
            'reportTypes' => $reportTypes,
            'stats' => $stats,
            'dates' => $dates,
            'incomingData' => $incomingData,
            'resolvedData' => $resolvedData,
        ]);
    }

    public function manageUserReport($reportId, Request $request){
        $report = Report::where("id", $reportId)->first();

        $report->update([
            "status" => $request["status"]
        ]);

        return redirect()->back()->with("success", "Report Status has been changed successfully");
    }
}

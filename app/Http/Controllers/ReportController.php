<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ReportType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function submitReport(Request $request){
        $reportType = ReportType::where("reportTypeId", $request["type"])->first();

        Report::create([
            "description" => $request["description"],
            "user_id" => Auth::user()->id,
            "report_type_id" => $reportType->id
        ]);

        return redirect()->back()->with("successReport", "Your Report has been submitted successfully");
    }
}

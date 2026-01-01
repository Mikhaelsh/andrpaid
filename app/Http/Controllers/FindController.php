<?php

namespace App\Http\Controllers;

use App\Models\Lecturer;
use Illuminate\Http\Request;

class FindController extends Controller
{
    public function index(){
        $lecturers = Lecturer::all();

        return view("pages.find", [
            "lecturers" => $lecturers
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function searchUserLecturer(Request $request){
        $query = $request->get('q');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::query()
            ->whereHas('lecturer')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->take(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }
}

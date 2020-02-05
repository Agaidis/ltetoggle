<?php

namespace App\Http\Controllers;

use App\Permit;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserMMPController extends Controller
{
    //

    public function index() {
        try {
            $permits = Permit::where('assignee', Auth::user()->id)->get();
            $users = User::all();

            return view('userMMP', compact('permits', 'users'));
        } catch( \Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update Assignee Error', $e->getMessage());
            return view('dashboard');
        }
    }
}

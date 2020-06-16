<?php

namespace App\Http\Controllers;

use App\Permit;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserMMPController extends Controller
{

    public function index() {
        try {
            $eaglePermits = DB::table('permits')->where('assignee', Auth::user()->id)->where('interest_area', 'eagle')->get();
            $nvxPermits = DB::table('permits')->where('assignee', Auth::user()->id)->whereIn('interest_area', ['nvx', 'apr'])->get();
            $users = User::all();

            return view('userMMP', compact('eaglePermits', 'nvxPermits', 'users'));
        } catch( \Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update Assignee Error', $e->getMessage());
            return view('dashboard');
        }
    }

    public function justus() {
        try {
            $eaglePermits = DB::table('permits')->where('assignee', 68)->where('interest_area', 'eagle')->get();
            $nvxPermits = DB::table('permits')->where('assignee', 68)->whereIn('interest_area', ['nvx', 'apr'])->get();
            $users = User::all();

            return view('userMMP', compact('eaglePermits', 'nvxPermits', 'users'));
        } catch( \Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update Assignee Error', $e->getMessage());
            return view('dashboard');
        }
    }
}

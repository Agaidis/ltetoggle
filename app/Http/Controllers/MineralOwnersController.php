<?php

namespace App\Http\Controllers;

use App\MineralOwner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MineralOwnersController extends Controller
{
    public function index(Request $request) {
        $leaseNames = array();

        $owners = MineralOwner::where('owner', $request->operator)->distinct()->get();

       foreach ($owners as $owner) {
           array_push($leaseNames, $owner->lease_name);
       }
       $leaseNames = array_unique($leaseNames);

       return view('mineralOwner', compact('owners', 'leaseNames'));
    }
}

<?php

namespace App\Http\Controllers;

use App\MineralOwner;
use Illuminate\Http\Request;

class MineralOwnersController extends Controller
{
    public function index(Request $request) {
        $leaseNames = array();



        $owners = MineralOwner::where('lease_name', $request->operator)->groupBy('owner')->get();

        if ($owners->isEmpty()) {
            $operator = str_replace(['UNIT ', ' UNIT'], ['', ''], $request->operator);
            $owners = MineralOwner::where('lease_name', $operator)->groupBy('owner')->get();

        }
        if ($owners->isEmpty()) {
            $owners = MineralOwner::where('operator_company_name', $request->reporter)->get();
        }

       foreach ($owners as $owner) {
           array_push($leaseNames, $owner->lease_name);
       }
       $leaseNames = array_unique($leaseNames);

       return view('mineralOwner', compact('owners', 'leaseNames'));
    }
}

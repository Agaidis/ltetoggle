<?php

namespace App\Http\Controllers;

use App\Lease;
use App\MineralOwner;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MineralOwnersController extends Controller
{
    public function index(Request $request) {
        $leaseNames = array();
        $users = User::all();


        $owners = MineralOwner::where('lease_name', $request->operator)->groupBy('owner')->get();

        if ($owners->isEmpty()) {
            $operator = str_replace(['UNIT ', ' UNIT'], ['', ''], $request->operator);
            $owners = MineralOwner::where('lease_name', $operator)->groupBy('owner')->get();

        }
        if ($owners->isEmpty()) {
            $owners = MineralOwner::where('operator_company_name', $request->reporter)->groupBy('owner')->get();
        }

       foreach ($owners as $owner) {
           array_push($leaseNames, $owner->lease_name);
       }
       $leaseNames = array_unique($leaseNames);

       return view('mineralOwner', compact('owners', 'leaseNames', 'users'));
    }

    public function getOwnerInfo (Request $request) {

        try {
            $owner = MineralOwner::where('id', $request->id)->groupBy('owner')->first();

            return $owner;
        } catch ( \Exception $e ) {
            Log::info($e->getMessage());
            mail('andrew.gaidis@gmail.com', 'Toggle Error', $e->getMessage());
            return false;
        }
    }

    public function updateAssignee(Request $request) {
        try {
            MineralOwner::where('id', $request->ownerId)->update(['assignee' => $request->assigneeId]);

            return 'success';

        } catch( Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update Assignee Error', $e->getMessage());
            return 'error';
        }
    }

    public function updatePhoneNumbers(Request $request) {
        try {
            MineralOwner::where('id', $request->ownerId)->update(
                [
                    'cell' => $request->cell,
                    'work' => $request->work,
                    'home' => $request->home
                ]);

            return 'success';

        } catch( Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update Assignee Error', $e->getMessage());
            return 'error';
        }
    }
}

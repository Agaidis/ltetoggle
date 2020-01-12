<?php

namespace App\Http\Controllers;

use App\MineralOwner;
use App\Permit;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NewPermitsController extends Controller
{

    private $apiManager;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->apiManager = new APIManager();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $permits = Permit::all();
        $users = User::all();

        return view('newPermits', compact('permits', 'users'));
    }

    public function getPermitDetails(Request $request) {

        try {
            $permit = Permit::where('permit_id', $request->permitId)->get();
            $leaseDescription = MineralOwner::where('lease_name', $request->reportedOperator)->first();
            $objData = new \stdClass;

            $objData->permit = $permit;
            $objData->leaseDescription = $leaseDescription;
        } catch ( \Exception $e)  {
            Log::info($e->getMessage());
            $objData = false;
        }
        return response()->json($objData);
    }

    public function getNotes(Request $request) {
        try {
            return Permit::where('permit_id', $request->permitId)->value('notes');
        } catch( \Exception $e ) {
            Log::info($e->getMessage());
            mail('andrew.gaidis@gmail.com', 'Toggle Error', $e->getMessage());
        }
    }

    public function updateNotes(Request $request) {
        try {
            $doesLeaseExist = Permit::where('permit_id', $request->permitId)->get();
            $userName = Auth()->user()->name;
            $date = date('Y/m/d h:m:s');

            if ($doesLeaseExist->isEmpty()) {
                $newLease = new Permit();

                $newLease->permit_id = $request->permitId;
                $newLease->notes = $userName . ' Date: ' . $date . '<br>' . $request->notes;

                $newLease->save();

            } else {
                Permit::where('permit_id', $request->permitId)
                    ->update(['notes' => '<b>User</b>: ' . $userName . ' <br><b>Date<b>: ' . $date . '<br>' . $request->notes . '<br><hr>' . $doesLeaseExist[0]->notes]);
            }

            $updatedPermit = Permit::where('permit_id', $request->permitId)->first();

            return $updatedPermit->notes;

        } catch( Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Error', $e->getMessage());
            return 'error';
        }
    }

    public function updateAssignee(Request $request) {
        try {
            $doesLeaseExist = Permit::where('permit_id', $request->permitId)->get();

            if ($doesLeaseExist->isEmpty()) {
                $newLease = new Permit();

                $newLease->permit_id = $request->permitId;
                $newLease->assignee = $request->assigneeId;
                $newLease->notes = '';

                $newLease->save();

                return 'success';
            } else {
                Permit::where('permit_id', $request->permitId)
                    ->update(['assignee' => $request->assigneeId]);

                return 'success';
            }
        } catch( Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Error', $e->getMessage());
            return 'error';
        }
    }
}
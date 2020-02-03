<?php

namespace App\Http\Controllers;

use App\ErrorLog;
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
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            $objData = false;
        }
        return response()->json($objData);
    }

    public function getNotes(Request $request) {
        try {
            return Permit::where('permit_id', $request->permitId)->value('notes');
        } catch( \Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function updateNotes(Request $request) {
        try {
            $doesLeaseExist = Permit::where('permit_id', $request->permitId)->get();
            $userName = Auth()->user()->name;
            $date = date('d/m/Y h:m:s');

            if ($doesLeaseExist->isEmpty()) {
                $newLease = new Permit();

                $newLease->permit_id = $request->permitId;
                $newLease->notes = '<p style="color:#755139FF; font-size:14px; margin-bottom:0;">'.$userName . ' | '. $date . '</p>' . $request->notes;

                $newLease->save();

            } else {
                Permit::where('permit_id', $request->permitId)
                    ->update(['notes' => '<p style="color:#755139FF; font-size:14px; margin-bottom:0;"> '.$userName . ' | '. $date . '</p>' . $request->notes . '<hr>' . $doesLeaseExist[0]->notes]);
            }

            $updatedPermit = Permit::where('permit_id', $request->permitId)->first();

            return $updatedPermit->notes;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
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
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }
}
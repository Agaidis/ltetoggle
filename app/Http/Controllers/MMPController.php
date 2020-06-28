<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\GeneralSetting;
use App\MineralOwner;
use App\Permit;
use App\PermitNote;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MMPController extends Controller
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


        $users = User::all();
        $currentUser = Auth::user()->name;
        $userRole = Auth::user()->role;
        $nonProducingPermits = DB::table('permits')->where('interest_area', 'apr')->groupBy('lease_name', 'reported_operator')->get();

        if ($userRole === 'regular') {
            $eaglePermits = DB::table('permits')->where('assignee', Auth::user()->id)->where('interest_area', 'eagle')->get();
            $nvxPermits = DB::table('permits')->where('assignee', Auth::user()->id)->whereIn('interest_area', ['nvx'])->get();

            return view('userMMP', compact('userRole', 'eaglePermits', 'nvxPermits', 'users', 'currentUser', 'nonProducingPermits'));
        } else {
            $eaglePermits = DB::table('permits')->where('interest_area', 'eagle')->groupBy('abstract', 'lease_name', 'survey')->get();
            $nvxPermits = DB::table('permits')->whereIn('interest_area', ['nvx'])->groupBy('abstract', 'lease_name', 'survey')->get();

            return view('dashboard', compact('userRole','eaglePermits', 'nvxPermits', 'users', 'currentUser', 'nonProducingPermits'));
        }


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
           $leaseName = Permit::where('permit_id', $request->permitId)->value('lease_name');

            return PermitNote::where('lease_name', $leaseName)->orderBy('id', 'DESC')->get();
        } catch( \Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function updateNotes(Request $request) {
        try {
            $permitInfo = Permit::where('permit_id', $request->permitId)->first();

            $userName = Auth()->user()->name;
            $date = date('d/m/Y h:m:s', strtotime('-5 hours'));

            $newPermitNote = new permitNote();

            $newPermitNote->permit_id = $request->permitId;
            $newPermitNote->lease_name = $permitInfo->lease_name;
            $newPermitNote->notes = '<div class="permit_note" id="permit_'.$newPermitNote->id.'_'. $request->permitId.'"><p style="font-size:14px; margin-bottom:0;"> '.$userName . ' | '. $date . '<span class="fas fa-trash delete_permit_note" id="delete_permit_note_'.$newPermitNote->id.'_'.$request->permitId.'" style="display:none; cursor:pointer; color:red; float:right;margin-right:5%;"></span></p>' . $request->notes .'<hr></div>';

            $newPermitNote->save();

            PermitNote::where('id', $newPermitNote->id)
                ->update(['notes' => '<div class="permit_note" id="permit_'.$newPermitNote->id.'_'. $request->permitId.'"><p style="font-size:14px; margin-bottom:0;">'.$userName . ' | '. $date . '<span class="fas fa-trash delete_permit_note" id="delete_permit_note_'.$newPermitNote->id.'_'.$request->permitId.'" style="display: none; cursor:pointer; color:red; float:right;margin-right:3%;"></span></p>' . $request->notes .'<hr></div>']);

            $updatedPermitNote = PermitNote::where('lease_name', $permitInfo->lease_name)->orderBy('id', 'DESC')->get();

            return $updatedPermitNote;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function deleteNote(Request $request) {
        try {
            $permitNote = PermitNote::where('id', $request->id)->first();

            PermitNote::destroy($request->id);

            $updatedPermitNotes = PermitNote::where('permit_id', $permitNote->permit_id)->orderBy('id', 'DESC')->get();

            return $updatedPermitNotes;
        } catch( Exception $e) {
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

    public function updateStatus(Request $request) {
        try {
            Permit::where('permit_id', $request->permitId)
                    ->update(['toggle_status' => $request->status]);

                return $request->status;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function updatePrices(Request $request) {
        try {

            GeneralSetting::where('name', 'oil')
                ->update(['value' => $request->oilPrice]);

            GeneralSetting::where('name', 'gas')
                ->update(['value' => $request->gasPrice]);

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
        }
    }
}
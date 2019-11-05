<?php

namespace App\Http\Controllers;

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
            $query = DB::select('SELECT permits.permit_id, leases.lease_id, permits.lease_name, permits.permit_type, permits.operator_alias, permits.approved_date, permits.drill_type, permits.block, permits.range, permits.section, permits.state, permits.survey, permits.township, permits.well_type, permits.abstract, leases.county_parish, leases.area_acres, leases.grantee_alias, leases.grantor, leases.expiration_primary_term
FROM permits
LEFT JOIN leases ON permits.operator_alias = leases.grantee_alias WHERE permit_id = '. $request->permitId);

        } catch ( \Exception $e)  {
            Log::info($e->getMessage());
        }



        return $query;
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

            if ($doesLeaseExist->isEmpty()) {
                $newLease = new Permit();

                $newLease->permit_id = $request->permitId;
                $newLease->notes = $request->notes;

                $newLease->save();

                return 'success';
            } else {
                Permit::where('permit_id', $request->permitId)
                    ->update(['notes' => $request->notes]);

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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lease;
use Illuminate\Support\Facades\Log;
use App\Permit;

class DashboardController extends Controller
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
        $leases = Lease::all();

        return view('dashboard', compact('leases'));
    }

    public function getLeaseDetails(Request $request) {
        $lease = Lease::where('lease_id', $request->leaseId)->get();

        $splitCounty = explode('(', $lease[0]->county_parish);

        $permits = Permit::where('county_parish', $splitCounty[0])->get();

        $response = [$permits, $lease];

        return $response;
    }

    public function getNotes(Request $request) {
        try {
            return Lease::where('lease_id', $request->leaseId)->value('notes');
        } catch( \Exception $e ) {
            Log::info($e->getMessage());
            mail('andrew.gaidis@gmail.com', 'Toggle Error', $e->getMessage());
        }
    }

    public function updateNotes(Request $request) {
        try {
            $doesLeaseExist = Lease::where('lease_id', $request->leaseId)->get();

            if ($doesLeaseExist->isEmpty()) {
                $newLease = new Lease();

                $newLease->lease_id = $request->leaseId;
                $newLease->notes = $request->notes;

                $newLease->save();

                return 'success';
            } else {
                Lease::where('lease_id', $request->leaseId)
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
}

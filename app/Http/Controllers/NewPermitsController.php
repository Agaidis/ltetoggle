<?php

namespace App\Http\Controllers;

use App\Permit;
use Illuminate\Http\Request;
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
        $decodedPermits = [];
        $token = $this->apiManager->getToken();

        $permits = $this->apiManager->getPermits($token->access_token);

        foreach ($permits as $permit => $stuff) {
            Log::info($stuff);
            Log::info(json_decode($stuff));
           $decodedPermits[$permit] = json_decode($stuff);
        }
       // $permits = json_decode($permits);


        return view('newPermits', compact('decodedPermits'));
    }

    public function getPermitDetails(Request $request) {
        $token = $this->apiManager->getToken();

        return $this->apiManager->getPermit($token->access_token, $request->permitId);
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
}

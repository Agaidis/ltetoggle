<?php

namespace App\Http\Controllers;

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
}

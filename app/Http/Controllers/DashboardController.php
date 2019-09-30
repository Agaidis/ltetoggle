<?php

namespace App\Http\Controllers;

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
        $token = $this->apiManager->getToken();

        $leases = $this->apiManager->getLandtracLeases($token->access_token);


        $leases = json_decode($leases);


        return view('dashboard', compact('leases'));
    }
}

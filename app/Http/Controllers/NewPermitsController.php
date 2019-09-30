<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $token = $this->apiManager->getToken();

        $permits = $this->apiManager->getPermits($token->access_token);

        $permits = json_decode($permits);


        return view('newPermits', compact('permits'));
    }
}

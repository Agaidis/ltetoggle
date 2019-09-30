<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelboreController extends Controller
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

        $wellBores = $this->apiManager->getWellBores($token->access_token);
        $wellBores = json_decode($wellBores);

        return view('welbore', compact('wellBores'));
    }
}

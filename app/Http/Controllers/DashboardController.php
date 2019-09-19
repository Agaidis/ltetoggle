<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $apiKey;
    private $apiToken;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->apiKey = env('DRILLING_API_KEY');
        $this->apiToken = env('DRILLING_ACCESS_TOKEN');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $client = new Client();

        try {
            $response = $client->request('GET', 'https://di-api.drillinginfo.com/v2/direct-access/landtrac-leases', [

                'headers' => [
                    'X-API-KEY' => $this->apiKey,
                    'Authorization' => $this->apiToken,
                    'Content-Type' => 'application/json'
                    ],
                ]
            );
        $data = $response->getBody()->getContents();


        } catch ( ClientException $e ) {

            print_r($e->getMessage());
            mail('andrew.gaidis@gmail.com', 'Drilling API Error', $e->getMessage());
        }
        return view('dashboard', compact('data'));
    }
}

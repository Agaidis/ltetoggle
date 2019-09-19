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
                    'Authorization' => 'Bearer '.'eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJsNzVlQ1R3UGVpTGR3UFcyXzdxRDJOdm5mVFZFeDh5UVB2YnJtTV95NWtzIn0.eyJqdGkiOiJhOWY2YTIxZi01NjVkLTQzYjAtYTdjZi1jMmEzODY5YzAwY2QiLCJleHAiOjE1Njg4NjA3MjYsIm5iZiI6MCwiaWF0IjoxNTY4ODMxNjI2LCJpc3MiOiJodHRwczovL2F1dGguZHJpbGxpbmdpbmZvLmNvbS9hdXRoL3JlYWxtcy9wcm9kIiwiYXVkIjoiMjEwMDctZGlyZWN0LWFjY2VzcyIsInN1YiI6Ijk0MmU1MWIyLWI0ZDAtNDI4ZC1iYzg2LWFiYjg1YzAwNDlhYiIsInR5cCI6IkJlYXJlciIsImF6cCI6IjIxMDA3LWRpcmVjdC1hY2Nlc3MiLCJhdXRoX3RpbWUiOjAsInNlc3Npb25fc3RhdGUiOiIxNDM0YTUyYi04NTNkLTQwMzItOTZhMC0xMDQ5Yjk2ZjY4YzIiLCJhY3IiOiIxIiwiY2xpZW50X3Nlc3Npb24iOiI4MmUxMzIxMi0zZmNkLTQ4NTctOGJmMy1hODVkM2E3NTMyNTIiLCJhbGxvd2VkLW9yaWdpbnMiOltdLCJyZWFsbV9hY2Nlc3MiOnsicm9sZXMiOlsiUklHX0RBVEEiLCJQSFhfREVQVEhfQ0xBVVNFX0RBVEEiLCJQSFhfV0VMTF9EQVRBIiwiUEhYX1BFUk1JVF9EQVRBIiwiUEhYX1BST0RfREFUQSIsIk1JTkVSQUxUUkFDVCIsIlRYX0FMTE9DQVRFRF9QUk9EVUNUSU9OIiwiUEhYX1VTQV9QUk9EIiwiUEhYX0xFQVNFX0RBVEEiLCJHUkFOVE9SX0dSQU5URUVfQUREUkVTU19GRUFUVVJFIiwiUEhYX09LX1JFR19EQVRBIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnt9LCJjbGllbnRJZCI6IjIxMDA3LWRpcmVjdC1hY2Nlc3MiLCJjbGllbnRIb3N0IjoiMTk4LjU4Ljc1LjEzMiIsImdyb3VwcyI6WyIvMjEwMDciXSwicHJlZmVycmVkX3VzZXJuYW1lIjoic2VydmljZS1hY2NvdW50LTIxMDA3LWRpcmVjdC1hY2Nlc3MiLCJjbGllbnRBZGRyZXNzIjoiMTk4LjU4Ljc1LjEzMiJ9.Ay0_qsAmOUv61-3tzO4GW3Q1XsxitX_ZKmdZNpT9HxBJ3wddivFk4WWZoKgDDLM45ijnS0Ryz8lxDcLhG548u-cntiRMhnn-oIoUO0Fxi6yhWdANQKGbfKsuT5Z-Ne2l7CdoLKdMe6z7lTXdBAOci57wHAE1b4XU15W8XE3FEhqHH-IBxTGm-pRCufXiK341nEF7UTuR_cE2HI0yABVmbA55s4atMuJZLflEcQ0SnPAbNO-DLlCsBcUoNLdRzcMIji46MjsIqhfFASJ7mpRTbJQOsCG7tNlntCIPCye7VYqDzwqTNZh5F2GDWJA6Oz3Amnr2HESr55FXmlhBCQo4KQ',
                    'Accept' => 'application/x-www-form-urlencoded',

                    ],
                ]
            );

        } catch ( ClientException $e ) {

            print_r($e->getMessage());
            mail('andrew.gaidis@gmail.com', 'Drilling API Error', $e->getMessage());
        }
        return view('dashboard');
    }
}

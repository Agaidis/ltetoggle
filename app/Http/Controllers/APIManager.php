<?php

namespace App\Http\Middleware;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

class APIManager
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
        $this->apiKey = env('DRILLING_API_KEY');
        $this->apiToken = env('DRILLING_ACCESS_TOKEN');
    }

    /**
     *
     * @return string
     */
    public function getLandtracLeases () {

        $client = new Client();

        $headers = [
            'X-API-KEY' => $this->apiKey,
            'Accept' => 'application/x-www-form-urlencoded',
            'Authorization' => $this->apiToken];
        try {
            $response = $client->request('GET', 'https://di-api.drillinginfo.com/v2/direct-access/landtrac-leases', $headers);

            return $response->getBody();
        } catch ( ClientException $e ) {
            mail('andrew.gaidis@gmail.com', 'Drilling API Error', $e->getMessage());
        }


    }
}

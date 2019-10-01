<?php

namespace App\Http\Controllers;

class APIManager
{

    private $apiKey;
    private $apiToken;
    private $clientId;
    private $clientSecret;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->apiKey = env('DRILLING_API_KEY');
        $this->apiToken = env('DRILLING_ACCESS_TOKEN');
        $this->clientId = env('CLIENT_ID');
        $this->clientSecret = env('CLIENT_SECRET');
    }


    public function getToken () {
        $curl = curl_init();

        curl_setopt_array($curl, array(

            CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/tokens?grant_type=client_credentials&client_secret=f35662b3-1b5a-4a61-a6db-b4783d35d6da&client_id=21007-direct-access",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "X-API-KEY: e89c4d8b6edf1a7b5c9739e6ae5e4235",
                "Content-Type: application/x-www-form-urlencoded"
            ),
                )
        );

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response);
        }
    }

    public function getLandtracLeases ($token) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/landtrac-leases?state=NM&pagesize=1000",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-API-KEY: e89c4d8b6edf1a7b5c9739e6ae5e4235",
                "Content-Type: application/x-www-form-urlencoded",
                "Authorization: Bearer " . $token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }


    }

    public function getLandtracLease ($token, $leaseId) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/landtrac-leases?leaseid=" . $leaseId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-API-KEY: e89c4d8b6edf1a7b5c9739e6ae5e4235",
                "Content-Type: application/x-www-form-urlencoded",
                "Authorization: Bearer " . $token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }


    }


    //?stateprovince=NM&countyparish=CHAVES
    //?stateprovince=NM&countyparish=EDDY
    //?stateprovince=NM&countyparish=LEA
    //?stateprovince=NM&countyparish=ROOSEVELT

    public function getPermits ($token) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/permits?stateprovince=NM&countyparish=CHAVES&pagesize=300",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "x-api-key: e89c4d8b6edf1a7b5c9739e6ae5e4235",
                "Content-Type: application/x-www-form-urlencoded",
                "Authorization:  Bearer " . $token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function getPermit ($token, $permitId) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/permits?permitid=" . $permitId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-API-KEY: e89c4d8b6edf1a7b5c9739e6ae5e4235",
                "Content-Type: application/x-www-form-urlencoded",
                "Authorization: Bearer " . $token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

    public function getWellBores ($token) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/wellbores?pagesize=1000",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "X-API-KEY: e89c4d8b6edf1a7b5c9739e6ae5e4235",
                "Content-Type: application/x-www-form-urlencoded",
                "Authorization: Bearer " . $token
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }
}

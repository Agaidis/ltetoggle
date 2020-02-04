<?php

namespace App\Http\Controllers;

use App\Permit;
use Illuminate\Support\Facades\Log;
use App\WellOrigin;


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

    public function getLandtracLeases ($token)
    {

        $countyResponse = [];
        $counties = array('TX');

//        // Start date
//        $date = '2019-12-01';
//        // End date
//        $end_date = '2019-12-16';
//
//        while (strtotime($date) <= strtotime($end_date)) {
//            echo "$date\n";
//            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
//            $strippedDate = str_replace('-', '', $date);

        foreach ($counties as $county) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/landtrac-leases?countyparish=KARNES\(\TX\)",
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
                $countyResponse[$county] = $response;
            }
        }
    //        }
            return $countyResponse;
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

    public function getPermits ($token) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/permits?countyparish=KARNES&drilltype=H&pagesize=10000",
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

        Log::info($response);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        }

        return $response;
    }

    public function getWellCounts ($token, $permitLeases) {

        foreach ( $permitLeases as $permitLease ) {
            $leaseName = str_replace(' ', '%20', $permitLease->lease_name);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/well-origins?county=KARNES&leasename=". $leaseName,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "x-api-key: e89c4d8b6edf1a7b5c9739e6ae5e4235",
                    "Accept: */*",
                    "Authorization: Bearer " . $token
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            }

            foreach (json_decode($response) as $key => $value) {
                $county = $value->County;
                $currentOperator = $value->CurrentOperator;
                $currentStatus = $value->CurrentStatus;
                $leaseName = $value->LeaseName;
                $uid = $value->UID;

                $doesWellExist = WellOrigin::where('uid', $uid)->get();

                if ($doesWellExist->isEmpty()) {

                    $newWellOrigin = new WellOrigin();

                    $newWellOrigin->county = $county;
                    $newWellOrigin->current_operator = $currentOperator;
                    $newWellOrigin->current_status = $currentStatus;
                    $newWellOrigin->lease_name = $leaseName;
                    $newWellOrigin->uid = $uid;

                    $newWellOrigin->save();

                } else {
                    WellOrigin::where('uid', $uid)
                        ->update([
                            'county' => $county,
                            'current_operator' => $currentOperator,
                            'current_status' => $currentStatus,
                            'lease_name' => $leaseName,
                            'uid' => $uid]);
                }
            }
        }
        return 'success';
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

    public function getProducingEntities ($token) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/producing-entities",
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

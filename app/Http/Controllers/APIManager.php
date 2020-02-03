<?php

namespace App\Http\Controllers;

use App\Permit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use \stdClass;


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
        $countyResponse = [];
        // Start date
        $date = '2020-01-01';
        // End date
        $end_date = '2020-01-24';

        while (strtotime($date) <= strtotime($end_date)) {
            echo "$date\n";
            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
        $strippedDate = str_replace('-', '', $date);


            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/permits?countyparish=KARNES&drilltype=H&createddate=" . $date,
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
                $countyResponse[$strippedDate] = $response;
            }
        }
        return $countyResponse;

    }

    public function getWellCounts ($token, $permitLeases) {

        $wellCounts = [];



        foreach ( $permitLeases as $permitLease ) {
            Log::info($permitLease->lease_name);

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/well-origins?leasename=ATZGER UNIT",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => false,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "X-API-KEY: e89c4d8b6edf1a7b5c9739e6ae5e4235",
                    "Authorization: Bearer eyJhbGciOiJSUzI1NiIsInR5cCIgOiAiSldUIiwia2lkIiA6ICJsNzVlQ1R3UGVpTGR3UFcyXzdxRDJOdm5mVFZFeDh5UVB2YnJtTV95NWtzIn0.eyJqdGkiOiJhY2IxZTJjOC1jODkyLTRlNDUtOWYyNC00OThiMGFjM2Y5N2EiLCJleHAiOjE1ODA3ODY2NDQsIm5iZiI6MCwiaWF0IjoxNTgwNzU3NTQ0LCJpc3MiOiJodHRwczovL2F1dGguZHJpbGxpbmdpbmZvLmNvbS9hdXRoL3JlYWxtcy9wcm9kIiwiYXVkIjoiMjEwMDctZGlyZWN0LWFjY2VzcyIsInN1YiI6Ijk0MmU1MWIyLWI0ZDAtNDI4ZC1iYzg2LWFiYjg1YzAwNDlhYiIsInR5cCI6IkJlYXJlciIsImF6cCI6IjIxMDA3LWRpcmVjdC1hY2Nlc3MiLCJhdXRoX3RpbWUiOjAsInNlc3Npb25fc3RhdGUiOiI3MGY3NzFjMS03M2ZkLTQ0ZjUtYTBhOC0wODBiZGJjMTQ1MjkiLCJhY3IiOiIxIiwiY2xpZW50X3Nlc3Npb24iOiJjZWJjZjljOS04MTYxLTRhOWMtOGJiMC1kNmZkMDg1NjBiNTUiLCJhbGxvd2VkLW9yaWdpbnMiOltdLCJyZWFsbV9hY2Nlc3MiOnsicm9sZXMiOlsiUklHX0RBVEEiLCJQSFhfTk1fUkVHX0RBVEEiLCJQSFhfREVQVEhfQ0xBVVNFX0RBVEEiLCJQSFhfV0VMTF9EQVRBIiwiUEhYX1BFUk1JVF9EQVRBIiwiUEhYX1BST0RfREFUQSIsIk1JTkVSQUxUUkFDVCIsIlRYX0FMTE9DQVRFRF9QUk9EVUNUSU9OIiwiUEhYX1VTQV9QUk9EIiwiUEhYX0xFQVNFX0RBVEEiLCJHUkFOVE9SX0dSQU5URUVfQUREUkVTU19GRUFUVVJFIiwiUEhYX09LX1JFR19EQVRBIl19LCJyZXNvdXJjZV9hY2Nlc3MiOnt9LCJjbGllbnRJZCI6IjIxMDA3LWRpcmVjdC1hY2Nlc3MiLCJjbGllbnRIb3N0IjoiNTIuNS4xNTIuMjExIiwiZ3JvdXBzIjpbIi8yMTAwNyJdLCJwcmVmZXJyZWRfdXNlcm5hbWUiOiJzZXJ2aWNlLWFjY291bnQtMjEwMDctZGlyZWN0LWFjY2VzcyIsImNsaWVudEFkZHJlc3MiOiI1Mi41LjE1Mi4yMTEifQ.UIcePNTFDAQJ7adtqYFOAGEpu7PCm7eDyGqLcS4FAQT9xHCMRbSg-SzPNrHToRFa2qAO3Rk1kCNNeC78B_IuLX7ePK3C1SiG7DoXL_5Fgp9OQxSPhO1ljSK4VYHIuiX4UVMI-8LiriQ_mq8bd-eF-kmJNz8SLx_MalsWP_-5PdDs7fIjUkptLfdheiJEEG7E3sYx6sk5fLI1vvx1Z1bmUzPZJ7BZQ9Ra_brRCGoRfuODMzC2lHTO5iMqjXA13xyL-1wyGAzM4ckkheGOIYNfezv1wTc-aAHAgTTHc-xlG2CjgjZiY-ybtfrO3yR_DKPkFJMIuIQ9qcygfyG2cVSNHA"
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);

            Log::info($response);
            Log::info($err);

            if (isset($response->County)) {
                $county = $response->County;
                $currentOperator = $response->CurrentOperator;
                $currentStatus = $response->CurrentStatus;
                $leaseName = $response->LeaseName;
                $uid = $response->UID;
                print_r('hey');

                Log::info($county);
                Log::info($currentOperator);
                Log::info($currentStatus);
                Log::info($leaseName);
                Log::info($uid);
            }
            print_r($response);

            curl_close($curl);

            if ($err) {
                return "cURL Error #:" . $err;
            } else {
               // $stdObject->$response->id = $response;
            }
        }
        return $wellCounts;
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

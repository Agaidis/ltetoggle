<?php
//
//namespace App\Console\Commands;
//
//use App\Http\Controllers\APIManager;
//use App\Permit;
//use Illuminate\Console\Command;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Log;
//
//class GetWellCounts extends Command
//{
//    /**
//     * The name and signature of the console command.
//     *
//     * @var string
//     */
//    protected $signature = 'process:getWellCounts';
//
//    /**
//     * The console command description.
//     *
//     * @var string
//     */
//    protected $description = 'Command description';
//
//    /**
//     * Create a new command instance.
//     *
//     * @return void
//     */
//    public function __construct()
//    {
//        parent::__construct();
//    }
//
//    /**
//     * Execute the console command.
//     *
//     * @return mixed
//     */
//    public function handle()
//    {
//
//        $apiManager = new APIManager();
//        $token = $apiManager->getToken();
//        $wellCounts = [];
//        $permitLeases = DB::table('permits')
//            ->select('id','lease_name', 'county_parish')
//            ->groupBy('lease_name')
//            ->get();
//
//        foreach ( $permitLeases as $permitLease ) {
//
//            $curl = curl_init();
//
//            curl_setopt_array($curl, array(
//                CURLOPT_URL => "https://di-api.drillinginfo.com/v2/direct-access/well-origins?leasename=" . $permitLease->lease_name,
//                CURLOPT_RETURNTRANSFER => true,
//                CURLOPT_ENCODING => "",
//                CURLOPT_MAXREDIRS => 10,
//                CURLOPT_TIMEOUT => 0,
//                CURLOPT_FOLLOWLOCATION => false,
//                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                CURLOPT_CUSTOMREQUEST => "GET",
//                CURLOPT_HTTPHEADER => array(
//                    "X-API-KEY: e89c4d8b6edf1a7b5c9739e6ae5e4235",
//                    "Content-Type: application/x-www-form-urlencoded",
//                    "Authorization: Bearer " . $token
//                ),
//            ));
//
//            $response = curl_exec($curl);
//            $err = curl_error($curl);
//
//            Log::info($response);
//
//            $county = $response->County;
//            $currentOperator = $response->CurrentOperator;
//            $currentStatus = $response->CurrentStatus;
//            $leaseName = $response->LeaseName;
//            $uid = $response->UID;
//
//            Log::info($county);
//            Log::info($currentOperator);
//            Log::info($currentStatus);
//            Log::info($leaseName);
//            Log::info($uid);
//
//            curl_close($curl);
//
//            if ($err) {
//                return "cURL Error #:" . $err;
//            } else {
//                $wellCounts[$permitLease->lease_name] = $response;
//            }
//        }
//        return $wellCounts;
//        }
//}

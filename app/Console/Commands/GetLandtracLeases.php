<?php

namespace App\Console\Commands;

use App\ErrorLog;
use App\Http\Controllers\APIManager;
use App\LandtracLease;
use App\WellRollUp;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetLandtracLeases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:getLandtracLeases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the landtrac leases';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        try {
            $eagleInterestCountiesArray = array('ATASCOSA\(\TX\)', 'BEE\(\TX\)', 'DEWITT\(\TX\)', 'GONZALES\(\TX\)', 'KARNES\(\TX\)', 'LIVE\OAK\(\TX\)', 'LAVACA\(\TX\)', 'WILSON\(\TX\)');
            $nvxInterestCountiesArray = array('DAWSON\(\TX\)', 'GAINES\(\TX\)', 'BORDEN\(\TX\)', 'CRANE\(\TX\)', 'ECTOR\(\TX\)', 'STERLING\(\TX\)', 'MITCHELL\(\TX\)', 'JEFF\DAVIS\(\TX\)');
            $nvxByApprovedDate = array('LEA\(\NM\)', 'EDDY\(\NM\)');

            $this->getCountyLeaseData($eagleInterestCountiesArray);

            $this->getCountyLeaseData($nvxInterestCountiesArray);

            $this->getCountyLeaseData($nvxByApprovedDate);


            return 'success';
        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine() . ' File: ' . $e->getFile();

            $errorMsg->save();
            return 'error';
        }
    }

        public function getCountyLeaseData ($counties) {

        try {
            $apiManager = new APIManager();

            $token = $apiManager->getToken();

            foreach ($counties as $county) {
                $linkUrl = '';

                do {
                    $leases = $apiManager->getLandtracLeases($county, $token->access_token, $linkUrl);
                    $linkUrl = trim($leases[1]);

                    if ($leases[0] != null && $leases[0] != '' && isset($leases[0])) {
                        $decodedLeases = json_decode($leases[0]);

                        for ($i = 0; $i < count($decodedLeases); $i++) {

//                        if ($decodedLeases[$i]->BottomHoleLongitudeWGS84 != '' && $decodedLeases[$i]->BottomHoleLongitudeWGS84 != null) {
//                            $btmLatLng = '{"lng": ' . $decodedLeases[$i]->BottomHoleLongitudeWGS84 . ', "lat": ' . $decodedLeases[$i]->BottomHoleLatitudeWGS84 . "}";
//                        } else {
//                            $btmLatLng = '';
//                        }

                            LandtracLease::updateOrCreate(['LeaseId' => $decodedLeases[$i]->LeaseId],
                                [
                                    'AreaAcres' => $decodedLeases[$i]->AreaAcres,
                                    'State' => $decodedLeases[$i]->State,
                                    'CountyParish' => $decodedLeases[$i]->CountyParish,
                                    'CreatedDate' => $decodedLeases[$i]->CreatedDate,
                                    'Geometry' => $decodedLeases[$i]->Geometry,
                                    'Grantee' => $decodedLeases[$i]->Grantee,
                                    'GranteeAddress' => $decodedLeases[$i]->GranteeAddress,
                                    'Grantor' => $decodedLeases[$i]->Grantor,
                                    'GrantorAddress' => $decodedLeases[$i]->GrantorAddress,
                                    'CentroidLatitude' => $decodedLeases[$i]->CentroidLatitude,
                                    'CentroidLongitude' => $decodedLeases[$i]->CentroidLongitude,
                                    'MinDepth' => $decodedLeases[$i]->MinDepth,
                                    'MaxDepth' => $decodedLeases[$i]->MaxDepth
                                ]
                            );
                        }
                    }
                } while ($linkUrl != '');
            }
            return 'success';

        } catch ( Exception $e) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine() . ' File: ' . $e->getFile();

            $errorMsg->save();
            return 'error';
        }
    }
}

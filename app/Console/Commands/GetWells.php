<?php

namespace App\Console\Commands;

use App\ErrorLog;
use App\Http\Controllers\APIManager;
use App\Permit;
use App\WellOrigin;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetWells extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:getWells';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will get the well origins';

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
        $apiManager = new APIManager();
        $token = $apiManager->getToken();

        try {
               $leasesCounties = DB::select('SELECT county_parish, lease_name FROM permits GROUP BY lease_name');

                for ($k = 0; $k < count($leasesCounties); $k++) {

                    $leaseName = $leasesCounties[$k]->lease_name;
                    Log::info($leaseName);

                $wells = $apiManager->getWellCounts($token->access_token, $leasesCounties[$k]->county_parish, $leaseName );

                Log::info($wells);

                if ($wells != null && $wells != '' && isset($wells)) {
                    $decodedWells = json_decode($wells);
                    for ($i = 0; $i < count($decodedWells); $i++) {

                        $doesWellExist = WellOrigin::where('uid', $decodedWells[$i]->UID)->get();

                        if ($doesWellExist->isEmpty()) {

                            $newWell = new WellOrigin();

                            $newWell->county = $decodedWells[$i]->County;
                            $newWell->current_operator = $decodedWells[$i]->CurrentOperator;
                            $newWell->current_status = $decodedWells[$i]->CurrentStatus;
                            $newWell->lease_name = $decodedWells[$i]->LeaseName;
                            $newWell->uid = $decodedWells[$i]->UID;
                            $newWell->well_name = $decodedWells[$i]->WellName;
                            if (isset($decodedWells[$i]->wellNumber)) {
                                $newWell->well_number = $decodedWells[$i]->wellNumber;
                            }

                            $newWell->save();

                        } else {
                            WellOrigin::where('uid', $decodedWells[$i]->UID)
                                ->update([
                                    'county' => $decodedWells[$i]->County,
                                    'current_operator' => $decodedWells[$i]->CurrentOperator,
                                    'current_status' => $decodedWells[$i]->CurrentStatus,
                                    'lease_name' => $decodedWells[$i]->LeaseName,
                                    'uid' => $decodedWells[$i]->UID,
                                    'well_name' => $decodedWells[$i]->WellName,
                                    'well_number' => $decodedWells[$i]->WellNumber]);
                        }
                    }
                }

            }
            return 'success';
        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine() . ' File: ' . $e->getFile();

            $errorMsg->save();
            return 'error';
        }
    }
}

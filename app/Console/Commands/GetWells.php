<?php

namespace App\Console\Commands;

use App\ErrorLog;
use App\Http\Controllers\APIManager;
use App\Permit;
use App\WellOrigin;
use App\WellProductionDetail;
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
               $leasesCounties = DB::select('SELECT lease_name FROM permits GROUP BY lease_name');

                for ($k = 0; $k < count($leasesCounties); $k++) {

                    $wells = $apiManager->getWellCounts( $token->access_token, $leasesCounties[$k]->lease_name );

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
                            $newWell->government_id = $decodedWells[$i]->GovernmentID;
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
                                    'well_number' => $decodedWells[$i]->WellNumber,
                                    'government_id' => $decodedWells[$i]->GovernmentID]);
                        }

                        $wellProductionDetails = $apiManager->getWellProductionDetails( $token->access_token, $decodedWells[$i]->GovernmentID );

                        $decodedWellProdDetails = json_decode($wellProductionDetails);

                        for ($j = 0; $j < count($decodedWellProdDetails); $j++) {

                            if (isset($decodedWellProdDetails[$j]->Api10)) {

                                $doesWellProdDetailsExist = WellProductionDetail::where('api10', $decodedWells[$i]->GovernmentID)->where('prod_date', $decodedWellProdDetails[$j]->ProdDate)->get();

                                if ($doesWellProdDetailsExist->isEmpty()) {

                                    $newWellDetail = new WellProductionDetail();

                                    $newWellDetail->api10 = $decodedWellProdDetails[$j]->Api10;
                                    $newWellDetail->api14 = $decodedWellProdDetails[$j]->Api14;
                                    $newWellDetail->avg_gas = $decodedWellProdDetails[$j]->AvgGas;
                                    $newWellDetail->avg_oil = $decodedWellProdDetails[$j]->AvgOil;
                                    $newWellDetail->avg_wtr = $decodedWellProdDetails[$j]->AvgWtr;
                                    $newWellDetail->cum_gas = $decodedWellProdDetails[$j]->CumGas;
                                    $newWellDetail->cum_oil = $decodedWellProdDetails[$j]->CumOil;
                                    $newWellDetail->cum_wtr = $decodedWellProdDetails[$j]->CumWtr;
                                    $newWellDetail->days = $decodedWellProdDetails[$j]->Days;
                                    $newWellDetail->gas = $decodedWellProdDetails[$j]->Gas;
                                    $newWellDetail->prod_date = $decodedWellProdDetails[$j]->ProdDate;

                                    $newWellDetail->save();

                                } else {
                                    WellProductionDetail::where('api10', $decodedWells[$i]->GovernmentID)
                                        ->update([
                                            'api10' => $decodedWellProdDetails[$j]->Api10,
                                            'api14' => $decodedWellProdDetails[$j]->Api14,
                                            'avg_gas' => $decodedWellProdDetails[$j]->AvgGas,
                                            'avg_oil' => $decodedWellProdDetails[$j]->AvgOil,
                                            'avg_wtr' => $decodedWellProdDetails[$j]->AvgWtr,
                                            'cum_gas' => $decodedWellProdDetails[$j]->CumGas,
                                            'cum_oil' => $decodedWellProdDetails[$j]->CumOil,
                                            'cum_wtr' => $decodedWellProdDetails[$j]->CumWtr,
                                            'days' => $decodedWellProdDetails[$j]->Days,
                                            'gas' => $decodedWellProdDetails[$j]->Gas,
                                            'prod_date' => $decodedWellProdDetails[$j]->ProdDate]);
                                }
                            }
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

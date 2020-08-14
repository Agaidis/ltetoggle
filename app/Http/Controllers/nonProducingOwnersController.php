<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\MineralOwner;
use App\Permit;
use App\PermitNote;
use App\User;
use App\WellRollUp;
use DateTime;
use App\LegalLease;
use Illuminate\Http\Request;
use JavaScript;

class nonProducingOwnersController extends Controller
{
    public function index(Request $request) {
        $users = User::all();

        $operator = $request->operator;
        $permitId = $request->id;

        $permitValues = Permit::where('id', $permitId)->first();
        $allRelatedPermits = Permit::where('lease_name', $permitValues->lease_name)->where('SurfaceLatitudeWGS84', '!=', null)->get();

        if (Auth()->user()->name === 'Billy Moreaux' && $permitValues->is_seen === 0) {
            Permit::where('id', $permitId)->update(['is_seen' => 1, 'toggle_status' => 'none']);
        }

        $leaseName = $permitValues->lease_name;

        try {
            $dateArray = array();
            $onProductionArray = array();
            $oilArray = array();
            $gasArray = array();
            $wellArray = explode('|', $permitValues->selected_well_name);

            array_push($wellArray, $permitValues->lease_name);

          //  $allWells = WellRollUp::where('CountyParish', 'LIKE', '%'.$permitValues->county_parish .'%')->where('SurfaceHoleLatitudeWGS84', '!=', null)->orderBy('LeaseName', 'ASC')->get();

            if ( $permitValues->selected_lease_name != null ) {
                $selectWells = WellRollUp::where('CountyParish', 'LIKE', '%'.$permitValues->county_parish .'%')->groupBy('LeaseName')->orderBy('LeaseName', 'ASC')->get();

                $wells = WellRollUp::select('id', 'CountyParish','OperatorCompanyName','WellStatus','WellName', 'LeaseName', 'WellNumber', 'FirstProdDate', 'LastProdDate', 'CumOil', 'CumGas')->where('LeaseName', $permitValues->lease_name)->where('CountyParish', 'LIKE', '%'.$permitValues->county_parish .'%')->get();

                $owners = LegalLease::where('permit_stitch_id',  $permitValues->permit_id)->get();

                $permitNotes = PermitNote::where('lease_name', $permitValues->lease_name)->orderBy('id', 'DESC')->get();

            } else {
                $selectWells = WellRollUp::where('CountyParish', 'LIKE', '%'.$permitValues->county_parish .'%')->groupBy('LeaseName')->orderBy('LeaseName', 'ASC')->get();

                $wells = WellRollUp::select('id', 'CountyParish','OperatorCompanyName','WellStatus','WellName', 'LeaseName', 'WellNumber', 'FirstProdDate', 'LastProdDate', 'CumOil', 'CumGas')->whereIn('LeaseName', $wellArray)->where('CountyParish', 'LIKE', '%'.$permitValues->county_parish .'%')->get();

                $owners = LegalLease::where('permit_stitch_id', $permitValues->permit_id)->get();

                $permitNotes = PermitNote::where('lease_name', $leaseName)->orderBy('id', 'DESC')->get();

            }

            $count = count($wells);
            $totalGas = 0;
            $totalGasWithComma = 0;
            $totalOil = 0;
            $totalOilWithComma = 0;

            foreach ($wells as $well) {
                if ($well->FirstProdDate != null)
                    array_push($onProductionArray, $well->FirstProdDate);
                array_push($dateArray, $well->LastProdDate);
                array_push($oilArray, $well->CumOil);
                array_push($gasArray, $well->CumGas);

                if ( count($gasArray) > 0 ) {
                    $totalGas = $totalGas + $well->CumGas;
                }
                if ( count($oilArray) > 0) {
                    $totalOil = $totalOil + $well->CumOil;
                }
            }

            if ( $totalGas > 0 ) {
                $totalGasWithComma = number_format($totalGas);
            }

            if ( $totalOil > 0 ) {
                $totalOilWithComma = number_format($totalOil);
            }

            if ( count($dateArray) > 0 ) {
                $latestDate = max($dateArray);

                if ( count($onProductionArray) > 0) {
                    $oldestDate = min($onProductionArray);
                } else {
                    $oldestDate = min($dateArray);
                }

                $datetime1 = new DateTime($oldestDate);
                $datetime2 = new DateTime($latestDate);
                $interval = $datetime1->diff($datetime2);
                $yearsOfProduction = $interval->y + 1;

                $bbls = $totalOil / $yearsOfProduction;
                $gbbls = $totalGas / $yearsOfProduction;
                $bblsWithComma = number_format($bbls);
                $gbblsWithComma = number_format($gbbls);
            } else {
                $oldestDate = 0;
                $latestDate = 0;
                $bblsWithComma = 0;
                $gbblsWithComma = 0;
                $yearsOfProduction = 0;
            }

            JavaScript::put(
                [
                    'allWells' => $selectWells,
                    'allRelatedPermits' => $allRelatedPermits
                ]);

            return view('nonProducingMineralOwner', compact(
                    'owners',
                    'permitValues',
                    'selectWells',
                    'leaseName',
                    'permitNotes',
                    'users',
                    'wells',
                    'wellArray',
                    'operator',
                    'count',
                    'oldestDate',
                    'latestDate',
                    'yearsOfProduction',
                    'totalGasWithComma',
                    'totalOilWithComma',
                    'bblsWithComma',
                    'gbblsWithComma')

            );
        } catch( \Exception $e) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }
}

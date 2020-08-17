<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\GeneralSetting;
use App\MineralOwner;
use App\OwnerNote;
use App\OwnerPhoneNumber;
use App\PermitNote;
use App\User;
use App\WellRollUp;
use Illuminate\Http\Request;
use App\Permit;
use DateTime;
use JavaScript;

class MineralOwnersController extends Controller
{
    public function index(Request $request)
    {
        $users = User::select('id', 'name')->get();

        $permitId = $request->id;

        $permitValues = Permit::where('id', $permitId)->first();

        if (Auth()->user()->name === 'Billy Moreaux' && $permitValues->is_seen === 0) {
            Permit::where('id', $permitId)->update(['is_seen' => 1, 'toggle_status' => 'none']);
        }

        $operator = $permitValues->operator_company_name;
        $leaseName = $permitValues->lease_name;

        try {
            $dateArray = array();
            $onProductionArray = array();
            $oilArray = array();
            $gasArray = array();

            $wellArray = explode('|', $permitValues->selected_well_name);
            $leaseArray = explode('|', $permitValues->selected_lease_name);
            $leases = MineralOwner::select('lease_name')->groupBy('lease_name')->get();

            array_push($leaseArray, $permitValues->lease_name);
            array_push($wellArray, $permitValues->lease_name);

            $allWells = WellRollUp::where('CountyParish', 'LIKE', '%' . $permitValues->county_parish . '%')->where('SurfaceHoleLatitudeWGS84', '!=', null)->orderBy('LeaseName', 'ASC')->get();
            $allRelatedPermits = Permit::where('lease_name', $permitValues->lease_name)->where('SurfaceLatitudeWGS84', '!=', null)->get();


            if ($permitValues->selected_well_name == '' || $permitValues->selected_well_name == null) {
                $wells = WellRollUp::select('id', 'CountyParish', 'OperatorCompanyName', 'WellStatus', 'WellName', 'LeaseName', 'WellNumber', 'FirstProdDate', 'LastProdDate', 'CumOil', 'CumGas')->where('LeaseName', $permitValues->lease_name)->where('CountyParish', 'LIKE', '%' . $permitValues->county_parish . '%')->get();
                $selectWells = WellRollUp::where('CountyParish', 'LIKE', '%' . $permitValues->county_parish . '%')->groupBy('LeaseName')->orderBy('LeaseName', 'ASC')->get();

            } else {
                $wells = WellRollUp::select('id', 'CountyParish', 'OperatorCompanyName', 'WellStatus', 'WellName', 'LeaseName', 'WellNumber', 'FirstProdDate', 'LastProdDate', 'CumOil', 'CumGas')->whereIn('LeaseName', $wellArray)->where('CountyParish', 'LIKE', '%' . $permitValues->county_parish . '%')->get();
                $selectWells = WellRollUp::where('CountyParish', 'LIKE', '%' . $permitValues->county_parish . '%')->groupBy('LeaseName')->orderBy('LeaseName', 'ASC')->get();

            }

            $totalGas = 0;
            $totalGasWithComma = 0;
            $totalOil = 0;
            $totalOilWithComma = 0;

            foreach ($wells as $well) {
                if ($well->WellStatus == 'ACTIVE') {
                    Permit::where('id', $permitId)->update(['is_producing' => 1]);
                }

                if ($well->FirstProdDate != null)
                    array_push($onProductionArray, $well->FirstProdDate);
                array_push($dateArray, $well->LastProdDate);
                array_push($oilArray, $well->CumOil);
                array_push($gasArray, $well->CumGas);

                if (count($gasArray) > 0) {
                    $totalGas = $totalGas + $well->CumGas;
                }
                if (count($oilArray) > 0) {
                    $totalOil = $totalOil + $well->CumOil;
                }
            }

            if ($totalGas > 0) {
                $totalGasWithComma = number_format($totalGas);
            }

            if ($totalOil > 0) {
                $totalOilWithComma = number_format($totalOil);
            }

            if (count($dateArray) > 0) {
                $latestDate = max($dateArray);

                if (count($onProductionArray) > 0) {
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
            $count = count($wells);

            if ($permitValues->selected_lease_name != null) {
                $owners = MineralOwner::select('id',
                    'lease_name',
                    'assignee',
                    'wellbore_type',
                    'follow_up_date',
                    'owner',
                    'owner_address',
                    'owner_city',
                    'owner_zip',
                    'owner_decimal_interest',
                    'owner_interest_type')->whereIn('lease_name', $leaseArray)->groupBy('owner')->orderBy('owner_decimal_interest', 'DESC')->get();

                $permitNotes = PermitNote::select('notes')->where('lease_name', $permitValues->lease_name)->orderBy('id', 'DESC')->get();

            } else {
                $owners = MineralOwner::where('lease_name', $permitValues->lease_name)->groupBy('owner')->orderBy('owner_decimal_interest', 'DESC')->get();
                $permitNotes = PermitNote::select('notes')->where('lease_name', $leaseName)->orderBy('id', 'DESC')->get();
            }

            JavaScript::put(
                [
                    'allWells' => $allWells,
                    'allRelatedPermits' => $allRelatedPermits,
                    'permitId' => $permitId
                ]);

            return view('mineralOwner', compact(
                    'owners',
                    'permitValues',
                    'leases',
                    'leaseName',
                    'leaseArray',
                    'wellArray',
                    'permitNotes',
                    'selectWells',
                    'users',
                    'wells',
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
        } catch (\Exception $e) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }
}

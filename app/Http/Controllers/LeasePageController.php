<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\GeneralSetting;
use App\LegalLease;
use App\MineralOwner;
use App\OwnerNote;
use App\OwnerPhoneNumber;
use App\Permit;
use App\PermitNote;
use App\User;
use App\WellRollUp;
use DateTime;
use Illuminate\Http\Request;
use JavaScript;

class LeasePageController extends Controller
{
    private $txInterestAreas = ['eagle', 'wtx'];
    private $nmInterestAreas = ['nm'];

    public function index(Request $request) {
        $users = User::select('id','name')->get();

        $permitId = $request->id;

        $permitValues = Permit::where('id', $permitId)->first();
        $operator = $permitValues->operator_company_name;
        $leaseName = $permitValues->lease_name;


        if (Auth()->user()->name === 'Billy Moreaux' && $permitValues->is_seen === 0) {
            Permit::where('id', $permitId)->update(['is_seen' => 1, 'toggle_status' => 'none']);
        }

        try {
            $dateArray = array();
            $onProductionArray = array();
            $oilArray = array();
            $gasArray = array();
            $wellArray = explode('|', $permitValues->selected_well_name);

            array_push($wellArray, $permitValues->lease_name);
            $allRelatedPermits = Permit::where('lease_name', $permitValues->lease_name)->where('SurfaceLatitudeWGS84', '!=', null)->get();

            if ($request->isProducing == 'not-producing') {
                if ( $permitValues->selected_well_name != null ) {
                    $owners = LegalLease::where('permit_stitch_id',  $permitValues->permit_id)->get();
                    $permitNotes = PermitNote::where('lease_name', $permitValues->lease_name)->orderBy('id', 'DESC')->get();
                } else {
                    $owners = LegalLease::where('permit_stitch_id', $permitValues->permit_id)->get();
                    $permitNotes = PermitNote::where('lease_name', $leaseName)->orderBy('id', 'DESC')->get();
                }
            } else {
                $leases = MineralOwner::select('lease_name')->groupBy('lease_name')->get();
                $leaseArray = explode('|', $permitValues->selected_lease_name);
                array_push($leaseArray, $permitValues->lease_name);
                $allWells = WellRollUp::where('CountyParish', 'LIKE', '%'.$permitValues->county_parish .'%')->where('SurfaceHoleLatitudeWGS84', '!=', null)->orderBy('LeaseName', 'ASC')->get();
                if ( $permitValues->selected_lease_name != null ) {
                    $owners = MineralOwner::select('id', 'lease_name', 'assignee', 'wellbore_type', 'follow_up_date', 'owner', 'owner_address', 'owner_city', 'owner_zip', 'owner_decimal_interest', 'owner_interest_type')->whereIn('lease_name',  $leaseArray)->groupBy('owner')->orderBy('owner_decimal_interest', 'DESC')->get();
                    $permitNotes = PermitNote::select('notes')->where('lease_name', $permitValues->lease_name)->orderBy('id', 'DESC')->get();
                } else {
                    $owners = MineralOwner::where('lease_name', $permitValues->lease_name)->groupBy('owner')->orderBy('owner_decimal_interest', 'DESC')->get();
                    $permitNotes = PermitNote::select('notes')->where('lease_name', $leaseName)->orderBy('id', 'DESC')->get();
                }
            }

            if ($permitValues->selected_well_name == '' || $permitValues->selected_well_name == null) {
                $wells = WellRollUp::select('id', 'CountyParish','OperatorCompanyName','WellStatus','WellName', 'LeaseName', 'WellNumber', 'FirstProdDate', 'LastProdDate', 'CumOil', 'CumGas')->where('LeaseName', $permitValues->lease_name)->where('CountyParish', 'LIKE', '%'.$permitValues->county_parish .'%')->get();
                $selectWells = WellRollUp::where('CountyParish', 'LIKE', '%'.$permitValues->county_parish .'%')->groupBy('LeaseName')->orderBy('LeaseName', 'ASC')->get();

            } else {
                $wells = WellRollUp::select('id', 'CountyParish','OperatorCompanyName','WellStatus','WellName', 'LeaseName', 'WellNumber', 'FirstProdDate', 'LastProdDate', 'CumOil', 'CumGas')->whereIn('LeaseName', $wellArray)->where('CountyParish', 'LIKE', '%'.$permitValues->county_parish .'%')->get();
                $selectWells = WellRollUp::where('CountyParish', 'LIKE', '%'.$permitValues->county_parish .'%')->groupBy('LeaseName')->orderBy('LeaseName', 'ASC')->get();

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
            $count = count($wells);



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

    public function getOwnerInfo (Request $request) {

        try {
            if (in_array($request->interest_area, $this->txInterestAreas )) {
                $owner = MineralOwner::where('id', $request->id)->groupBy('owner')->first();
            } else {
                $owner = LegalLease::where('LeaseId', $request->LeaseId)->first();
            }

            $oilPrice = GeneralSetting::where('name', 'oil')->value('value');
            $gasPrice = GeneralSetting::where('name', 'gas')->value('value');

            $owner['oilPrice'] = $oilPrice;
            $owner['gasPrice'] = $gasPrice;

            return $owner;
        } catch ( \Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return false;
        }
    }

    public function getWellInfo(Request $request ) {

        try {
            $wellDetails = WellRollUp::where('id', $request->id)->get();

            return $wellDetails;
        } catch ( \Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }



    public function getNotes(Request $request) {
        try {
            $ownerInfo = MineralOwner::where('id', $request->ownerId)->first();
            return OwnerNote::where('owner_name', $ownerInfo->owner)->where('lease_name', $request->leaseName)->orderBy('id', 'DESC')->get();
        } catch( \Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
        }
    }

    public function updateNotes(Request $request) {
        try {
            $ownerInfo = MineralOwner::where('id', $request->ownerId)->get();

            MineralOwner::where('id', $request->ownerId)->update(['follow_up_date' => date('Y-m-d h:i:s A', strtotime('+1 day +19 hours'))]);

            $userName = Auth()->user()->name;
            $userId = Auth()->user()->id;
            $date = date('d/m/Y h:m:s', strtotime('-5 hours'));

            $newOwnerLeaseNote = new OwnerNote();

            $newOwnerLeaseNote->lease_name = $request->leaseName;
            $newOwnerLeaseNote->owner_name = $ownerInfo[0]->owner;
            $newOwnerLeaseNote->notes = '<div class="owner_note" id="owner_'.$newOwnerLeaseNote->id.'_'.$request->ownerId.'"><p style="font-size:14px; margin-bottom:0;"> '.$userName . ' | '. $date . '<span class="fas fa-trash delete_owner_note" id="delete_owner_note_'.$newOwnerLeaseNote->id.'_'.$request->ownerId.'" style="display:none; cursor:pointer; color:red; float:right;margin-right:5%;"></span></p>' . $request->notes .'<hr></div>';

            $newOwnerLeaseNote->save();

            OwnerNote::where('id', $newOwnerLeaseNote->id)
                ->update(['notes' => '<div class="owner_note" id="owner_'.$newOwnerLeaseNote->id.'_'.$request->ownerId.'"><p style="font-size:14px; margin-bottom:0;">'.$userName . ' | '. $date . '<span class="fas fa-trash delete_owner_note" id="delete_owner_note_'.$newOwnerLeaseNote->id.'_'.$request->ownerId.'" style="display: none; cursor:pointer; color:red; float:right;margin-right:3%;"></span></p>' . $request->notes .'<hr></div>']);

            $updatedOwnerNote = OwnerNote::where('owner_name', $ownerInfo[0]->owner)->where('lease_name', $request->leaseName)->orderBy('id', 'DESC')->get();


            MineralOwner::where('id', $request->ownerId)->update(['assignee' => $userId, 'follow_up_date' => date('Y-m-d h:i:s A', strtotime('+1 day +19 hours'))]);

            return $updatedOwnerNote;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function deleteNote(Request $request) {
        try {
            $ownerNote = OwnerNote::where('id', $request->id)->first();

            OwnerNote::destroy($request->id);

            $updatedOwnerNote = OwnerNote::where('owner_name', $ownerNote->owner_name)->where('lease_name', $ownerNote->lease_name)->orderBy('id', 'DESC')->get();

            return $updatedOwnerNote;
        } catch( Exception $e) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function updateAssignee(Request $request) {
        try {
            if ($request->assigneeId != 0) {
                MineralOwner::where('id', $request->ownerId)->update(['assignee' => $request->assigneeId, 'follow_up_date' => date('Y-m-d h:i:s A', strtotime('+1 day +19 hours'))]);
            } else {
                MineralOwner::where('id', $request->ownerId)->update(['assignee' => $request->assigneeId]);
            }
            return 'success';

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function updateFollowUp(Request $request) {
        try {

            if ($request->date != '') {
                $dateObj = str_replace('/', '-', $request->date);

                $dateArray = explode('-', $dateObj);

                $formattedDate = $dateArray[1] . '-' . $dateArray[0] . '-' . $dateArray[2];

                $date = date('Y-m-d h:i:s A', strtotime($formattedDate));
            } else {
                $date = null;
            }

            MineralOwner::where('id', $request->id)->update(['follow_up_date' => $date]);

            return 'success';

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function getOwnerNumbers(Request $request) {
        try {
            $ownerName = MineralOwner::where('id', $request->ownerId)->value('owner');

            $phoneNumbers = OwnerPhoneNumber::where('owner_name', $ownerName)->where('soft_delete', 0)->get();

            return $phoneNumbers;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function addPhone(Request $request) {
        try {

            $ownerName = MineralOwner::where('id', $request->ownerId)->value('owner');

            $newOwnerPhoneNumber = new OwnerPhoneNumber();

            $newOwnerPhoneNumber->phone_number = $request->phoneNumber;
            $newOwnerPhoneNumber->owner_name = $ownerName;
            $newOwnerPhoneNumber->phone_desc = $request->phoneDesc;
            $newOwnerPhoneNumber->owner_id = $request->ownerId;
            $newOwnerPhoneNumber->lease_name = $request->leaseName;

            $newOwnerPhoneNumber->save();

            return $newOwnerPhoneNumber;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function pushPhoneNumber(Request $request) {
        try {

            OwnerPhoneNumber::where('id', $request->id)
                ->update([
                    'is_pushed' => 1,
                    'reason' => $request->reason
                ]);

            return $request->id;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function softDeletePhone(Request $request) {
        try {
            OwnerPhoneNumber::where('id', $request->id)
                ->update(['soft_delete' => 1]);

            return $request->id;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function updateWellType(Request $request) {
        try {

            if ($request->wellType != 0) {
                MineralOwner::where('id', $request->ownerId)->update(
                    [
                        'wellbore_type' => $request->wellType,
                        'follow_up_date' => date('Y-m-d h:i:s A', strtotime('+1 day +19 hours'))
                    ]);
            } else {
                MineralOwner::where('id', $request->ownerId)->update(
                    [
                        'wellbore_type' => $request->wellType
                    ]);
            }

            return 'success';

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function updateAcreage(Request $request) {
        try {
            Permit::where('id', $request->id)
                ->update(['acreage' => $request->acreage]);

            return $request->id;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function updateOwnerPrice(Request $request) {
        try {
            MineralOwner::where('id', $request->id)
                ->update(['price' => $request->price]);

            return $request->id;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function updateLeaseName(Request $request) {
        try {
            Permit::where('id', $request->permitId)
                ->update(['selected_lease_name' => $request->leaseNames]);

            return $request->permitId;
        } catch ( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function updateWellName(Request $request) {
        try {
            Permit::where('id', $request->permitId)
                ->update(['selected_well_name' => $request->wellNames]);

            return $request->permitId;
        } catch ( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }
}
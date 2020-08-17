<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\GeneralSetting;
use App\MineralOwner;
use App\OwnerNote;
use App\OwnerPhoneNumber;
use App\Permit;
use App\PermitNote;
use App\User;
use App\WellRollUp;
use DateTime;
use App\LegalLease;
use Illuminate\Http\Request;
use JavaScript;
use Illuminate\Support\Facades\Log;

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
                    'allRelatedPermits' => $allRelatedPermits,
                    'permitId' => $permitId
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

    public function updateAssignee(Request $request) {
        try {
            if ($request->assigneeId != 0) {
                LegalLease::where('LeaseId', $request->LeaseId)->update(['assignee' => $request->assigneeId, 'follow_up_date' => date('Y-m-d h:i:s A', strtotime('+1 day +19 hours'))]);
            } else {
                LegalLease::where('LeaseId', $request->LeaseId)->update(['assignee' => $request->assigneeId]);
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

            LegalLease::where('LeaseId', $request->LeaseId)->update(['follow_up_date' => $date]);

            return 'success';

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
                LegalLease::where('LeaseId', $request->LeaseId)->update(
                    [
                        'wellbore' => $request->wellType,
                        'follow_up_date' => date('Y-m-d h:i:s A', strtotime('+1 day +19 hours'))
                    ]);
            } else {
                LegalLease::where('LeaseId', $request->LeaseId)->update(
                    [
                        'wellbore' => $request->wellType
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

    public function updateOwnerPrice(Request $request) {
        try {
            LegalLease::where('LeaseId', $request->LeaseId)
                ->update(['price' => $request->price]);

            return $request->id;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function getNotes(Request $request) {
        try {
            $ownerInfo = LegalLease::where('LeaseId', $request->LeaseId)->first();
            return OwnerNote::where('owner_name', $ownerInfo->Grantor)->where('lease_name', $request->leaseName)->orderBy('id', 'DESC')->get();
        } catch( \Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
        }
    }

    public function updateNotes(Request $request) {
        try {
            $ownerInfo = LegalLease::where('LeaseId', $request->LeaseId)->get();

            LegalLease::where('LeaseId', $request->LeaseId)->update(['follow_up_date' => date('Y-m-d h:i:s A', strtotime('+1 day +19 hours'))]);

            $userName = Auth()->user()->name;
            $userId = Auth()->user()->id;
            $date = date('d/m/Y h:m:s', strtotime('-5 hours'));

            $newOwnerLeaseNote = new OwnerNote();

            $newOwnerLeaseNote->lease_name = $request->leaseName;
            $newOwnerLeaseNote->owner_name = $ownerInfo[0]->Grantor;
            $newOwnerLeaseNote->notes = '<div class="owner_note" id="owner_'.$newOwnerLeaseNote->id.'_'.$request->LeaseId.'"><p style="font-size:14px; margin-bottom:0;"> '.$userName . ' | '. $date . '<span class="fas fa-trash delete_owner_note" id="delete_owner_note_'.$newOwnerLeaseNote->id.'_'.$request->LeaseId.'" style="display:none; cursor:pointer; color:red; float:right;margin-right:5%;"></span></p>' . $request->notes .'<hr></div>';
            $newOwnerLeaseNote->interest_areas = 'nm';

            $newOwnerLeaseNote->save();

            OwnerNote::where('id', $newOwnerLeaseNote->id)
                ->update(['notes' => '<div class="owner_note" id="owner_'.$newOwnerLeaseNote->id.'_'.$request->ownerId.'"><p style="font-size:14px; margin-bottom:0;">'.$userName . ' | '. $date . '<span class="fas fa-trash delete_owner_note" id="delete_owner_note_'.$newOwnerLeaseNote->id.'_'.$request->LeaseId.'" style="display: none; cursor:pointer; color:red; float:right;margin-right:3%;"></span></p>' . $request->notes .'<hr></div>']);

            $updatedOwnerNote = OwnerNote::where('owner_name', $ownerInfo[0]->Grantor)->where('lease_name', $request->leaseName)->orderBy('id', 'DESC')->get();


            LegalLease::where('id', $request->LeaseId)->update(['assignee' => $userId, 'follow_up_date' => date('Y-m-d h:i:s A', strtotime('+1 day +19 hours'))]);

            return $updatedOwnerNote;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function getOwnerInfo (Request $request) {

        try {
            $owner = LegalLease::where('LeaseId', $request->LeaseId)->first();

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

    public function getOwnerNumbers(Request $request) {
        try {
            $ownerName = LegalLease::where('LeaseId', $request->LeaseId)->value('Grantor');

            $phoneNumbers = OwnerPhoneNumber::where('owner_name', $ownerName)->where('soft_delete', 0)->where('is_pushed', 0)->get();

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

            $ownerName = LegalLease::where('leaseId', $request->LeaseId)->value('Grantor');

            $newOwnerPhoneNumber = new OwnerPhoneNumber();

            $newOwnerPhoneNumber->phone_number = $request->phoneNumber;
            $newOwnerPhoneNumber->owner_name = $ownerName;
            $newOwnerPhoneNumber->phone_desc = $request->phoneDesc;
            $newOwnerPhoneNumber->LeaseId = $request->LeaseId;
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
}

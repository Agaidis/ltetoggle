<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\GeneralSetting;
use App\MineralOwner;
use App\OwnerNote;
use App\OwnerPhoneNumber;
use App\PermitNote;
use App\User;
use App\WellOrigin;
use App\WellProductionDetail;
use Illuminate\Http\Request;
use App\Permit;
use Illuminate\Support\Facades\Log;
use DateTime;

class MineralOwnersController extends Controller
{
    public function index(Request $request) {
        $users = User::all();

        $operator = $request->operator;
        $permitId = $request->id;

        $permitValues = Permit::where('id', $permitId)->first();

        if (Auth()->user()->name === 'Billy Moreaux' && $permitValues->is_seen === 0) {
            Permit::where('id', $permitId)->update(['is_seen' => 1, 'toggle_status' => 'none']);
        }

        $leaseName = $permitValues->lease_name;

        try {
            $dateArray = array();
            $onProductionArray = array();
            $oilArray = array();
            $gasArray = array();
            $wells = WellOrigin::where('lease_name', $permitValues->lease_name)->where('county', $permitValues->county_parish)->get();

            $cumOil = 0;
            $cumGas = 0;
            foreach ($wells as $well) {
                if ($well->on_production_date != null)
                array_push($onProductionArray, $well->on_production_date);

                $wellDetails = WellProductionDetail::where('api10', $well->government_id)->get();

                foreach ( $wellDetails as $wellDetail) {
                    array_push($dateArray, $wellDetail->prod_date);

                    $cumOil = $cumOil + $wellDetail->cum_oil;
                    $cumGas = $cumGas + $wellDetail->cum_gas;
                    array_push($oilArray, $wellDetail->cum_oil);
                    array_push($gasArray, $wellDetail->cum_gas);
                }
            }

            if ( count($gasArray) > 0 ) {
                $totalGas = max($gasArray);
                $totalGasWithComma = number_format($totalGas);
                $cumGasWithComma = number_format($cumGas);
            } else {
                $totalGas = 0;
                $totalGasWithComma = 0;
            }

            if ( count($oilArray) > 0 ) {
                $totalOil = max($oilArray);
                $totalOilWithComma = number_format($totalOil);
                $cumOilWithComma = number_format($cumOil);
            } else {
                $totalOil = 0;
                $totalOilWithComma = 0;
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

            $leases = MineralOwner::groupBy('lease_name')->orderBy('lease_name', 'ASC')->get();

            $leaseArray = array();

            $count = count($wells);
            if ( $permitValues->selected_lease_name != null ) {

                $leaseArray = explode('|', $permitValues->selected_lease_name);

                $owners = MineralOwner::whereIn('lease_name',  $leaseArray)->groupBy('owner')->orderBy('owner_decimal_interest', 'DESC')->get();

                $permitNotes = PermitNote::where('lease_name', $permitValues->lease_name)->orderBy('id', 'DESC')->get();

            } else {

                $owners = MineralOwner::where('lease_name', $permitValues->lease_name)->groupBy('owner')->orderBy('owner_decimal_interest', 'DESC')->get();

                $permitNotes = PermitNote::where('lease_name', $leaseName)->orderBy('id', 'DESC')->get();

                if ($owners->isEmpty()) {
                    $leaseName = str_replace(['UNIT ', ' UNIT', ' - LANG 01 D', '-RUPPERT A SA 2'], ['', '', '', ''], $permitValues->lease_name);
                    $operator = str_replace(['UNIT ', ' UNIT', ' - LANG 01 D'], ['', '', ''], $request->operator);
                    $owners = MineralOwner::where('lease_name', $leaseName)->groupBy('owner')->orderBy('owner_decimal_interest', 'DESC')->get();

                }
            }

            return view('mineralOwner', compact(
                'owners',
                'permitValues',
                'leases',
                'leaseName',
                'leaseArray',
                'permitNotes',
                'users',
                'wells',
                'operator',
                'cumGasWithComma',
                'cumOilWithComma',
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

    public function getWellInfo(Request $request ) {

        try {
            $wellDetails = WellProductionDetail::where('api10', $request->govId)->get();

            return $wellDetails;
        } catch ( \Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function getOwnerInfo (Request $request) {

        try {
            $owner = MineralOwner::where('id', $request->id)->groupBy('owner')->first();

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

    public function getNotes(Request $request) {
        try {
            $ownerInfo = MineralOwner::where('id', $request->ownerId)->first();
            return OwnerNote::where('owner_name', $ownerInfo->owner)->where('lease_name', $request->leaseName)->orderBy('id', 'ASC')->get();
        } catch( \Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
        }
    }

    public function updateNotes(Request $request) {
        try {
            $ownerInfo = MineralOwner::where('id', $request->ownerId)->get();

            MineralOwner::where('id', $request->ownerId)->update(['follow_up_date' => date('Y-m-d', strtotime('+1 day +19 hours'))]);

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


            MineralOwner::where('id', $request->ownerId)->update(['assignee' => $userId, 'follow_up_date' => date('Y-m-d', strtotime('+1 day +19 hours'))]);

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
                MineralOwner::where('id', $request->ownerId)->update(['assignee' => $request->assigneeId, 'follow_up_date' => date('Y-m-d', strtotime('+1 day +19 hours'))]);
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
            MineralOwner::where('id', $request->id)->update(['follow_up_date' => $request->date]);

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
                        'follow_up_date' => date('Y-m-d', strtotime('+1 day +19 hours'))
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
}

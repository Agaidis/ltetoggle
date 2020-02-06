<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\MineralOwner;
use App\OwnerNote;
use App\OwnerPhoneNumber;
use App\User;
use App\WellOrigin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Permit;

class MineralOwnersController extends Controller
{
    public function index(Request $request) {
        $users = User::all();

        $operator = $request->operator;
        $permitId = $request->id;

        $permitValues = Permit::where('id', $permitId)->first();
        $leaseName = $permitValues->lease_name;

        try {
            $wells = WellOrigin::where('lease_name', $permitValues->lease_name)->where('current_operator', $permitValues->operator_alias)->get();
            $count = count($wells);

            $owners = MineralOwner::where('lease_name', $permitValues->lease_name)->groupBy('owner')->get();

            if ($owners->isEmpty()) {
                $leaseName = str_replace(['UNIT ', ' UNIT'], ['', ''], $permitValues->lease_name);
                $operator = str_replace(['UNIT ', ' UNIT'], ['', ''], $request->operator);
                $owners = MineralOwner::where('lease_name', $permitValues->lease_name)->groupBy('owner')->get();

            }

            return view('mineralOwner', compact('owners','permitValues', 'users', 'operator', 'leaseName', 'wells', 'count'));
        } catch( \Exception $e) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function getOwnerInfo (Request $request) {

        try {
            $owner = MineralOwner::where('id', $request->id)->groupBy('owner')->first();

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
            return OwnerNote::where('owner_name', $ownerInfo->owner)->where('lease_name', $request->leaseName)->first();
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

            $doesOwnerNoteExist = OwnerNote::where('owner_name', $ownerInfo[0]->owner)->where('lease_name', $request->leaseName)->get();
            $userName = Auth()->user()->name;
            $date = date('d/m/Y h:m:s', strtotime('-5 hours'));

            if ($doesOwnerNoteExist->isEmpty()) {
                $newOwnerLeaseNote = new OwnerNote();

                $newOwnerLeaseNote->lease_name = $request->leaseName;
                $newOwnerLeaseNote->owner_name = $ownerInfo[0]->owner;
                $newOwnerLeaseNote->notes = '<div id="owner_'.$newOwnerLeaseNote->id.'"><p style="font-size:14px; margin-bottom:0;"> '.$userName . ' | '. $date . '</p>' . $request->notes .'</div>';

                $newOwnerLeaseNote->save();

                OwnerNote::where('id', $newOwnerLeaseNote->id)
                         ->update(['notes' => '<div id="owner_'.$newOwnerLeaseNote->id.'"><p style="font-size:14px; margin-bottom:0;">'.$userName . ' | '. $date . '</p>' . $request->notes .'</div>']);

            } else {
                OwnerNote::where('owner_name', $ownerInfo[0]->owner)->where('lease_name', $request->leaseName)
                    ->update(['notes' => '<div id="owner_'.$doesOwnerNoteExist[0]->id.'"><p style="font-size:14px; margin-bottom:0;">'.$userName . ' | '. $date . '</p>' . $request->notes . '<hr>' . $doesOwnerNoteExist[0]->notes .'</div>']);
            }

            $updatedOwnerNote = OwnerNote::where('owner_name', $ownerInfo[0]->owner)->where('lease_name', $request->leaseName)->first();

            return $updatedOwnerNote;

        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function updateAssignee(Request $request) {
        try {
            MineralOwner::where('id', $request->ownerId)->update(['assignee' => $request->assigneeId, 'follow_up_date' => date('Y-m-d', strtotime('+1 day +19 hours'))]);

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
            $phoneNumbers = OwnerPhoneNumber::where('owner_name', $request->ownerName)->where('soft_delete', 0)->get();

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
            $newOwnerPhoneNumber = new OwnerPhoneNumber();

            $newOwnerPhoneNumber->phone_number = $request->phoneNumber;
            $newOwnerPhoneNumber->owner_name = $request->ownerName;
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
            MineralOwner::where('id', $request->ownerId)->update(
                [
                    'wellbore_type' => $request->wellType,
                    'follow_up_date' => date('Y-m-d', strtotime('+1 day +19 hours'))
                ]);

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
}

<?php

namespace App\Http\Controllers;

use App\MineralOwner;
use App\OwnerNote;
use App\OwnerPhoneNumber;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Permit;

class MineralOwnersController extends Controller
{
    public function index(Request $request) {
        $leaseNames = array();
        $users = User::all();

        $operator = $request->operator;
        $permitId = $request->id;

        $permitNotes = Permit::where('id', $permitId)->value('notes');
        $permitReportedOperator = Permit::where('id', $permitId)->value('reported_operator');
        $leaseName = Permit::where('id', $permitId)->value('lease_name');

        try {
            $ownerPhoneNumbers = DB::select('SELECT DISTINCT owner, phone_number, phone_desc, soft_delete FROM mineral_owners p
LEFT JOIN owner_phone_numbers o ON p.owner = o.owner_name WHERE o.phone_number != ""');


            for ($i = 0; $i < count($ownerPhoneNumbers); $i++) {
                Log::info($ownerPhoneNumbers);
            }

            $owners = MineralOwner::where('lease_name', $leaseName)->groupBy('owner')->get();

            if ($owners->isEmpty()) {
                $leaseName = str_replace(['UNIT ', ' UNIT'], ['', ''], $leaseName);
                $operator = str_replace(['UNIT ', ' UNIT'], ['', ''], $request->operator);
                $owners = MineralOwner::where('lease_name', $leaseName)->groupBy('owner')->get();

            }
//            if ($owners->isEmpty()) {
//                $owners = MineralOwner::where('operator_company_name', $request->reporter)->groupBy('owner')->get();
//            }

            if (!$owners->isEmpty()) {
                foreach ($owners as $owner) {
                    array_push($leaseNames, $owner->lease_name);
                }
                $leaseNames = array_unique($leaseNames);
            }

            return view('mineralOwner', compact('owners', 'leaseNames', 'users', 'operator', 'ownerPhoneNumbers', 'permitNotes', 'permitReportedOperator', 'leaseName'));
        } catch( \Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update Assignee Error', $e->getMessage());
            return 'error';
        }
    }

    public function getOwnerInfo (Request $request) {

        try {
            Log::info($request->id);
            $owner = MineralOwner::where('id', $request->id)->groupBy('owner')->first();

            return $owner;
        } catch ( \Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Error', $e->getMessage());
            return false;
        }
    }

    public function getNotes(Request $request) {
        try {
            $ownerInfo = MineralOwner::where('id', $request->ownerId)->first();
            return OwnerNote::where('owner_name', $ownerInfo->owner)->where('lease_name', $request->leaseName)->value('notes');
        } catch( \Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Error', $e->getMessage());
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
                $newOwnerLeaseNote->notes = '<p style="color:white; font-size:14px; margin-bottom:0;"> '.$userName . ' | '. $date . '</p>' . $request->notes;

                $newOwnerLeaseNote->save();

            } else {
                OwnerNote::where('owner_name', $ownerInfo[0]->owner)->where('lease_name', $request->leaseName)
                    ->update(['notes' => '<p style="color:white; font-size:14px; margin-bottom:0;">'.$userName . ' | '. $date . '</p>' . $request->notes . '<hr>' . $doesOwnerNoteExist[0]->notes]);
            }

            $updatedOwnerNote = OwnerNote::where('owner_name', $ownerInfo[0]->owner)->where('lease_name', $request->leaseName)->first();

            return $updatedOwnerNote->notes;

        } catch( Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Error', $e->getMessage());
            return 'error';
        }
    }

    public function updateAssignee(Request $request) {
        try {
            MineralOwner::where('id', $request->ownerId)->update(['assignee' => $request->assigneeId, 'follow_up_date' => date('Y-m-d', strtotime('+1 day +19 hours'))]);

            return 'success';

        } catch( Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update Assignee Error', $e->getMessage());
            return 'error';
        }
    }

    public function updateFollowUp(Request $request) {
        try {
            MineralOwner::where('id', $request->id)->update(['follow_up_date' => $request->date]);

            return 'success';

        } catch( Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update Assignee Error', $e->getMessage());
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
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update phone numbers Error', $e->getMessage());
            return 'error';
        }
    }

    public function softDeletePhone(Request $request) {
        try {
            $returnArray = array();

            OwnerPhoneNumber::where('phone_number', $request->phoneNumber)->
            where('phone_desc', $request->phoneDesc)->
            where('owner_name', $request->ownerName)->
            update(['soft_delete' => 1]);

            array_push($returnArray, $request->uniqueId);
            array_push($returnArray, $request->phoneNumber);
            array_push($returnArray, $request->ownerName);
            array_push($returnArray, $request->phoneDesc);

            return $returnArray;

        } catch( Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update phone numbers Error', $e->getMessage());
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
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update well type Error', $e->getMessage());
            return 'error';
        }
    }
}

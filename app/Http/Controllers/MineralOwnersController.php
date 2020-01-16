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

        try {
            $ownerPhoneNumbers = DB::select('SELECT DISTINCT owner, phone_number, phone_desc, soft_delete FROM mineral_owners p
LEFT JOIN owner_phone_numbers o ON p.owner = o.owner_name WHERE o.phone_number != ""');


            for ($i = 0; $i < count($ownerPhoneNumbers); $i++) {
                Log::info($ownerPhoneNumbers);
            }

            $owners = MineralOwner::where('lease_name', $request->operator)->groupBy('owner')->get();

            if ($owners->isEmpty()) {
                $operator = str_replace(['UNIT ', ' UNIT'], ['', ''], $request->operator);
                $owners = MineralOwner::where('lease_name', $operator)->groupBy('owner')->get();

            }
            if ($owners->isEmpty()) {
                $owners = MineralOwner::where('operator_company_name', $request->reporter)->groupBy('owner')->get();
            }

            if ($owners->isEmpty()) {

            } else {
                foreach ($owners as $owner) {
                    array_push($leaseNames, $owner->lease_name);
                }
                $leaseNames = array_unique($leaseNames);
            }

            return view('mineralOwner', compact('owners', 'leaseNames', 'users', 'operator', 'ownerPhoneNumbers', 'permitNotes', 'permitReportedOperator'));
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
            Log::info($request->ownerId);
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
            Log::info('Owner Id for updating Notes: ' . $request->ownerId);
            $ownerInfo = MineralOwner::where('id', $request->ownerId)->get();

            Log::info($ownerInfo[0]->owner);
            Log::info($request->leaseName);
            $doesOwnerNoteExist = OwnerNote::where('owner_name', $ownerInfo[0]->owner)->where('lease_name', $request->leaseName)->get();
            $userName = Auth()->user()->name;
            $date = date('Y/m/d h:m:s');

            if ($doesOwnerNoteExist->isEmpty()) {
                $newOwnerLeaseNote = new OwnerNote();

                $newOwnerLeaseNote->lease_name = $request->leaseName;
                $newOwnerLeaseNote->owner_name = $ownerInfo[0]->owner;
                $newOwnerLeaseNote->notes = '<b>User</b>: ' . $userName . ' <br><b>Date</b>: ' . $date . '<br>' . $request->notes;

                $newOwnerLeaseNote->save();

            } else {
                OwnerNote::where('owner_name', $ownerInfo[0]->owner)->where('lease_name', $request->leaseName)
                    ->update(['notes' => '<span style="color:black; font-size:18px;margin-left:20%;">'.$userName . ' | '. $date . '</span><br>' . $request->notes . '<br><hr>' . $doesOwnerNoteExist[0]->notes]);
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
            MineralOwner::where('id', $request->ownerId)->update(['assignee' => $request->assigneeId]);

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
                    'wellbore_type' => $request->wellType
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

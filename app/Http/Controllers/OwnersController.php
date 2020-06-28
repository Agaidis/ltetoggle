<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\MineralOwner;
use App\OwnerNote;
use App\OwnerPhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Permit;
use Illuminate\Support\Facades\Log;

class OwnersController extends Controller
{
    public function index(Request $request) {


        try {
            $ownerName = $request->ownerName;
            $permitObj = array();
            $noteArray = array();

            $ownerNotes = OwnerNote::where('owner_name', $ownerName)->get();
            $ownerPhoneNumbers = OwnerPhoneNumber::where('owner_name', $ownerName)->orderBy('soft_delete', 'ASC')->get();


            if (!$ownerNotes->isEmpty()) {
                $ownerLeaseData = DB::table('mineral_owners')
                    ->where('owner', $ownerName)
                    ->join('owner_notes', 'mineral_owners.owner', '=', 'owner_notes.owner_name')
                    ->select('owner_notes.*', 'mineral_owners.*')
                    ->groupBy('mineral_owners.lease_name')
                    ->get();
            } else {
                $ownerLeaseData = DB::table('mineral_owners')->where('owner', $ownerName)->get();
            }
            $count = 0;

            foreach ($ownerLeaseData as $ownerLease) {
                $leaseNote = '';

                $permits = Permit::where('lease_name', $ownerLease->lease_name)->first();
                $notes = OwnerNote::where('lease_name', $ownerLease->lease_name)->get();

                if (is_object(($permits))) {
                    $permitObj[$count]['lease_name'] = $permits->lease_name;
                    $permitObj[$count]['reported_operator'] = $permits->reported_operator;
                    $permitObj[$count]['id'] = $permits->id;
                } else {
                    $permitObj[$count]['lease_name'] = '';
                    $permitObj[$count]['reported_operator'] = '';
                    $permitObj[$count]['id'] = '';
                }

                if (!$notes->isEmpty()) {
                    foreach ($notes as $note) {
                        $leaseNote .= $note->notes;
                    }
                    $noteArray[$count]['lease_name'] = $notes[0]->lease_name;
                    $noteArray[$count]['notes'] = $leaseNote;
                } else {
                    $noteArray[$count]['lease_name'] = '';
                    $noteArray[$count]['notes'] = '';

                }
                $count++;
            }

            return view('owner', compact('ownerName', 'ownerNotes', 'ownerPhoneNumbers','ownerLeaseData', 'permitObj', 'noteArray' ));
        } catch( \Exception $e) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }
}

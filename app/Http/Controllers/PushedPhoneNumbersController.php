<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\MineralOwner;
use App\OwnerPhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PushedPhoneNumbersController extends Controller
{
    public function index()
    {
        try {
            $ownerArray = array();
           // $pushedPhoneNumbers = OwnerPhoneNumber::where('is_pushed', 1)->get();

//            $pushedPhoneNumbers = DB::table('owner_phone_numbers')
//                ->where('is_pushed', 1)
//                ->join('mineral_owners', function ($join) {
//                $join->on('owner_phone_numbers.owner_name', '=', 'mineral_owners.owner')
//                    ->select('owner_phone_numbers.*', 'mineral_owners.owner_address', 'mineral_owners.owner_city', 'mineral_owners.owner_state', 'mineral_owners.owner_zip');
//            })->groupBy('mineral_owners.owner')->get();
            $pushedPhoneNumbers = DB::table('owner_phone_numbers')
                ->where('is_pushed', 1)
                ->join('mineral_owners', 'owner_phone_numbers.owner_name', '=', 'mineral_owners.owner')
                ->select('owner_phone_numbers.*', 'mineral_owners.owner_address', 'mineral_owners.owner_city', 'mineral_owners.owner_state', 'mineral_owners.owner_zip')
                ->groupBy('mineral_owners.owner')->get();

            foreach ($pushedPhoneNumbers as $pushedPhoneNumber) {
                array_push($ownerArray, $pushedPhoneNumber->owner_name);
            }



            return view('pushedPhoneNumbers', compact('pushedPhoneNumbers', 'ownerArray'));
        } catch (\Exception $e) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
        }
    }

    public function insertPhoneNumber(Request $request) {
        try {
            $ownerName = OwnerPhoneNumber::where('id', $request->id)->value('owner_name');

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

    public function updatePhoneNumber(Request $request) {
        try {

            OwnerPhoneNumber::where('id', $request->id)
                ->update([
                    'is_pushed' => 0,
                    'phone_number' => $request->phoneNumber,
                    'phone_desc' => $request->phoneDesc
                ]);

            return view('pushedPhoneNumbers', compact('phoneNumbers'));
        } catch (\Exception $e) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
        }
    }
}

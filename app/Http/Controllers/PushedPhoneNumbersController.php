<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\OwnerPhoneNumber;
use Illuminate\Http\Request;

class PushedPhoneNumbersController extends Controller
{
    public function index()
    {
        try {
            $phoneNumbers = OwnerPhoneNumber::where('is_pushed', 1)->get();

            return view('pushedPhoneNumbers', compact('phoneNumbers'));
        } catch (\Exception $e) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
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

<?php

namespace App\Http\Controllers;

use App\OwnerNote;
use App\OwnerPhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OwnersController extends Controller
{
    public function index(Request $request) {
        $ownerName = $request->ownerName;

        try {
            $ownerNotes = OwnerNote::where('owner_name', $ownerName)->get();
            $ownerPhoneNumbers = OwnerPhoneNumber::where('owner_name', $ownerName)->get();

            return view('owner', compact('ownerName', 'ownerNotes', 'ownerPhoneNumbers' ));
        } catch( \Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Owner Page Error', $e->getMessage());
            return 'error';
        }
    }
}

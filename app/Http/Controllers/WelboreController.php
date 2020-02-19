<?php

namespace App\Http\Controllers;

use App\MineralOwner;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class WelboreController extends Controller
{

    private $apiManager;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->apiManager = new APIManager();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        try {
            $users = User::all();
            $highPriorityProspects = DB::select('select * from mineral_owners WHERE assignee = '. Auth::user()->id .' AND wellbore_type != "0" ORDER BY FIELD(wellbore_type, "4", "3", "2", "1" ), wellbore_type DESC');

            $owners = DB::table('mineral_owners')
                ->where('follow_up_date', '!=', NULL )
                ->where('assignee', Auth::user()->id)
                ->where(function ($query) {
                    $query->where('wellbore_type', '=', NULL)
                        ->orWhere('wellbore_type', '=', '0');
                })->orderBy('follow_up_date')->get();

            $ownerPhoneNumbers = DB::select('SELECT DISTINCT owner, phone_number, phone_desc, soft_delete FROM mineral_owners p
LEFT JOIN owner_phone_numbers o ON p.owner = o.owner_name WHERE o.phone_number != ""');

            return view('welbore', compact('owners','highPriorityProspects', 'ownerPhoneNumbers', 'users'));
        } catch( \Exception $e) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Toggle Update Assignee Error', $e->getMessage());
            return view('dashboard');
        }



    }
}

<?php

namespace App\Http\Controllers;

use App\ErrorLog;
use App\Permit;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin');
    }


    public function updatePermits(Request $request) {

        try {
            $this->runUpdatePermits($request->county);

            return 'success';
        } catch ( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine() . ' File: ' . $e->getFile();

            $errorMsg->save();
            return 'error';
        }
    }

    public function runUpdatePermits($county) {
        $apiManager = new APIManager();
        $token = $apiManager->getToken();
        // Start date
        $date = '2020-02-01';
        // End date
        $end_date = date('Y-m-d');

        try {
            do {
                Log::info($date);
                $permits = $apiManager->getPermits($county, $token->access_token, $date);
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));

                if ($permits != null && $permits != '' && isset($permits)) {
                    $decodedPermits = json_decode($permits);
                    Log::info(count($decodedPermits));

                    for ($i = 0; $i < count($decodedPermits); $i++) {
                        Log::info($decodedPermits[$i]->PermitStatus);
                            if ($decodedPermits[$i]->BottomHoleLongitudeWGS84 != '' && $decodedPermits[$i]->BottomHoleLongitudeWGS84 != null) {
                                $btmLatLng = '{"lng": ' . $decodedPermits[$i]->BottomHoleLongitudeWGS84 . ', "lat": ' . $decodedPermits[$i]->BottomHoleLatitudeWGS84 . "}";
                            } else {
                                $btmLatLng = '';
                            }
                            $doesPermitExist = Permit::where('permit_id', $decodedPermits[$i]->PermitID)->get();

                            if ($doesPermitExist->isEmpty()) {

                                $newPermit = new Permit();

                                $newPermit->permit_id = $decodedPermits[$i]->PermitID;
                                $newPermit->notes = '';
                                $newPermit->abstract = $decodedPermits[$i]->Abstract;
                                $newPermit->approved_date = $decodedPermits[$i]->ApprovedDate;
                                $newPermit->block = $decodedPermits[$i]->Block;
                                $newPermit->county_parish = $decodedPermits[$i]->CountyParish;
                                $newPermit->drill_type = $decodedPermits[$i]->DrillType;
                                $newPermit->lease_name = $decodedPermits[$i]->LeaseName;
                                $newPermit->operator_alias = $decodedPermits[$i]->OperatorAlias;
                                $newPermit->permit_type = $decodedPermits[$i]->PermitType;
                                $newPermit->range = $decodedPermits[$i]->Range;
                                $newPermit->section = $decodedPermits[$i]->Section;
                                $newPermit->state = $decodedPermits[$i]->StateProvince;
                                $newPermit->survey = $decodedPermits[$i]->Survey;
                                $newPermit->township = $decodedPermits[$i]->Township;
                                $newPermit->well_type = $decodedPermits[$i]->WellType;
                                $newPermit->btm_geometry = $btmLatLng;
                                $newPermit->reported_operator = $decodedPermits[$i]->ReportedOperator;
                                $newPermit->permit_number = $decodedPermits[$i]->PermitNumber;
                                $newPermit->permit_status = $decodedPermits[$i]->PermitStatus;
                                $newPermit->district = $decodedPermits[$i]->District;
                                $newPermit->created_date = $decodedPermits[$i]->CreatedDate;
                                $newPermit->submitted_date = $decodedPermits[$i]->SubmittedDate;

                                $newPermit->save();


                            } else {
                                Permit::where('permit_id', $decodedPermits[$i]->PermitID)
                                    ->update([
                                        'abstract' => $decodedPermits[$i]->Abstract,
                                        'approved_date' => $decodedPermits[$i]->ApprovedDate,
                                        'block' => $decodedPermits[$i]->Block,
                                        'county_parish' => $decodedPermits[$i]->CountyParish,
                                        'drill_type' => $decodedPermits[$i]->DrillType,
                                        'lease_name' => $decodedPermits[$i]->LeaseName,
                                        'operator_alias' => $decodedPermits[$i]->OperatorAlias,
                                        'permit_type' => $decodedPermits[$i]->PermitType,
                                        'range' => $decodedPermits[$i]->Range,
                                        'section' => $decodedPermits[$i]->Section,
                                        'state' => $decodedPermits[$i]->StateProvince,
                                        'survey' => $decodedPermits[$i]->Survey,
                                        'township' => $decodedPermits[$i]->Township,
                                        'well_type' => $decodedPermits[$i]->WellType,
                                        'btm_geometry' => $btmLatLng,
                                        'reported_operator' => $decodedPermits[$i]->ReportedOperator,
                                        'permit_number' => $decodedPermits[$i]->PermitNumber,
                                        'permit_status' => $decodedPermits[$i]->PermitStatus,
                                        'district' => $decodedPermits[$i]->District,
                                        'created_date' => $decodedPermits[$i]->CreatedDate,
                                        'submitted_date' => $decodedPermits[$i]->SubmittedDate]);
                            }

                    }
                }
            } while (strtotime($date) <= strtotime($end_date));
            return 'success';
        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine() . ' File: ' . $e->getFile();

            $errorMsg->save();
            return 'error';
        }
    }
}

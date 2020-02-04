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
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine();

            $errorMsg->save();
            return 'error';
        }
    }

    public function runUpdatePermits($county) {
        $apiManager = new APIManager();
        $token = $apiManager->getToken();

        $permits = $apiManager->getPermits($county, $token->access_token);

        try {
            foreach (json_decode($permits) as $permit => $data) {
                if ($data->PermitStatus == 'Active') {
                    if ($data->BottomHoleLongitudeWGS84 != '' && $data->BottomHoleLongitudeWGS84 != null) {
                        $btmLatLng = '{"lng": ' . $data->BottomHoleLongitudeWGS84 . ', "lat": ' . $data->BottomHoleLatitudeWGS84 . "}";
                    } else {
                        $btmLatLng = '';
                    }

                    $doesPermitExist = Permit::where('permit_id', $data->PermitID)->get();

                    if ($doesPermitExist->isEmpty()) {

                        $newPermit = new Permit();

                        $newPermit->permit_id = $data->PermitID;
                        $newPermit->notes = '';
                        $newPermit->abstract = $data->Abstract;
                        $newPermit->approved_date = $data->ApprovedDate;
                        $newPermit->block = $data->Block;
                        $newPermit->county_parish = $data->CountyParish;
                        $newPermit->drill_type = $data->DrillType;
                        $newPermit->lease_name = $data->LeaseName;
                        $newPermit->operator_alias = $data->OperatorAlias;
                        $newPermit->permit_type = $data->PermitType;
                        $newPermit->range = $data->Range;
                        $newPermit->section = $data->Section;
                        $newPermit->state = $data->StateProvince;
                        $newPermit->survey = $data->Survey;
                        $newPermit->township = $data->Township;
                        $newPermit->well_type = $data->WellType;
                        $newPermit->btm_geometry = $btmLatLng;
                        $newPermit->reported_operator = $data->ReportedOperator;
                        $newPermit->permit_number = $data->PermitNumber;
                        $newPermit->permit_status = $data->PermitStatus;
                        $newPermit->district = $data->District;
                        $newPermit->created_date = $data->CreatedDate;

                        $newPermit->save();

                    } else {
                        Permit::where('permit_id', $data->PermitID)
                            ->update([
                                'abstract' => $data->Abstract,
                                'approved_date' => $data->ApprovedDate,
                                'block' => $data->Block,
                                'county_parish' => $data->CountyParish,
                                'drill_type' => $data->DrillType,
                                'lease_name' => $data->LeaseName,
                                'operator_alias' => $data->OperatorAlias,
                                'permit_type' => $data->PermitType,
                                'range' => $data->Range,
                                'section' => $data->Section,
                                'state' => $data->StateProvince,
                                'survey' => $data->Survey,
                                'township' => $data->Township,
                                'well_type' => $data->WellType,
                                'btm_geometry' => $btmLatLng,
                                'reported_operator' => $data->ReportedOperator,
                                'permit_number' => $data->PermitNumber,
                                'permit_status' => $data->PermitStatus,
                                'district' => $data->District,
                                'created_date' => $data->CreatedDate]);
                    }
                }
            }
            return 'success';
        } catch( Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Permit Toggle Error', $e->getMessage());
            return 'error';
        }
    }
}

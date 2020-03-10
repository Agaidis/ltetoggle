<?php

namespace App\Console\Commands;

use App\ErrorLog;
use App\Http\Controllers\APIManager;
use App\Permit;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetPermits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:getPermits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will loop through by date and grab all permits by all counties';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $apiManager = new APIManager();
        $token = $apiManager->getToken();
        $counties = ['ATASCOSA','BEE','DEWITT','GONZALES','KARNES','LIVE OAK','LAVACA','WILSON'];
        $end_date = date('Y-m-d');

        try {
            foreach ($counties as $county) {
                $date = '2020-01-27';
                do {
                    $permits = $apiManager->getPermits($county, $token->access_token, $date);
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                    if ($permits != null && $permits != '' && isset($permits)) {
                        $decodedPermits = json_decode($permits);
                        for ($i = 0; $i < count($decodedPermits); $i++) {
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
                                $newPermit->is_seen = 0;

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
            }
            return 'success';
        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine() . ' File: ' . $e->getFile();

            $errorMsg->save();
            return 'error';
        }
    }

}

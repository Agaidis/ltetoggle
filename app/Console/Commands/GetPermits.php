<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\APIManager;
use App\Permit;
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
    protected $description = 'This will get the latest permits';

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
        $decodedPermits = [];
        $token = $apiManager->getToken();

        $permits = $apiManager->getPermits($token->access_token);

        foreach ($permits as $permit => $stuff) {
            $decodedPermits[$permit] = json_decode($stuff);
        }

        try {
           foreach ($decodedPermits as $permit => $data) {
                $count = count($data);
                Log::info($count);
                for ($i = 0; $i < $count; $i++) {
                    $doesPermitExist = Permit::where('permit_id', $data[$i]->PermitID)->get();

                    if ($doesPermitExist->isEmpty()) {
                        $newPermit = new Permit();

                        $newPermit->permit_id = $data[$i]->PermitID;
                        $newPermit->notes = '';
                        $newPermit->abstract = $data[$i]->Abstract;
                        $newPermit->approved_date = $data[$i]->ApprovedDate;
                        $newPermit->block = $data[$i]->Block;
                        $newPermit->county_parish = $data[$i]->CountyParish;
                        $newPermit->drill_type = $data[$i]->DrillType;
                        $newPermit->lease_name = $data[$i]->LeaseName;
                        $newPermit->operator_alias = $data[$i]->OperatorAlias;
                        $newPermit->permit_type = $data[$i]->PermitType;
                        $newPermit->range = $data[$i]->Range;
                        $newPermit->section = $data[$i]->Section;
                        $newPermit->state = $data[$i]->StateProvince;
                        $newPermit->survey = $data[$i]->Survey;
                        $newPermit->township = $data[$i]->Township;
                        $newPermit->well_type = $data[$i]->WellType;


                        $newPermit->save();

                    } else {
                        Permit::where('permit_id', $data[$i]->PermitID)
                            ->update([
                                'abstract' => $data[$i]->Abstract,
                                'approved_date' => $data[$i]->ApprovedDate,
                                'block' => $data[$i]->Block,
                                'county_parish' => $data[$i]->CountyParish,
                                'drill_type' => $data[$i]->DrillType,
                                'lease_name' => $data[$i]->LeaseName,
                                'operator_alias' => $data[$i]->OperatorAlias,
                                'permit_type' => $data[$i]->PermitType,
                                'range' => $data[$i]->Range,
                                'section' => $data[$i]->Section,
                                'state' => $data[$i]->StateProvince,
                                'survey' => $data[$i]->Survey,
                                'township' => $data[$i]->Township,
                                'well_type' => $data[$i]->WellType]);
                    }
                }
           }
        } catch( Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Permit Toggle Error', $e->getMessage());
            return 'error';
        }
    }
}

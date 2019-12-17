<?php

namespace App\Console\Commands;

use App\Http\Controllers\APIManager;
use App\Lease;
use App\Permit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class GetLeases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:getLeases';

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

        $leases = $apiManager->getLandtracLeases($token->access_token);

        foreach ($leases as $lease => $stuff) {
            $decodedPermits[$lease] = json_decode($stuff);
        }

        try {
            foreach ($decodedPermits as $lease => $data) {
                $count = count($data);
                for ($i = 0; $i < $count; $i++) {
                        if (strpos($data[$i]->Geometry, 'MULTIPOLYGON(((')) {
                            $geometry = str_replace(['MULTIPOLYGON(((', ')))'], ['', ''], $data[$i]->Geometry);
                        } else {
                            $geometry = str_replace(['POLYGON((', '))'], ['', ''], $data[$i]->Geometry);

                            $geometryArray = explode(',', $geometry);

                            for ($k = 0; $k < count($geometryArray); $k++) {
                                $geometryArray[$k] = '{"lng":' . $geometryArray[$k];
                                $geometryArray[$k] = str_replace(' ', ', "lat": ', $geometryArray[$k]);

                                $geometryArray[$k] .= '}';
                            }
                            $geometry = implode(', ', $geometryArray);
                        }

                        $doesLeaseExist = Lease::where('lease_id', $data[$i]->LeaseId)->get();
                        if ($doesLeaseExist->isEmpty()) {
                            $newLease = new Lease();

                            $newLease->lease_id = $data[$i]->LeaseId;
                            $newLease->notes = '';
                            $newLease->area_acres = $data[$i]->AreaAcres;
                            $newLease->county_parish = $data[$i]->CountyParish;
                            $newLease->expiration_primary_term = $data[$i]->ExpirationofPrimaryTerm;
                            $newLease->grantee = $data[$i]->Grantee;
                            $newLease->grantee_alias = $data[$i]->GranteeAlias;
                            $newLease->grantor = $data[$i]->Grantor;
                            $newLease->grantor_address = $data[$i]->GrantorAddress;
                            $newLease->state = $data[$i]->State;
                            $newLease->geometry = $geometry;
                            $newLease->abstract = '';
                            $newLease->block = '';
                            $newLease->section = '';
                            $newLease->survey = '';
                            $newLease->spatial_assignee = $data[$i]->SpatialAssignee;

                            $newLease->save();

                        } else {
                            Lease::where('lease_id', $data[$i]->LeaseId)
                                ->update([
                                    'area_acres' => $data[$i]->AreaAcres,
                                    'county_parish' => $data[$i]->CountyParish,
                                    'expiration_primary_term' => $data[$i]->ExpirationofPrimaryTerm,
                                    'grantee' => $data[$i]->Grantee,
                                    'grantee_alias' => $data[$i]->GranteeAlias,
                                    'grantor' => $data[$i]->Grantor,
                                    'grantor_address' => $data[$i]->GrantorAddress,
                                    'state' => $data[$i]->State,
                                    'geometry' => $geometry,
                                    'abstract' => '',
                                    'block' => '',
                                    'section' => '',
                                    'survey' => '',
                                    'spatial_assignee' => $data[$i]->SpatialAssignee]);
                        }
                    }
        //        }
            }
        } catch( Exception $e ) {
            Log::info($e->getMessage());
            Log::info($e->getCode());
            Log::info($e->getLine());
            mail('andrew.gaidis@gmail.com', 'Lease Toggle Error', $e->getMessage());
            return 'error';
        }
    }
}

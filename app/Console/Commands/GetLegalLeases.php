<?php

namespace App\Console\Commands;

use App\ErrorLog;
use App\Http\Controllers\APIManager;
use App\LegalLease;
use Exception;
use Illuminate\Console\Command;

class GetLegalLeases extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:getLegalLeases';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets the Legal Leases';

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

        try {
         //   $eagleInterestCountiesArray = array('ATASCOSA%20\(TX\)', 'BEE%20\(TX\)', 'DEWITT%20\(TX\)', 'GONZALES%20\(TX\)', 'KARNES%20\(TX\)', 'LIVE%20OAK%20\(TX\)', 'LAVACA%20\(TX\)', 'WILSON%20\(TX\)');
            $nvxInterestCountiesArray = array('DAWSON%20\(TX\)', 'GAINES%20\(TX\)', 'BORDEN%20\(TX\)', 'CRANE%20\(TX\)', 'ECTOR%20\(TX\)', 'STERLING%20\(TX\)', 'MITCHELL%20\(TX\)', 'JEFF%20DAVIS%20\(TX\)');
            $nvxByApprovedDate = array('LEA%20\(NM\)', 'EDDY%20\(NM\)');
               $eagleInterestCountiesArray = array('GONZALES%20\(TX\)');

            $this->getCountyLeaseData($eagleInterestCountiesArray);

           // $this->getCountyLeaseData($nvxInterestCountiesArray);

          //  $this->getCountyLeaseData($nvxByApprovedDate);


            return 'success';
        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine() . ' File: ' . $e->getFile();

            $errorMsg->save();
            return 'error';
        }
    }

    public function getCountyLeaseData ($counties) {

        try {
            $apiManager = new APIManager();

            $token = $apiManager->getToken();

            foreach ($counties as $county) {
                $url = '';

                do {
                    $leases = $apiManager->getLegalLeases($county, $token->access_token, $url);
                    if (isset($leases[1])) {
                        $url = trim($leases[1]);
                    } else {
                        $url = '';
                    }

                    if ($leases[0] != null && $leases[0] != '' && isset($leases[0])) {

                        $decodedLeases = json_decode($leases[0]);

                        for ($i = 0; $i < count($decodedLeases); $i++) {

                            LegalLease::updateOrCreate(['LeaseId' => $decodedLeases[$i]->LeaseId],
                                [
                                    'MappingID' => $decodedLeases[$i]->MappingID,
                                    'AreaAcres' => $decodedLeases[$i]->AreaAcres,
                                    'Abstract' => $decodedLeases[$i]->Abstract,
                                    'AbstractNo' => $decodedLeases[$i]->AbstractNo,
                                    'Block' => $decodedLeases[$i]->Block,
                                    'CountyParish' => $decodedLeases[$i]->CountyParish,
                                    'Created' => $decodedLeases[$i]->Created,
                                    'Geometry' => $decodedLeases[$i]->Geometry,
                                    'LatitudeWGS84' => $decodedLeases[$i]->LatitudeWGS84,
                                    'LongitudeWGS84' => $decodedLeases[$i]->LongitudeWGS84,
                                    'Grantee' => $decodedLeases[$i]->Grantee,
                                    'GranteeAddress' => $decodedLeases[$i]->GranteeAddress,
                                    'GranteeAlias' => $decodedLeases[$i]->GranteeAlias,
                                    'Grantor' => $decodedLeases[$i]->Grantor,
                                    'GrantorAddress' => $decodedLeases[$i]->GrantorAddress,
                                    'MaxDepth' => $decodedLeases[$i]->MaxDepth,
                                    'MinDepth' => $decodedLeases[$i]->MinDepth,
                                    'Range' => $decodedLeases[$i]->Range,
                                    'Section' => $decodedLeases[$i]->Section,
                                    'Township' => $decodedLeases[$i]->Township,
                                    'RecordDate' => $decodedLeases[$i]->RecordDate
                                ]);
                        }
                    }
                } while ($url != '');
            }
            return 'success';

        } catch ( Exception $e) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine() . ' File: ' . $e->getFile();

            $errorMsg->save();
            return 'error';
        }
    }
}

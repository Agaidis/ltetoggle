<?php

namespace App\Console\Commands;

use App\ErrorLog;
use App\Http\Controllers\APIManager;
use App\Jobs\LegalLeases;
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
            $eagleInterestCountiesArray = array('ATASCOSA%20\(TX\)', 'BEE%20\(TX\)', 'DEWITT%20\(TX\)', 'GONZALES%20\(TX\)', 'KARNES%20\(TX\)', 'LIVE%20OAK%20\(TX\)', 'LAVACA%20\(TX\)', 'WILSON%20\(TX\)');
            $nvxInterestCountiesArray = array('DAWSON%20\(TX\)', 'GAINES%20\(TX\)', 'BORDEN%20\(TX\)', 'CRANE%20\(TX\)', 'ECTOR%20\(TX\)', 'STERLING%20\(TX\)', 'MITCHELL%20\(TX\)', 'JEFF%20DAVIS%20\(TX\)');
            $nvxByApprovedDate = array('LEA%20\(NM\)', 'EDDY%20\(NM\)');

            $this->getCountyLeaseData($eagleInterestCountiesArray);

            $this->getCountyLeaseData($nvxInterestCountiesArray);

            $this->getCountyLeaseData($nvxByApprovedDate);


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

                    if ($this->leases[0] != null && $this->leases[0] != '' && isset($this->leases[0])) {

                        $decodedLeases = json_decode($this->leases[0]);

                        for ($i = 0; $i < count($decodedLeases); $i++) {
                            LegalLeases::dispatch($decodedLeases[$i]);
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

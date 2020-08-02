<?php

namespace App\Console\Commands;

use App\ErrorLog;
use App\WellRollUp;
use Illuminate\Console\Command;
use App\Permit;

class DetermineProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'determine:production';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will determine what permits have wells and what do not';

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
        $newMexicoCounties = array('LEA', 'EDDY');

        try {

            foreach ($newMexicoCounties as $newMexicoCounty) {

                $permits = Permit::where('county_parish', $newMexicoCounty)->get();

                foreach ($permits as $permit) {
                    $wells = WellRollUp::where('leaseName', $permit->lease_name)->where('WellStatus', 'ACTIVE')->get();

                    if ($wells->isEmpty()) {
                        if ($permit->selected_lease_name != '' && $permit->selected_lease_name != null) {
                            $selectedLeaseNameWells = WellRollUp::where('leaseName', $permit->selected_lease_name)->where('WellStatus', 'ACTIVE')->get();

                            if ($selectedLeaseNameWells->isEmpty()) {
                                Permit::where('id', $permit->id)
                                    ->update(['is_producing' => 0]);
                            } else {
                                Permit::where('id', $permit->id)
                                    ->update(['is_producing' => 1]);
                            }
                        } else {
                            Permit::where('id', $permit->id)
                                ->update(['is_producing' => 0]);
                        }
                    } else {
                        Permit::where('id', $permit->id)
                            ->update(['is_producing' => 1]);
                    }
                }
            }
            return true;
        } catch ( \Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine() . ' File: ' . $e->getFile();

            $errorMsg->save();
            return 'error';
        }
    }
}

<?php

namespace App\Jobs;

use App\LegalLease;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class LegalLeases implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $leases;
    /**
     * Create a new job instance.
     *
     * @var $leases
     * @return void
     */
    public function __construct($leases)
    {
        $this->leases = $leases;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->leases[0] != null && $this->leases[0] != '' && isset($this->leases[0])) {

            $decodedLeases = json_decode($this->leases[0]);

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
    }
}

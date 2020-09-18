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

    protected $decodedLeases;
    /**
     * Create a new job instance.
     *
     * @var $leases
     * @return void
     */
    public function __construct($decodedLeases)
    {
        $this->decodedLeases = $decodedLeases;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        LegalLease::updateOrCreate(['LeaseId' => $this->decodedLeases->LeaseId],
            [
                'MappingID' => $this->decodedLeases->MappingID,
                'AreaAcres' => $this->decodedLeases->AreaAcres,
                'Abstract' => $this->decodedLeases->Abstract,
                'AbstractNo' => $this->decodedLeases->AbstractNo,
                'Block' => $this->decodedLeases->Block,
                'CountyParish' => $this->decodedLeases->CountyParish,
                'Created' => $this->decodedLeases->Created,
                'Geometry' => $this->decodedLeases->Geometry,
                'LatitudeWGS84' => $this->decodedLeases->LatitudeWGS84,
                'LongitudeWGS84' => $this->decodedLeases->LongitudeWGS84,
                'Grantee' => $this->decodedLeases->Grantee,
                'GranteeAddress' => $this->decodedLeases->GranteeAddress,
                'GranteeAlias' => $this->decodedLeases->GranteeAlias,
                'Grantor' => $this->decodedLeases->Grantor,
                'GrantorAddress' => $this->decodedLeases->GrantorAddress,
                'MaxDepth' => $this->decodedLeases->MaxDepth,
                'MinDepth' => $this->decodedLeases->MinDepth,
                'Range' => $this->decodedLeases->Range,
                'Section' => $this->decodedLeases->Section,
                'Township' => $this->decodedLeases->Township,
                'RecordDate' => $this->decodedLeases->RecordDate
            ]);
    }
}

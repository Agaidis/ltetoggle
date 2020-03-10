<?php

namespace App\Console\Commands;

use App\ErrorLog;
use App\OwnerPhoneNumber;
use App\Permit;
use App\WellOrigin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:DailyReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a daily email of the new leases, phone numbers and wells.';

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
            $lastDay = date('Y-m-d H:i:s',strtotime('-24 hours'));

            $permits = Permit::where('created_at', '>=', $lastDay)->get();
            $ownerPhoneNumbers = OwnerPhoneNumber::where('created_at', '>=', $lastDay)->get();
            $wells = WellOrigin::where('created_at', '>=', $lastDay)->get();

            $leaseTable = '<table><tbody>';
            $phoneNumbersTable = '<table><tbody>';
            $wellsTable = '<table><tbody>';

            foreach ($permits as $permit) {
                $leaseTable .= '<tr><th width="30%">Entity</th><th>Details</th></tr>';
                $leaseTable .= '<tr><td width="30%">Permit Id</td><td>' . $permit->permit_id . '</td></tr>';
                $leaseTable .= '<tr><td width="30%">County</td><td>' . $permit->county_parish . '</td></tr>';
                $leaseTable .= '<tr><td width="30%">Lease Name</td><td>' . $permit->lease_name . '</td></tr>';
                $leaseTable .= '<tr><td width="30%">County</td><td>' . $permit->permit_type . '</td></tr>';
                $leaseTable .= '<tr><td width="30%">Lease Name</td><td>' . $permit->permit_status . '</td></tr>';
            }

            foreach ($ownerPhoneNumbers as $ownerPhoneNumber) {
                $phoneNumbersTable .= '<tr><th width="30%">Entity</th><th>Details</th></tr>';
                $phoneNumbersTable .= '<tr><td width="30%">Owner Name</td><td>' . $ownerPhoneNumber->owner_name . '</td></tr>';
                $phoneNumbersTable .= '<tr><td width="30%">Phone Description</td><td>' . $ownerPhoneNumber->phone_desc . '</td></tr>';
                $phoneNumbersTable .= '<tr><td width="30%">Phone Number</td><td>' . $ownerPhoneNumber->phone_number . '</td></tr>';
            }

            foreach ($wells as $well) {
                $wellsTable .= '<tr><th width="30%">Entity</th><th>Details</th></tr>';
                $wellsTable .= '<tr><td width="30%">Well Id</td><td>' . $well->uid . '</td></tr>';
                $wellsTable .= '<tr><td width="30%">County</td><td>' . $well->county . '</td></tr>';
                $wellsTable .= '<tr><td width="30%">Current Operator</td><td>' . $well->current_operator . '</td></tr>';
                $wellsTable .= '<tr><td width="30%">Current Status</td><td>' . $well->current_status . '</td></tr>';
                $wellsTable .= '<tr><td width="30%">Lease Name</td><td>' . $well->lease_name . '</td></tr>';

            }

            $leaseTable .= '</tbody></table>';
            $phoneNumbersTable .= '</tbody></table>';
            $wellsTable .= '</tbody></table>';

            Log::info($leaseTable);

            $subject = 'Toggle Daily Report';
            $headers = 'From: Toggle Report\r\n';
            $headers .= 'MIME-Version: 1.0\r\n';
            $headers .= 'Content-Type: text/html; charset=ISO-8859-1\r\n';
            $message = '<html><body>';
            $message .= '<style>
            h1{
                background-color: #c0392b;
                color: white;
                padding: 5px;
            }
            th, td {
                padding: 15px;
                text-align: left;
            }
            th {
                text-align: left;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }
            table, th, td {
                border: 1px solid #000000;
            }
            tr:nth-child(even) {background-color: #dedede}</style>';
            $message .= '<h2>New Leases</h2>';
            $message .= $leaseTable;
            $message .= '<h2>New Phone Numbers</h2>';
            $message .= $phoneNumbersTable;
            $message .= '<h2>New Wells</h2>';
            $message .= $wellsTable;
            $message .= '</body></html>';
            mail('andrewg@lexathonenergy.com', $subject, $message, $headers);
        } catch( Exception $e ) {
            $errorMsg = new ErrorLog();
            $errorMsg->payload = $e->getMessage() . ' Line #: ' . $e->getLine() . ' File: ' . $e->getFile();

            $errorMsg->save();
            return 'error';
        }
    }
}

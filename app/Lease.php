<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{

    protected $fillable = [
        'lease_id', 'notes'
    ];

    protected $table = 'leases';
}

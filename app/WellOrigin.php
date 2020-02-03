<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WellOrigin extends Model
{
    protected $fillable = [
        'county', 'current_operator', 'current_status', 'lease_name', 'uid'
    ];

    protected $table = 'well_origins';
}

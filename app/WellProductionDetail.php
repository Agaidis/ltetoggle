<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WellProductionDetail extends Model
{
    protected $fillable = [
        'api10', 'api14'
    ];

    protected $table = 'well_production_details';
}

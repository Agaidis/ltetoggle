<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{

    protected $fillable = [
        'lease_id', 'notes', 'area_acres', 'county_parish', 'expiration_primary_term', 'grantee', 'grantee_alias', 'grantor', 'grantor_address', 'state', 'geometry', 'assignee',
        'block', 'abstract', 'survey', 'section'
    ];

    protected $table = 'leases';
}

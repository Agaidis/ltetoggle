<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLeaseData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->string('area_acres')->nullable();
            $table->string('county_parish')->nullable();
            $table->string('expiration_primary_term')->nullable();
            $table->string('grantee')->nullable();
            $table->string('grantee_alias')->nullable();
            $table->string('grantor')->nullable();
            $table->string('grantor_address')->nullable();
            $table->string('state')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->dropColumn('area_acres');
            $table->dropColumn('county_parish');
            $table->dropColumn('expiration_primary_term');
            $table->dropColumn('grantee');
            $table->dropColumn('grantee_alias');
            $table->dropColumn('grantor');
            $table->dropColumn('grantor_address');
        });
    }
}

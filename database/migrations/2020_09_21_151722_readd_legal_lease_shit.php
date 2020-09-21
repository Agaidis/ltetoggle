<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ReaddLegalLeaseShit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('legal_leases', function (Blueprint $table) {
            $table->string('permit_stitch_id')->nullable();
            $table->string('assignee')->nullable();
            $table->string('wellbore')->nullable();
            $table->string('follow_up_date')->nullable();
            $table->string('interest_areas')->nullable();
            $table->string('price')->nullable();
            $table->string('interest_type')->nullable();
            $table->string('decimal_interest')->nullable();
            $table->string('monthly_revenue')->nullable();
            $table->string('pricing_per_nma')->nullable();
            $table->string('net_mineral_acres')->nullable();
            $table->string('user_interest_type')->default('RI');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('legal_leases', function (Blueprint $table) {
            //
        });
    }
}

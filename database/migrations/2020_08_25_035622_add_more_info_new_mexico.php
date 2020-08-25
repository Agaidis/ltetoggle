<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreInfoNewMexico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('legal_leases', function (Blueprint $table) {
            $table->string('interest_type')->nullable();
            $table->string('decimal_interest')->nullable();
            $table->string('monthly_revenue')->nullable();
            $table->string('pricing_per_nma')->nullable();
            $table->string('net_mineral_acres')->nullable();
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

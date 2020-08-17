<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWellProductionDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('well_production_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('api10')->nullable();
            $table->string('api14')->nullable();
            $table->integer('avg_gas')->nullable();
            $table->integer('avg_oil')->nullable();
            $table->integer('avg_wtr')->nullable();
            $table->integer('cum_gas')->nullable();
            $table->integer('cum_oil')->nullable();
            $table->integer('cum_wtr')->nullable();
            $table->integer('days')->nullable();
            $table->integer('gas')->nullable();
            $table->string('prod_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('well_production_details');
    }
}

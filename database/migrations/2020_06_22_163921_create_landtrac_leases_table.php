<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLandtracLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landtrac_leases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('LeaseId');
            $table->string('AreaAcres')->nullable();
            $table->string('State')->nullable();
            $table->string('CountyParish')->nullable();
            $table->string('CreatedDate')->nullable();
            $table->string('Geometry')->nullable();
            $table->string('Grantee')->nullable();
            $table->string('GranteeAddress')->nullable();
            $table->string('Grantor')->nullable();
            $table->string('GrantorAddress')->nullable();
            $table->string('CentroidLatitude')->nullable();
            $table->string('CentroidLongitude')->nullable();
            $table->string('MaxDepth')->nullable();
            $table->string('MinDepth')->nullable();

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
        Schema::dropIfExists('landtrac_leases');
    }
}

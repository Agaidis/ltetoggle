<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLegalLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('legal_leases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('LeaseId');
            $table->string('AreaAcres')->nullable();
            $table->string('Abstract')->nullable();
            $table->string('AbstractNo')->nullable();
            $table->string('Block')->nullable();
            $table->string('CountyParish')->nullable();
            $table->string('Created')->nullable();
            $table->string('Geometry')->nullable();
            $table->string('LatitudeWGS84')->nullable();
            $table->string('LongitudeWGS84')->nullable();
            $table->string('Grantee')->nullable();
            $table->string('GranteeAddress')->nullable();
            $table->string('GranteeAlias')->nullable();
            $table->string('Grantor')->nullable();
            $table->string('GrantorAddress')->nullable();
            $table->string('MaxDepth')->nullable();
            $table->string('MinDepth')->nullable();
            $table->string('Range')->nullable();
            $table->string('Section')->nullable();
            $table->string('Township')->nullable();
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
        Schema::dropIfExists('legal_leases');
    }
}

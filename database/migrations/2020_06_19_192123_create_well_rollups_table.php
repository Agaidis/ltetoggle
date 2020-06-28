<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWellRollupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('well_rollups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('API14')->nullable();
            $table->string('CountyParish')->nullable();
            $table->string('OperatorCompanyName')->nullable();
            $table->string('ReportedOperator')->nullable();
            $table->string('WellName')->nullable();
            $table->string('WellNumber')->nullable();
            $table->string('WellStatus')->nullable();
            $table->string('CompletionDate')->nullable();
            $table->string('CreatedDate')->nullable();
            $table->string('CumGas')->nullable();
            $table->string('CumOil')->nullable();
            $table->string('DrillType')->nullable();
            $table->string('LeaseName')->nullable();
            $table->string('MeasuredDepth')->nullable();
            $table->string('Abstract')->nullable();
            $table->string('Range')->nullable();
            $table->string('District')->nullable();
            $table->string('Section')->nullable();
            $table->string('Township')->nullable();
            $table->string('SurfaceHoleLatitudeWGS84')->nullable();
            $table->string('SurfaceHoleLongitudeWGS84')->nullable();
            $table->string('BottomHoleLatitudeWGS84')->nullable();
            $table->string('BottomHoleLongitudeWGS84')->nullable();
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
        Schema::dropIfExists('well_rollups');
    }
}

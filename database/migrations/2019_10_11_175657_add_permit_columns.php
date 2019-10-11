<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPermitColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->string('abstract')->nullable();
            $table->string('approved_date')->nullable();
            $table->string('block')->nullable();
            $table->string('county_parish')->nullable();
            $table->string('drill_type')->nullable();
            $table->string('lease_name')->nullable();
            $table->string('operator_alias')->nullable();
            $table->string('permit_type')->nullable();
            $table->string('range')->nullable();
            $table->string('section')->nullable();
            $table->string('state')->nullable();
            $table->string('survey')->nullable();
            $table->string('township')->nullable();
            $table->string('well_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->dropColumn('abstract');
            $table->dropColumn('approved_date');
            $table->dropColumn('block');
            $table->dropColumn('county_parish');
            $table->dropColumn('drill_type');
            $table->dropColumn('lease_name');
            $table->dropColumn('operator_alias');
            $table->dropColumn('permit_type');
            $table->dropColumn('range');
            $table->dropColumn('section');
            $table->dropColumn('state');
            $table->dropColumn('survey');
            $table->dropColumn('township');
            $table->dropColumn('well_type');
        });
    }
}

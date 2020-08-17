<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCumProdGas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mineral_owners', function (Blueprint $table) {
            $table->string('cum_prod_gas')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mineral_owners', function (Blueprint $table) {
            $table->dropColumn('cum_prod_gas');
        });
    }
}

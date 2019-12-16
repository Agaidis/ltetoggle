<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMineralOwnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mineral_owners', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('owner')->nullable();
            $table->string('owner_address')->nullable();
            $table->string('owner_city')->nullable();
            $table->string('owner_state')->nullable();
            $table->string('owner_zip')->nullable();
            $table->string('owner_decimal_interest')->nullable();
            $table->string('owner_interest_type')->nullable();
            $table->string('appraisal_year')->nullable();
            $table->string('operator_company_name')->nullable();
            $table->string('lease_name')->nullable();
            $table->string('rrc_lease_number')->nullable();
            $table->string('county')->nullable();
            $table->string('state')->nullable();
            $table->string('tax_value')->nullable();
            $table->longText('lease_description')->nullable();
            $table->string('first_prod_date')->nullable();
            $table->string('last_prod_date')->nullable();
            $table->string('cum_prod_oil')->nullable();
            $table->string('active_well_count')->nullable();
            $table->string('notes')->nullable();
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
        Schema::dropIfExists('mineral_owners');
    }
}

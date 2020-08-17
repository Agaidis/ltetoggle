<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWellOrigins extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('well_origins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('county')->nullable();
            $table->string('current_operator')->nullable();
            $table->string('current_status')->nullable();
            $table->string('lease_name')->nullable();
            $table->string('uid')->nullable();
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
        Schema::dropIfExists('well_origins');
    }
}

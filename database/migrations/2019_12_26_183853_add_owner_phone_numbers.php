<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOwnerPhoneNumbers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mineral_owners', function (Blueprint $table) {
            $table->string('cell')->nullable();
            $table->string('work')->nullable();
            $table->string('home')->nullable();
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
            $table->dropColumn('cell');
            $table->dropColumn('work');
            $table->dropColumn('home');
        });
    }
}

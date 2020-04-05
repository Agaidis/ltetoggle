<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddToggleStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('permits', function (Blueprint $table) {
            $table->string('toggle_status')->nullable()->default('none');
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
            $table->dropColumn('toggle_status');
        });
    }
}

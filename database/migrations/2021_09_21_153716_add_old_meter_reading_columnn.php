<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOldMeterReadingColumnn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cons_meter_mod', function (Blueprint $table) {
            $table->integer('old_meter_reading');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cons_meter_mod', function (Blueprint $table) {
            $table->dropColumn('old_meter_reading');
        });
    }
}

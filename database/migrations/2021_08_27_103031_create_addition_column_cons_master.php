<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdditionColumnConsMaster extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cons_master', function (Blueprint $table) {
            $table->smallInteger('employee')->default(0);
            $table->smallInteger('temp_connect')->default(0);
            $table->smallInteger('senior_citizen')->default(0);
            $table->smallInteger('institutional')->default(0);
            $table->smallInteger('metered')->default(1);
            $table->smallInteger('govt')->default(0);
            $table->smallInteger('main_accnt')->default(1);
            $table->smallInteger('large_load')->default(0);
            $table->smallInteger('main_account')->nullable();
            $table->smallInteger('nearest_cons')->nullable();
            $table->string('occupant')->nullable();
            $table->string('board_res')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cons_master', function (Blueprint $table) {
            $table->dropColumn('employee');
            $table->dropColumn('voting_address');
            $table->dropColumn('temp_connect');
            $table->dropColumn('senior_citizen');
            $table->dropColumn('institutional');
            $table->dropColumn('metered');
            $table->dropColumn('govt');
            $table->dropColumn('main_accnt');
            $table->dropColumn('large_load');
            $table->dropColumn('main_account');
            $table->dropColumn('nearest_cons');
            $table->dropColumn('occupant');
            $table->dropColumn('board_res');
        });
    }
}

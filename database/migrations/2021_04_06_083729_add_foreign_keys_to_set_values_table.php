<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSetValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('set_values', function (Blueprint $table) {
            $table->foreign('set_id', 'set_values_ibfk_1')->references('id')->on('sets')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('user_id', 'set_values_ibfk_2')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('set_values', function (Blueprint $table) {
            $table->dropForeign('set_values_ibfk_1');
            $table->dropForeign('set_values_ibfk_2');
        });
    }
}

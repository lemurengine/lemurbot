<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToMapValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('map_values', function (Blueprint $table) {
            $table->foreign('map_id', 'map_values_ibfk_1')->references('id')->on('maps')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('user_id', 'map_values_ibfk_2')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('map_values', function (Blueprint $table) {
            $table->dropForeign('map_values_ibfk_1');
            $table->dropForeign('map_values_ibfk_2');
        });
    }
}

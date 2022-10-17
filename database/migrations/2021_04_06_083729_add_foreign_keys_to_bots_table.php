<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->foreign('language_id', 'bots_ibfk_1')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('user_id', 'bots_ibfk_2')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->dropForeign('bots_ibfk_1');
            $table->dropForeign('bots_ibfk_2');
        });
    }
}

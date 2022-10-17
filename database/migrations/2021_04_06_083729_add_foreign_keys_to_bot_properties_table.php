<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBotPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bot_properties', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_properties_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('user_id', 'bot_properties_ibfk_2')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bot_properties', function (Blueprint $table) {
            $table->dropForeign('bot_properties_ibfk_1');
            $table->dropForeign('bot_properties_ibfk_2');
        });
    }
}

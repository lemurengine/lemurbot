<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBotRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bot_ratings', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_ratings_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('conversation_id', 'bot_ratings_ibfk_2')->references('id')->on('conversations')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bot_ratings', function (Blueprint $table) {
            $table->dropForeign('bot_ratings_ibfk_1');
            $table->dropForeign('bot_ratings_ibfk_2');
        });
    }
}

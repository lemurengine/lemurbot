<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBotWordSpellingGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bot_word_spelling_groups', function (Blueprint $table) {
            $table->foreign('user_id', 'bot_word_spelling_groups_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('bot_id', 'bot_word_spelling_groups_ibfk_2')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('word_spelling_group_id', 'bot_word_spelling_groups_ibfk_3')->references('id')->on('word_spelling_groups')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bot_word_spelling_groups', function (Blueprint $table) {
            $table->dropForeign('bot_word_spelling_groups_ibfk_1');
            $table->dropForeign('bot_word_spelling_groups_ibfk_2');
            $table->dropForeign('bot_word_spelling_groups_ibfk_3');
        });
    }
}

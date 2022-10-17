<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWordSpellingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('word_spellings', function (Blueprint $table) {
            $table->foreign('user_id', 'word_spellings_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('word_spelling_group_id', 'word_spellings_ibfk_2')->references('id')->on('word_spelling_groups')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('word_spellings', function (Blueprint $table) {
            $table->dropForeign('word_spellings_ibfk_1');
            $table->dropForeign('word_spellings_ibfk_2');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKeysToWordSpellingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('word_spellings', function (Blueprint $table) {
            $table->index(['word','word_spelling_group_id'], 'word_spellings_ibix_1');
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
            $table->dropIndex('word_spellings_ibix_1');
        });
    }
}

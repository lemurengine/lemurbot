<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWordTransformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('word_transformations', function (Blueprint $table) {
            $table->foreign('user_id', 'word_transformations_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('language_id', 'word_transformations_ibfk_2')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('word_transformations', function (Blueprint $table) {
            $table->dropForeign('word_transformations_ibfk_1');
            $table->dropForeign('word_transformations_ibfk_2');
        });
    }
}

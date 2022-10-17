<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordSpellingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('word_spellings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->unsignedBigInteger('word_spelling_group_id')->index('word_spelling_group_id');
            $table->string('slug')->unique();
            $table->string('word');
            $table->string('replacement');
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['word_spelling_group_id', 'word'], 'word_spellings_ibuq_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('word_spellings');
    }
}

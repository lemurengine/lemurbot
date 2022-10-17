<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmptyResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empty_responses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bot_id')->index('bot_id');
            $table->string('input');
            $table->string('slug')->unique();
            $table->timestamps();
            $table->softDeletes();
            $table->integer('occurrences')->default(1);
            $table->unique(['bot_id', 'input'], 'empty_responses_ibuq_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empty_responses');
    }
}

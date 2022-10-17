<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEmptyResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empty_responses', function (Blueprint $table) {
            $table->foreign('bot_id', 'empty_responses_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empty_responses', function (Blueprint $table) {
            $table->dropForeign('empty_responses_ibfk_1');
        });
    }
}

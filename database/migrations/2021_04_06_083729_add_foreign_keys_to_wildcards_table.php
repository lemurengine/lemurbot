<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToWildcardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wildcards', function (Blueprint $table) {
            $table->foreign('conversation_id', 'wildcards_ibfk_1')->references('id')->on('conversations')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wildcards', function (Blueprint $table) {
            $table->dropForeign('wildcards_ibfk_1');
        });
    }
}

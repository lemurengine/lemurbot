<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToNormalizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('normalizations', function (Blueprint $table) {
            $table->foreign('language_id', 'normalizations_ibfk_1')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('normalizations', function (Blueprint $table) {
            $table->dropForeign('normalizations_ibfk_1');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToTurnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('turns', function (Blueprint $table) {
            $table->foreign('conversation_id', 'turns_ibfk_1')->references('id')->on('conversations')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('category_id', 'turns_ibfk_2')->references('id')->on('categories')->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreign('client_category_id', 'turns_ibfk_3')->references('id')->on('client_categories')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('turns', function (Blueprint $table) {
            $table->dropForeign('turns_ibfk_1');
            $table->dropForeign('turns_ibfk_2');
            $table->dropForeign('turns_ibfk_3');
        });
    }
}

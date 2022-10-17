<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToClientCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('client_categories', function (Blueprint $table) {
            $table->foreign('client_id', 'client_categories_ibfk_1')->references('id')->on('clients')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('bot_id', 'client_categories_ibfk_2')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('turn_id', 'client_categories_ibfk_3')->references('id')->on('turns')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('client_categories', function (Blueprint $table) {
            $table->dropForeign('client_categories_ibfk_1');
            $table->dropForeign('client_categories_ibfk_2');
            $table->dropForeign('client_categories_ibfk_3');
        });
    }
}

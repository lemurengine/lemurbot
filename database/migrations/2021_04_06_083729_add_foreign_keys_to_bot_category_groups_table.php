<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToBotCategoryGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bot_category_groups', function (Blueprint $table) {
            $table->foreign('user_id', 'bot_category_groups_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('bot_id', 'bot_category_groups_ibfk_2')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('category_group_id', 'bot_category_groups_ibfk_3')->references('id')->on('category_groups')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bot_category_groups', function (Blueprint $table) {
            $table->dropForeign('bot_category_groups_ibfk_1');
            $table->dropForeign('bot_category_groups_ibfk_2');
            $table->dropForeign('bot_category_groups_ibfk_3');
        });
    }
}

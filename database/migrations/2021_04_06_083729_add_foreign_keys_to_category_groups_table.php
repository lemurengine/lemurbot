<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCategoryGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_groups', function (Blueprint $table) {
            $table->foreign('user_id', 'category_groups_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('language_id', 'category_groups_ibfk_2')->references('id')->on('languages')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_groups', function (Blueprint $table) {
            $table->dropForeign('category_groups_ibfk_1');
            $table->dropForeign('category_groups_ibfk_2');
        });
    }
}

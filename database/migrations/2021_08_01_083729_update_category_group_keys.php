<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCategoryGroupKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * drop and rebuild keys with cascade - cascade
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_groups', function (Blueprint $table) {
            $table->dropUnique('category_groups_name_unique');
            $table->unique(['name', 'user_id'], 'category_groups_name_user_id_unique');
        });
    }

    public function down()
    {
        Schema::table('category_groups', function (Blueprint $table) {
            $table->dropUnique('category_groups_name_user_id_unique');
            $table->unique(['name'],'category_groups_name_unique');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTablesWithSection extends Migration
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
            $table->unsignedBigInteger('section_id')->nullable()->index('section_id')->after('language_id');
        });

        Schema::table('bot_properties', function (Blueprint $table) {
            $table->unsignedBigInteger('section_id')->nullable()->index('section_id')->after('user_id');
        });

        Schema::table('category_groups', function (Blueprint $table) {
            $table->foreign('section_id', 'category_groups_ibfk_3')->references('id')->on('sections')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
        Schema::table('bot_properties', function (Blueprint $table) {
            $table->foreign('section_id', 'bot_properties_ibfk3')->references('id')->on('sections')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    public function down()
    {
        Schema::table('bot_properties', function (Blueprint $table) {
            $table->dropForeign('bot_properties_ibfk3');
        });

        Schema::table('category_groups', function (Blueprint $table) {
            $table->dropForeign('category_groups_ibfk_3');
        });


        Schema::table('category_groups', function (Blueprint $table) {
            $table->dropColumn('section_id');
        });
        Schema::table('bot_properties', function (Blueprint $table) {
            $table->dropColumn('section_id');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveObsoleteFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('turns', function (Blueprint $table) {
            $table->dropColumn('is_display');
        });

        Schema::table('bots', function (Blueprint $table) {
            $table->dropColumn('is_master');
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
            $table->boolean('is_display')->default(0)->after('source');
        });

        Schema::table('bots', function (Blueprint $table) {
            $table->boolean('is_master')->default(0)->after('status');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateBotTable extends Migration
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
        Schema::table('bots', function (Blueprint $table) {
            $table->string('critical_category_group')->nullable()->after('default_response');
        });
    }

    public function down()
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->dropColumn('critical_category_group');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateTurnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('turns', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_turn_id')->nullable()->index('parent_turn_id')->after('client_category_id');
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
            $table->dropColumn('parent_turn_id');
        });
    }
}

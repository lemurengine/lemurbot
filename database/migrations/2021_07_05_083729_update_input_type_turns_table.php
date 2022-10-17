<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInputTypeTurnsTable extends Migration
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
        Schema::table('turns', function (Blueprint $table) {
            $table->text('input')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('turns', function (Blueprint $table) {
            $table->string('input')->nullable()->change();
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMapValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('map_values', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('map_id')->index('map_id');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->string('slug')->unique();
            $table->string('word');
            $table->string('value');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('map_values');
    }
}

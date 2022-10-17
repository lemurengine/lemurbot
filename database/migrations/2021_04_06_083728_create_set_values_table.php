<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('set_values', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('set_id')->index('set_id');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->string('slug')->unique();
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
        Schema::dropIfExists('set_values');
    }
}

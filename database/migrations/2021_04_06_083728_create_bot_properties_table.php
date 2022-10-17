<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bot_id')->index('bot_id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->string('slug')->unique();
            $table->string('name', 100);
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
        Schema::dropIfExists('bot_properties');
    }
}

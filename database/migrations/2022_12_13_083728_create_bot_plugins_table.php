<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotPluginsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_plugins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->unsignedBigInteger('bot_id')->index('bot_id');
            $table->unsignedBigInteger('plugin_id')->index('plugin_id');
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['bot_id', 'plugin_id'], 'bot_plugins_ibuq_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_plugins');
    }
}

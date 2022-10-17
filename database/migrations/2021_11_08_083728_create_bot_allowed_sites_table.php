<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotAllowedSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_allowed_sites', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bot_id')->index('bot_id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->string('slug')->unique();
            $table->string('website_url');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('bot_allowed_sites', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_allowed_sites_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('user_id', 'bot_allowed_sites_ibfk_2')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bot_allowed_sites', function (Blueprint $table) {
            $table->dropForeign('bot_allowed_sites_ibfk_1');
            $table->dropForeign('bot_allowed_sites_ibfk_2');
        });

        Schema::dropIfExists('bot_allowed_sites');

    }
}

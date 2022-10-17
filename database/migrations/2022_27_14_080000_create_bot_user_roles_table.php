<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_user_roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->string('role');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('bot_user_roles', function (Blueprint $table) {
            $table->foreign('user_id', 'bot_user_roles_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bot_user_roles', function (Blueprint $table) {
            $table->dropForeign('bot_user_roles_ibfk_1');
        });

        Schema::dropIfExists('bot_user_roles');
    }
}

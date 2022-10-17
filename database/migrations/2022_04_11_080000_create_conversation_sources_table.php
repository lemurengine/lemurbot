<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_sources', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('conversation_id')->index('conversation_id');
            $table->string('slug')->unique();
            $table->text('params')->nullable();
            $table->string('user')->nullable();
            $table->string('ip')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('referer')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('conversation_sources', function (Blueprint $table) {
            $table->foreign('conversation_id', 'conversation_sources_ibfk_1')->references('id')->on('conversations')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversation_sources', function (Blueprint $table) {
            $table->dropForeign('conversation_sources_ibfk_1');
        });

        Schema::dropIfExists('conversation_sources');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id')->index('client_id');
            $table->unsignedBigInteger('bot_id')->index('bot_id');
            $table->unsignedBigInteger('turn_id')->index('turn_id');
            $table->string('slug')->unique();
            $table->string('pattern');
            $table->text('template');
            $table->string('tag');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['client_id', 'bot_id', 'pattern'], 'client_categories_ibix_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_categories');
    }
}

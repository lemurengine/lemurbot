<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTurnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('turns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('conversation_id')->index('conversation_id');
            $table->unsignedBigInteger('category_id')->nullable()->index('category_id');
            $table->unsignedBigInteger('client_category_id')->nullable()->index('client_category_id');
            $table->string('slug')->unique();
            $table->string('input')->nullable();
            $table->text('output')->nullable();
            $table->string('status', 1)->default('O');
            $table->string('source')->default('human')->index('source');
            $table->boolean('is_display')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('turns');
    }
}

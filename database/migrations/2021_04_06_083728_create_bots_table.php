<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('language_id')->default(1)->index('language_id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->string('slug')->unique();
            $table->string('name', 50)->unique();
            $table->string('summary', 75);
            $table->text('description')->nullable();
            $table->string('default_response')->default('I do not a response for that');
            $table->text('lemurtar_url')->nullable();
            $table->string('image');
            $table->enum('status', ['H', 'T', 'A'])->default('A');
            $table->boolean('is_master')->default(0);
            $table->boolean('is_public')->default(0);
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
        Schema::dropIfExists('bots');
    }
}

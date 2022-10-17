<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordSpellingGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('word_spelling_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('language_id')->default(1)->index('language_id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->string('slug')->unique();
            $table->string('name')->unique();
            $table->text('description');
            $table->boolean('is_master')->default(1);
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
        Schema::dropIfExists('word_spelling_groups');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->unsignedBigInteger('category_group_id')->index('category_group_id');
            $table->string('slug')->unique();
            $table->string('pattern');
            $table->string('regexp_pattern');
            $table->string('first_letter_pattern', 1);
            $table->string('topic')->nullable();
            $table->string('regexp_topic')->nullable();
            $table->string('first_letter_topic', 1)->nullable();
            $table->string('that')->nullable();
            $table->string('regexp_that')->nullable();
            $table->string('first_letter_that', 1)->nullable();
            $table->text('template');
            $table->enum('status', ['H', 'T', 'A'])->default('A');
            $table->softDeletes();
            $table->timestamps();
            $table->index(['pattern', 'topic', 'that'], 'categories_ibix_4');
            $table->index(['pattern', 'first_letter_that'], 'categories_ibix_1');
            $table->index(['status', 'regexp_pattern', 'regexp_that', 'first_letter_pattern'], 'categories_ibix_2');
            $table->index(['status', 'regexp_pattern', 'regexp_that', 'first_letter_pattern', 'first_letter_that'], 'categories_ibix_3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}

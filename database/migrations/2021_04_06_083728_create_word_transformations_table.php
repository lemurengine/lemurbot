<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWordTransformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('word_transformations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->unsignedBigInteger('language_id')->default(1)->index('language_id');
            $table->string('slug')->unique();
            $table->string('first_person_form', 50);
            $table->string('second_person_form', 50);
            $table->string('third_person_form', 50);
            $table->string('third_person_form_female', 50)->nullable();
            $table->string('third_person_form_male', 50)->nullable();
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
        Schema::dropIfExists('word_transformations');
    }
}

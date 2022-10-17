<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNormalizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('normalizations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('language_id')->index('language_id');
            $table->string('slug')->unique();
            $table->string('original_value');
            $table->string('normalized_value');
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
        Schema::dropIfExists('normalizations');
    }
}

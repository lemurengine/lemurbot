<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachineLearntCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machine_learnt_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('client_id')->index('client_id');
            $table->unsignedBigInteger('bot_id')->index('bot_id');
            $table->unsignedBigInteger('turn_id')->nullable()->index('turn_id');
            $table->string('slug')->unique();
            $table->string('pattern');
            $table->text('template');
            $table->string('topic')->nullable();
            $table->string('that')->nullable();
            $table->text('example_input')->nullable();
            $table->text('example_output')->nullable();
            $table->string('category_group_slug')->nullable();
            $table->integer('occurrences')->default(1);
            $table->softDeletes();
            $table->timestamps();
            $table->index(['client_id', 'bot_id', 'pattern'], 'machine_learnt_categories_ibix_1');
        });

        Schema::table('machine_learnt_categories', function (Blueprint $table) {
            $table->foreign('client_id', 'machine_learnt_categories_ibfk_1')->references('id')->on('clients')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('bot_id', 'machine_learnt_categories_ibfk_2')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreign('turn_id', 'machine_learnt_categories_ibfk_3')->references('id')->on('turns')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('machine_learnt_categories', function (Blueprint $table) {
            $table->dropForeign('machine_learnt_categories_ibfk_1');
            $table->dropForeign('machine_learnt_categories_ibfk_2');
            $table->dropForeign('machine_learnt_categories_ibfk_3');
        });

        Schema::dropIfExists('machine_learnt_categories');
    }
}

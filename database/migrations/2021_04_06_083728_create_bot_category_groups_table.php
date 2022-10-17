<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotCategoryGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_category_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('slug')->unique();
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->unsignedBigInteger('bot_id')->index('bot_id');
            $table->unsignedBigInteger('category_group_id')->index('category_group_id');
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['bot_id', 'category_group_id'], 'bot_category_groups_ibuq_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_category_groups');
    }
}

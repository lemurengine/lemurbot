<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePluginsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->index('user_id');
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('description');
            $table->text('classname');
            $table->enum('apply_plugin', ['pre', 'post']);
            $table->boolean('return_onchange')->default(1);
            $table->boolean('is_master')->default(0);
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

        Schema::drop('plugins');
	}

}

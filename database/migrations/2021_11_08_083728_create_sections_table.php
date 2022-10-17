<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->integer('order');
            $table->string('type');
            $table->string('default_state');
            $table->boolean('is_protected');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('user_id', 'sections_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropForeign('sections_ibfk_1');
        });
        Schema::dropIfExists('sections');
    }
}

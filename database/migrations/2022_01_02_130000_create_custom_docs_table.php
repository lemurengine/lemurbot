<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomDocsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_docs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id');
            $table->string('slug')->unique();
            $table->string('title')->unique();
            $table->text('body');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('custom_docs', function (Blueprint $table) {
            $table->foreign('user_id', 'custom_docs_ibfk_1')->references('id')->on('users')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('custom_docs', function (Blueprint $table) {
            $table->dropForeign('custom_docs_ibfk_1');
        });

        Schema::dropIfExists('custom_docs');

    }
}

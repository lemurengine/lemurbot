<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemovingEnums extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->string('status', 1)->default('A')->change();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->string('status', 1)->default('A')->change();
        });
        Schema::table('category_groups', function (Blueprint $table) {
            $table->string('status', 1)->default('A')->change();
        });
        Schema::table('plugins', function (Blueprint $table) {
            $table->string('apply_plugin', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bots', function (Blueprint $table) {
            $table->enum('status', ['H','T','A'])->default('A')->change();
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->enum('status', ['H','T','A'])->default('A')->change();
        });
        Schema::table('category_groups', function (Blueprint $table) {
            $table->enum('status', ['H','T','A'])->default('A')->change();
        });
        Schema::table('plugins', function (Blueprint $table) {
            $table->enum('apply_plugin', ['pre', 'post'])->change();
        });
    }
}

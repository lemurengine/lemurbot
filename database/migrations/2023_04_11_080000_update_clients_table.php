<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LemurEngine\LemurBot\Models\EmptyResponse;

class UpdateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * drop and rebuild keys with cascade - cascade
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->index('user_id')->after('bot_id');
            $table->string('image', 256)->nullable()->after('slug');
            $table->string('name', 50)->nullable()->after('slug');
        });
    }

    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('image');
            $table->dropColumn('name');
        });
    }
}

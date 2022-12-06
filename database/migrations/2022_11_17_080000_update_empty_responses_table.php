<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LemurEngine\LemurBot\Models\EmptyResponse;

class UpdateEmptyResponsesTable extends Migration
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
        Schema::table('empty_responses', function (Blueprint $table) {
            $table->text('that')->nullable()->after('bot_id');
        });
    }

    public function down()
    {
        Schema::table('empty_responses', function (Blueprint $table) {
            $table->unique(['bot_id', 'input'], 'empty_responses_ibuq_1');
        });
    }
}

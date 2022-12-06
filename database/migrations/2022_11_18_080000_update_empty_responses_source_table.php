<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LemurEngine\LemurBot\Models\EmptyResponse;

class UpdateEmptyResponsesSourceTable extends Migration
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
            $table->string('source')->nullable()->after('that');
            $table->dropUnique('empty_responses_ibuq_1');
            $table->unique(['bot_id','that', 'input', 'source'], 'empty_responses_ibuq_1');
        });
    }

    public function down()
    {
        Schema::table('empty_responses', function (Blueprint $table) {
            $table->dropUnique('empty_responses_ibuq_1');
            //when we try to drop the source field.. we are going to make un-unique key combinations
            //so lets just clear anything where that is not null
            EmptyResponse::whereIsNotNull('source')->delete();
            $table->dropColumn('source');
            $table->unique(['bot_id','that', 'input'], 'empty_responses_ibuq_1');
        });
    }
}

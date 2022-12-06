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
            $table->dropUnique('empty_responses_ibuq_1');
            $table->unique(['bot_id','that', 'input'], 'empty_responses_ibuq_1');
        });
    }

    public function down()
    {
        Schema::table('empty_responses', function (Blueprint $table) {
            //when we try to drop the that field.. we are going to make un-unique key combinations
            //so lets just clear anything where that is not null
            EmptyResponse::whereIsNotNull('that')->delete();
            $table->dropUnique('empty_responses_ibuq_1');
            $table->dropColumn('that');
            $table->unique(['bot_id', 'input'], 'empty_responses_ibuq_1');
        });
    }
}

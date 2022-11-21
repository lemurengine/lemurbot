<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->string('that')->nullable()->after('bot_id');
            $table->dropUnique('empty_responses_ibuq_1');
            $table->unique(['bot_id','that', 'input'], 'empty_responses_ibuq_1');
        });
    }

    public function down()
    {
        Schema::table('empty_responses', function (Blueprint $table) {
            $table->dropColumn('that');
            $table->dropUnique('empty_responses_ibuq_1');
            $table->unique(['bot_id', 'input'], 'empty_responses_ibuq_1');
        });
    }
}

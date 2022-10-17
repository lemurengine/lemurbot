<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToConversationPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conversation_properties', function (Blueprint $table) {
            $table->foreign('conversation_id', 'conversation_properties_ibfk_1')->references('id')->on('conversations')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conversation_properties', function (Blueprint $table) {
            $table->dropForeign('conversation_properties_ibfk_1');
        });
    }
}

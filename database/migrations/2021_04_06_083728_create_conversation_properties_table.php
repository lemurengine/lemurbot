<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConversationPropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversation_properties', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('conversation_id')->index('conversation_id');
            $table->string('slug')->unique();
            $table->string('name');
            $table->text('value');
            $table->timestamps();
            $table->softDeletes();
            $table->unique(['conversation_id', 'name'], 'conversation_properties_ibuq_1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conversation_properties');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateForeignKeysRelatedToBotTable extends Migration
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
        Schema::table('bot_category_groups', function (Blueprint $table) {
            $table->dropForeign('bot_category_groups_ibfk_2');
        });

        Schema::table('bot_keys', function (Blueprint $table) {
            $table->dropForeign('bot_keys_ibfk_1');
        });

        Schema::table('bot_properties', function (Blueprint $table) {
            $table->dropForeign('bot_properties_ibfk_1');
        });

        Schema::table('bot_ratings', function (Blueprint $table) {
            $table->dropForeign('bot_ratings_ibfk_1');
        });

        Schema::table('bot_word_spelling_groups', function (Blueprint $table) {
            $table->dropForeign('bot_word_spelling_groups_ibfk_2');
        });
        Schema::table('client_categories', function (Blueprint $table) {
            $table->dropForeign('client_categories_ibfk_2');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign('clients_ibfk_1');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign('conversations_ibfk_1');
        });

        Schema::table('empty_responses', function (Blueprint $table) {
            $table->dropForeign('empty_responses_ibfk_1');
        });

        Schema::table('bot_category_groups', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_category_groups_ibfk_2')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('bot_keys', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_keys_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('bot_properties', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_properties_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('bot_ratings', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_ratings_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('bot_word_spelling_groups', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_word_spelling_groups_ibfk_2')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
        Schema::table('client_categories', function (Blueprint $table) {
            $table->foreign('bot_id', 'client_categories_ibfk_2')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('bot_id', 'clients_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreign('bot_id', 'conversations_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('CASCADE');
        });

        Schema::table('empty_responses', function (Blueprint $table) {
            $table->foreign('bot_id', 'empty_responses_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('CASCADE');
        });
    }

    /**
     * revert and return all keys back to cascade - restrict
     */
    public function down()
    {
        Schema::table('bot_category_groups', function (Blueprint $table) {
            $table->dropForeign('bot_category_groups_ibfk_2');
        });

        Schema::table('bot_keys', function (Blueprint $table) {
            $table->dropForeign('bot_keys_ibfk_1');
        });

        Schema::table('bot_properties', function (Blueprint $table) {
            $table->dropForeign('bot_properties_ibfk_1');
        });

        Schema::table('bot_ratings', function (Blueprint $table) {
            $table->dropForeign('bot_ratings_ibfk_1');
        });

        Schema::table('bot_word_spelling_groups', function (Blueprint $table) {
            $table->dropForeign('bot_word_spelling_groups_ibfk_2');
        });
        Schema::table('client_categories', function (Blueprint $table) {
            $table->dropForeign('client_categories_ibfk_2');
        });
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign('clients_ibfk_1');
        });

        Schema::table('conversations', function (Blueprint $table) {
            $table->dropForeign('conversations_ibfk_1');
        });

        Schema::table('empty_responses', function (Blueprint $table) {
            $table->dropForeign('empty_responses_ibfk_1');
        });

        Schema::table('bot_category_groups', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_category_groups_ibfk_2')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });

        Schema::table('bot_keys', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_keys_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });

        Schema::table('bot_properties', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_properties_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });

        Schema::table('bot_ratings', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_ratings_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });

        Schema::table('bot_word_spelling_groups', function (Blueprint $table) {
            $table->foreign('bot_id', 'bot_word_spelling_groups_ibfk_2')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
        Schema::table('client_categories', function (Blueprint $table) {
            $table->foreign('bot_id', 'client_categories_ibfk_2')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('bot_id', 'clients_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
        Schema::table('conversations', function (Blueprint $table) {
            $table->foreign('bot_id', 'conversations_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });

        Schema::table('empty_responses', function (Blueprint $table) {
            $table->foreign('bot_id', 'empty_responses_ibfk_1')->references('id')->on('bots')->onUpdate('CASCADE')->onDelete('RESTRICT');
        });
    }
}

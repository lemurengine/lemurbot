<?php

namespace LemurEngine\LemurBot\Factories;

use LemurEngine\LemurBot\Models\Conversation;

class ConversationFactory
{
    /**
     * @param $bot
     * @param $client
     * @param $allowHtml
     * @param $forceNew
     * @return Conversation
     */
    public static function getConversationByBotClientOrCreate($bot, $client, $allowHtml = 1, $forceNew = false)
    {

        if (!$forceNew) {
            $conversation = Conversation::where('bot_id', $bot->id)
                ->where('client_id', $client->id)->latest('id')->first();
        } else {
            $conversation = null;
        }

        if ($conversation == null) {
            $conversation = new Conversation();
            $conversation->bot_id=$bot->id;
            $conversation->client_id=$client->id;
            $conversation->allow_html=$allowHtml;
            $conversation->save();
        }

        return $conversation;
    }
}

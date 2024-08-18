<?php

namespace LemurEngine\LemurBot\Factories;

use Exception;
use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Turn;

class TurnFactory
{
    /**
     * @param $conversation
     * @param $input
     * @param $source
     * @param null $parentTurnId
     * @return Turn
     */
    public static function createTurn($conversation, $input, $source, $parentTurnId = null)
    {
        LemurLog::debug(
            'creating turn'
        );

        $turn = new Turn();
        $turn->conversation_id = $conversation->id; //this seem to fix the bottleneck
        $turn->save();
        $turn->parent_turn_id = $parentTurnId;
        $turn->input = $input['message'];
        $turn->source = $source;
        $turn->save();
        return $turn;
    }


    /**
     * @param $conversation
     * @param $input
     * @return Turn
     * @param $source
     * @throws Exception
     */
    public static function createCompleteTurn($conversation, $input, $source, $parentTurnId = null)
    {

        LemurLog::debug(
            'completing turn'
        );

        $currentLog = new Turn();
        $currentLog->conversation_id = $conversation->id; //this seem to fix the bottleneck
        $currentLog->save();
        $currentLog->parent_turn_id = $parentTurnId;
        $currentLog->input = $input['message'];
        $currentLog->status = 'C';
        $currentLog->source = $source;
        $currentLog->save();

        return $conversation->currentConversationTurn;

    }
}

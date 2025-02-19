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

        // Create the Turn instance with all attributes set before saving
        $turn = new Turn([
            'conversation_id' => $conversation->id,
            'parent_turn_id' => $parentTurnId,
            'input' => $input['message'],
            'source' => $source
        ]);

        // Save only once
        $turn->save();

        LemurLog::debug(
            'turn created'
        );

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

        // Create the Turn instance with all attributes set before saving
        $currentLog = new Turn([
            'conversation_id' => $conversation->id,
            'parent_turn_id' => $parentTurnId,
            'input' => $input['message'],
            'status' => 'C',
            'source' => $source,
        ]);

        // Save only once
        $currentLog->save();

        LemurLog::debug(
            'turn completed'
        );

        return $conversation->currentConversationTurn;
    }
}

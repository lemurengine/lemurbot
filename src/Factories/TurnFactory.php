<?php

namespace LemurEngine\LemurBot\Factories;

use Exception;
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
        $turn = new Turn([
            'conversation_id' => $conversation->id,
            'parent_turn_id' => $parentTurnId,
            "input" => $input['message'],
            "source" => $source
        ]);
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
        $currentLog = new Turn([
            'conversation_id' => $conversation->id,
            'parent_turn_id' =>$parentTurnId,
            "input" => $input['message'],
            'status' => 'C',
            "source" => $source
        ]);
        $currentLog->save();


        $currentLog = $conversation->currentConversationTurn;

        return $currentLog;
    }
}

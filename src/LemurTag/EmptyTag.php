<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Classes\LemurStr;
use LemurEngine\LemurBot\Models\EmptyResponse;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class EmptyTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class EmptyTag extends AimlTag
{
    protected string $tagName = "Empty";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * EmptyTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     * Saves the contents of this tag to the empty_responses table
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }

    public function closeTag()
    {

        LemurLog::debug(
            __FUNCTION__,
            [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId(),
                'attributes'=>$this->getAttributes()
            ]
        );

        $input = $this->getCurrentTagContents();
        $that = $this->conversation->getThat('human');
        $input = LemurStr::cleanKeepSpace($input);

        $empty = EmptyResponse::where('bot_id', $this->conversation->bot->id)->where('input', 'like', $input)->where('that', 'like', $that)->first();
        if ($empty===null) {
            $emptyResponse = new EmptyResponse();
            $emptyResponse->bot_id = $this->conversation->bot->id;
            $emptyResponse->that = $that;
            $emptyResponse->input = $input;
            $emptyResponse->occurrences = 1;
            $emptyResponse->save();
        } else {
            $empty->occurrences++;
            $empty->save();
        }
    }
}

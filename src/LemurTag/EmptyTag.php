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
        $source = $this->conversation->getSource();
        $input = LemurStr::cleanKeepSpace($input);

        $empty = EmptyResponse::where('bot_id', $this->conversation->bot->id)
            ->where(function ($query) use ($input) {
                $query->where('input', $input)
                    ->orWhere('input', 'like', $input);
            })->where(function ($query) use ($that) {
                $query->where('that', $that)
                    ->orWhere('that', 'like', $that);
            })
            ->where('source', $source)
            ->first();

        if ($empty===null) {
            $emptyResponse = new EmptyResponse();
            $emptyResponse->bot_id = $this->conversation->bot->id;
            $emptyResponse->that = $that;
            $emptyResponse->input = $input;
            $emptyResponse->source = $source;
            $emptyResponse->occurrences = 1;
            $emptyResponse->save();
        } else {
            $empty->that = $that;
            $empty->source = $source;
            $empty->occurrences++;
            $empty->save();
        }
    }
}

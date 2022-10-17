<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class RestTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class RestTag extends AimlTag
{
    protected string $tagName = "Rest";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * RestTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }

    /**
     */
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

        $contents = trim($this->getCurrentTagContents(true));
        $tokens = explode(' ', $contents);
        array_shift($tokens);
        $contents = implode(' ', $tokens);
        $this->buildResponse(trim($contents));
    }
}

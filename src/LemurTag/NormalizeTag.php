<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Classes\LemurStr;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class NormalizeTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class NormalizeTag extends AimlTag
{
    protected string $tagName = "Normalize";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;


    /**
     * NormalizeTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }


    /**
     * when we close the <set> tag we need to decide if we want
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

        $contents = $this->getCurrentTagContents();

        $this->buildResponse(LemurStr::normalize($contents));
    }
}

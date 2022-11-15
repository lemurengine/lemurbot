<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class BotTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class ForFunTag extends AimlTag
{
    protected string $tagName = "ForFun";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * BotTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
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

        $botProperty = $this->conversation->getBotProperty('forfun');

        if ($botProperty === null) {
            $forFun = $this->getUnknownValueStr('bot_property');
        } else {
            $forFun = $botProperty->value;
        }

        $this->buildResponse($forFun);
    }

}

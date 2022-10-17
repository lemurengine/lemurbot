<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class SetTopicTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class SetTopicTag extends SetTag
{
    protected string $tagName = "SetTopic";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * SettopicTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes)
    {
        $attributes['name']='topic';

        parent::__construct($conversation, $attributes);
    }
}

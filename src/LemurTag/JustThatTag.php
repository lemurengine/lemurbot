<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class JustThatTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class JustThatTag extends ThatTag
{
    protected string $tagName = "JustThat";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * JustthatTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes)
    {
        parent::__construct($conversation, $attributes);
    }
}

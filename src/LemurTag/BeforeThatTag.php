<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class BeforeThatTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class BeforeThatTag extends ThatTag
{
    protected string $tagName = "BeforeThat";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;
    /**
     * BeforeThatTag Constructor.
     * Just here for backwards compatibility
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes)
    {
        $attributes['index']='1';
        parent::__construct($conversation, $attributes);
    }
}

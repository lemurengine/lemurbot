<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class SetnameTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class SetnameTag extends SetTag
{
    protected string $tagName = "Setname";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * SetnameTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes)
    {
        $attributes['name']='name';

        parent::__construct($conversation, $attributes);
    }
}

<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class GetnameTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class GetNameTag extends GetTag
{
    protected string $tagName = "GetName";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * GetnameTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes)
    {
        $attributes['name']='name';

        parent::__construct($conversation, $attributes);
    }
}

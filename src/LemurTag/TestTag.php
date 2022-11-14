<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class TestTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class TestTag extends AimlTag
{
    protected string $tagName = "Test";
    protected $someVar = "something";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * this is purely used for testing so we can test the abstract AimlTag class
     *
     * @param Conversation $conversation
     * @param array $attributes
     */

    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }
}

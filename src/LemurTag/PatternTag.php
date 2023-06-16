<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class PatternTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class PatternTag extends AimlTag
{
    protected string $tagName = "Pattern";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;


    /**
     * PatternTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes

    //this has been intentionally left empty
    //there are no pattern tests
    //why?
    //because the pattern tag is NOT parsed at run time
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }
}

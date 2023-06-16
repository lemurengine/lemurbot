<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class Category
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 *
 * this has been intentionally left empty
 * why? because the category tag is NOT parsed at run time
 */
class CategoryTag extends AimlTag
{
    protected string $tagName = "Category";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * CategoryTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }
}

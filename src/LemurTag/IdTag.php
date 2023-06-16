<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class IdTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class IdTag extends AimlTag
{
    protected string $tagName = "Id";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;


    /**
     * IdTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }

    public function closeTag()
    {

        $conversationSlug = $this->conversation->slug;
        $this->buildResponse($conversationSlug);
    }
}

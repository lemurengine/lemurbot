<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class DefaultTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class DefaultTag extends AimlTag
{
    protected string $tagName = "Default";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;
    /**
     * DefaultTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {


        parent::__construct($conversation, $attributes);
    }




    public function closeTag()
    {

        $this->buildResponse($this->conversation->bot->default_response);
    }
}

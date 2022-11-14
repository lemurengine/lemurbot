<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class ATag
 * example: <a href="https://www.google.com">Google</a>
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class ATag extends HtmlTag
{
    protected string $tagName = 'A';

    /**
     * Extends the default HTML tags
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes, $this->tagName, HtmlTag::HTMLTAG_WRAPPED);
    }

}

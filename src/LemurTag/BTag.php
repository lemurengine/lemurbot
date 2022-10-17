<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class ATag
 * example: <b>I AM BOLD</b>
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class BTag extends HtmlTag
{
    protected string $tagName = 'B';

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

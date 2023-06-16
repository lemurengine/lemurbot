<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Conversation;
use SimpleXMLElement;

/**
 * Class RespondButton
 * example: <respondbutton>Yes</respondbutton><respondbutton>No</respondbutton>
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class RespondButtonTag extends HtmlTag
{
    protected string $tagName = 'RespondButton';

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

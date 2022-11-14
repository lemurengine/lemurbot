<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class NameTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class NameTag extends AimlTag
{
    protected string $tagName = "Name";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * NameTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }


    public function closeTag()
    {

        //previously a tag such as <get> or <set> will have been called.....
        $previousObject = $this->getPreviousTagObject();
        $contents = $this->getCurrentTagContents(true);
        //$parentObject[$tagName]->processContents($this->_tagContents);
        $previousObject->setAttributes(['NAME'=>$contents]);
        $previousObjectIndex = $this->getTagStack()->maxPosition()-1;

        $this->getTagStack()->overWrite($previousObject, $previousObjectIndex);
    }
}

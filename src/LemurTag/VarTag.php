<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class VarTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class VarTag extends AimlTag
{
    protected string $tagName = "Var";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * VarTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }



    public function closeTag()
    {

                LemurLog::debug(
                    __FUNCTION__,
                    [
                    'conversation_id'=>$this->conversation->id,
                    'turn_id'=>$this->conversation->currentTurnId(),
                    'tag_id'=>$this->getTagId(),
                    'attributes'=>$this->getAttributes()
                    ]
                );

        //previously a tag such as <get> or <set> will have been called.....
        $previousObject = $this->getPreviousTagObject();
        $contents = $this->getCurrentTagContents(true);
        //$parentObject[$tagName]->processContents($this->_tagContents);
        $previousObject->setAttributes(['VAR'=>$contents]);
        $previousObjectIndex = $this->getTagStack()->maxPosition()-1;
        $this->getTagStack()->overWrite($previousObject, $previousObjectIndex);
    }
}

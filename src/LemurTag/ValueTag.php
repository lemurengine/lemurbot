<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class ValueTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class ValueTag extends AimlTag
{
    protected string $tagName = "Value";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * ValueTag Constructor.
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
        $start_time = microtime(true);
        $parentObject = $this->getPreviousTagObject();

        //we are inside the name tag therefore if the attribute does not have a key then we set it to name
        if (!isset($this->tagContents['VALUE'])&&(isset($this->tagContents[0]))) {
            $this->tagContents['VALUE']=$this->tagContents[0];
        }

        //there is a bug with this...
        //when using the <value></value> syntax the tag stack some how lost the value tag
        //i have 'fixed' this by just resolving out the tags into attributes in the cleanaiml function
        //$parentObject[$tagName]->processContents($this->_tagContents);
        $parentObject->setAttributes($this->tagContents);
        $parentObject->setAttributes($this->tagContents);


        $this->tagContents=[];
    }
}

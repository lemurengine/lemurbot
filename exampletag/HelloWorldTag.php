<?php
namespace App\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\LemurTag\AimlTag;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class HelloWorldTag
 * @package App\LemurTag
 *
 * Usage: <helloworld />
 *
 * Example AIML:
 * <category>
 *  <pattern>TEST HELLO WORLD</pattern>
 *  <template><helloworld /></template>
 * </category>
 *
 * Expected Conversation:
 * Input: Test Hello World
 * Output: Hello World!
 *
 * Documentation:
 * https://docs.lemurengine.com/extend.html
 */
class HelloWorldTag extends AimlTag
{
    protected string $tagName = "HelloWorld";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;


    /**
     * HelloWorldTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }


    /**
     * This method is called when the closing tag is encountered e.g. <helloworld/>
     * @return string|void
     */
    public function closeTag()
    {
        //some debugging
        LemurLog::debug(
            __FUNCTION__, [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId(),
                'attributes'=>$this->getAttributes()
            ]
        );

        //build response in the stack
        $this->buildResponse("Hello World!");
    }
}

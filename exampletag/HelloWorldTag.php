<?php
namespace App\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\LemurTag\AimlTag;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class HelloworldTag
 * @package LemurEngine\LemurBot\LemurTag\Custom
 *
 * Usage: <helloworld />
 *
 * Example AIML:
 * <category>
 *  <pattern>TEST</pattern>
 *  <template><helloworld /></template>
 * </category>
 *
 * Expected Conversation:
 * Input: Test
 * Output: Hello World!
 *
 * Documentation:
 * https://docs.lemurbot.com/extend.html
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

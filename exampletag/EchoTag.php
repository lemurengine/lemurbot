<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class EchoTag
 * @package App\LemurTag
 *
 * Usage: <echo><star /></echo>
 *
 * Example AIML:
 * <category>
 *  <pattern>ECHO *</pattern>
 *  <template><echo><star /></echo></template>
 * </category>
 *
 * Expected Conversation:
 * Input: Echo Yikes
 * Output: Yikes
 *
 * Documentation:
 * https://docs.lemurbot.com/extend.html
 */
class EchoTag extends AimlTag
{
    protected string $tagName = "Echo";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;


    /**
     * FormalTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }


    /**
     * when we close the <set> tag we need to decide if we want
     */
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

        //this will return the value of <star />
        $contents = $this->getCurrentTagContents(true);
        $this->buildResponse($contents);
    }
}

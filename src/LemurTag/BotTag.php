<?php
namespace LemurEngine\LemurBot\LemurTag;

use Illuminate\Database\Eloquent\Relations\hasOne;
use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class BotTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class BotTag extends AimlTag
{
    protected string $tagName = "Bot";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * BotTag Constructor.
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

            //is the name attribute set?
        if ($this->hasAttribute('NAME')) {
            //get the name value
            $nameValue = $this->getAttribute('NAME');

            $value = $this->getBotProperty($nameValue);


            $this->buildResponse($value);
        }
    }


    /**
     * @param $nameValue
     * @return hasOne
     */
    public function getBotProperty($nameValue)
    {


        $botProperty = $this->conversation->getBotProperty($nameValue);

        if ($botProperty === null) {
            $botPropertyValue = $this->getUnknownValueStr('bot_property');
        } else {
            $botPropertyValue = $botProperty->value;
        }

        return $botPropertyValue;
    }
}

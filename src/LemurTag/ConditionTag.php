<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Services\TalkService;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class ConditionTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class ConditionTag extends AimlTag
{

    protected string $tagName = "Condition";
    protected $talkService;
    private $tmpContents = '';
    //this is a recursive tag
    static $aimlTagType = self::TAG_RECURSIVE;

    /**
     * ConditionTag Constructor.
     * @param Conversation $conversation
     * @param TalkService $talkService
     * @param array $attributes
     */
    public function __construct(TalkService $talkService, Conversation $conversation, array $attributes)
    {
        parent::__construct($conversation, $attributes);
        $this->talkService = $talkService;
    }

    public function getTmpContents()
    {
        return $this->tmpContents;
    }

    public function setTmpContents($tmpContents)
    {
        if ($this->checkAttribute('TYPE', 'LOOP')) {
            //we are in a loop... so we append the answer
            $this->tmpContents .= ' '.$tmpContents;
        } else {
            $this->tmpContents = $tmpContents;
        }
    }



    public function hasTmpContents()
    {
        return ($this->tmpContents!=''?true:false);
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


        //if this was a loop then we have already built the values by looping around...
        if ($this->checkAttribute('TYPE', 'LOOP')) {
            $tagContents = $this->getResponseFromReParse($this->tmpContents);
            $this->buildResponse($tagContents);
        } else {
            $tagContents = $this->getResponseFromReParse($this->tmpContents);
            $this->buildResponse($tagContents);
        }
    }
}

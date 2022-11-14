<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Wildcard;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class StarTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class StarTag extends AimlTag
{
    protected string $tagName = "Star";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * StarTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);

        //if there is no format set then set default index
        if (empty($attributes['INDEX'])) {
            $this->setAttributes(['INDEX'=>1]);
        }
    }



    public function closeTag()
    {



        LemurLog::debug(__FUNCTION__, [
                'conversation_id'=>$this->conversation->id,
                'turn_id'=>$this->conversation->currentTurnId(),
                'tag_id'=>$this->getTagId(),
                'attributes'=>$this->getAttributes()
            ]);

        $contents = $this->getCurrentTagContents(false);

        if ($this->isInLiTag()) {
            $newContents = $this->buildAIMLIfInDoNotParseMode($contents);
            $this->buildResponse($newContents);
        } else {
            $star = $this->buildResponseFromStar();
            $this->buildResponse($star);
            $this->checkSetTopicStar($star);
        }
    }

    public function buildResponseFromStar()
    {


        //get the index in question and if there is none set it to the default which is 1
        if ($this->hasAttribute('INDEX')) {
            $index = $this->getAttribute('INDEX');
        } else {
            $index = 1;
        }

        //For offset purposes 1=0, 2=1 etc so decremement the index by 1 for the offset
        $offset = $index-1;

        return $this->getStoredWildcard($offset);
    }


    public function getStoredWildcard($offset)
    {

        $star = Wildcard::where('conversation_id', $this->conversation->id)
            ->where('type', 'star')->latest('id')->skip($offset)->first();

        if ($star===null) {
            $value = $this->getUnknownValueStr('star');
        } else {
            $value = $star->value;
        }
        return $value;
    }


    public function checkSetTopicStar($star)
    {

        //we might be trying to set the topic... in which case do that here...
        $ParentObject = $this->getPreviousTagObject('Set');
        //if the set name attrib is present we could be setting a topic star so lets check
        if ($ParentObject && isset($ParentObject->attributes["NAME"])) {
            //todo i do not want these strtouppers i need a way to make this standard
            if (mb_strtoupper($ParentObject->attributes["NAME"]) == 'TOPIC') {
                //this star has been set in the context of a star.....
                //so we need to set to the topicstar as well....
                $wildcard = new Wildcard;
                $wildcard->conversation_id = $this->conversation->id;
                $wildcard->type = 'topicstar';
                $wildcard->value = $star;
                $wildcard->save();
            }
        }
    }
}

<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Wildcard;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class TopicStarTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class TopicStarTag extends AimlTag
{
    protected string $tagName = "TopicStar";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * TopicstarTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }

    public function closeTag()
    {


        //get the index in question and if there is none set it to the default which is 1
        if ($this->hasAttribute('INDEX')) {
            $index = $this->getAttribute('INDEX');
        } else {
            $index = 1;
        }

        //For offset purposes 1=0, 2=1 etc so decrement the index by 1 for the offset
        $offset = $index-1;

        $star = Wildcard::where('conversation_id', $this->conversation->id)
            ->where('type', 'topicstar')->latest('id')->skip($offset)->first();

        if ($star===null) {
            $value = $this->getUnknownValueStr('topicstar');
        } else {
            $value = $star->value;
        }

        $this->buildResponse($value);
    }
}

<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurStr;
use LemurEngine\LemurBot\Models\Turn;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class ThatTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class ThatTag extends AimlTag
{
    protected string $tagName = "That";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * ThatTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }


    /**
     * @return string|void
     */
    public function closeTag()
    {


        //get the index in question and if there is none set it to the default which is 1
        if ($this->hasAttribute('INDEX')) {
            $index = $this->getAttribute('INDEX');
        } else {
            $index = 1;
        }

        $position = explode(",", $index);

        if (!isset($position[1])) {
            $position[1] = 1;
        }


        //For offset purposes 1=0, 2=1 etc so decrememnt the index by 1 for the offset
        $offset = $position[0];
        //as we using an array we can consider position 1 to be index 0 in the array
        $sentencePosition = $position[1]-1;

        //this is a v lazy way of doing this
        $turn = Turn::where('conversation_id', $this->conversation->id)
            ->where('source', 'human')->latest('id')->skip($offset)->first();

        if ($turn!==null) {
            $allTurnSentences = LemurStr::splitIntoSentences($turn->output);
            //now flip it as the last sentence = 1 (in AIML world)
            $allTurnSentences = array_reverse($allTurnSentences);
        }

        if (!isset($allTurnSentences[$sentencePosition])) {
            $that = $this->getUnknownValueStr('response');
        } else {
            $that = trim($allTurnSentences[$sentencePosition]);
        }

        $this->buildResponse($that);
    }
}

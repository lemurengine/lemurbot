<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Turn;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class ResponseTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class ResponseTag extends AimlTag
{
    protected string $tagName = "Response";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * Response Constructor.
     * @param Conversation $conversation
     * @param array $attributes

     * <response/> - the current response (all sentences in that response)
     * <response index="2"/> - the previous response (all sentences in that response)
     * <response index="N"/> - the nth response (all sentences in that response)
     * e.g. User: Hello. My name is Bob. Bot: Hi Bob. nice to meet you
     * Hi Bob. nice to meet you = <that/>
     * 'unknown' = <response index="2"/>
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
            $offset = $this->getAttribute('INDEX');
        } else {
            //1 is equal to the last response...
            $offset = 1;
        }



        $turn = Turn::where('conversation_id', $this->conversation->id)
            ->where('source', 'human')->latest('id')->skip($offset)->first();

        if ($turn===null) {
            $output = $this->getUnknownValueStr('response');
        } else {
            $output = $turn->output;
        }

        $this->buildResponse($output);
    }
}

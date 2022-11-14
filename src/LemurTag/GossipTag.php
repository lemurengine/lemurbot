<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\ClientCategory;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class GossipTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class GossipTag extends AimlTag
{
    protected string $tagName = "Gossip";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * GossipTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }


    /**
     * when we close the tag
     * just return send the response all the way up the tag stack
     * all the way up to the template tag
     *
     * @return string
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

        //if we are in learning mode send the response back up the stack
        if ($this->isInLearningMode()) {
            $contents = $this->getCurrentTagContents(true);

            $contents = str_replace("unknown said", "someone said", $contents);

            $contents = "<category><pattern>GOSSIP</pattern><template>$contents</template></category>";

            $this->buildResponse($contents);
        } else {
            $botId = $this->conversation->bot->id;

            $clientCategory = ClientCategory::where('pattern', 'GOSSIP')
                ->where('bot_id', $botId)->inRandomOrder()->first();
            if ($clientCategory!=null) {
                return $clientCategory->template;
            } else {
                return "I dont know any gossip";
            }
        }
    }
}

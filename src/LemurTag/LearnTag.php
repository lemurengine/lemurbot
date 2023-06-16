<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Classes\LemurStr;
use LemurEngine\LemurBot\Models\ClientCategory;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class LearnTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class LearnTag extends AimlTag
{
    protected string $tagName = "Learn";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * LearnTag Constructor.
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


        $contents = $this->getCurrentTagContents(true);


        if(trim($contents)==''){
            return;
        }

        $contents = LemurStr::removeTrailingKeepSpace($contents);

        $aiml = simplexml_load_string($contents);

        $pattern = LemurStr::cleanForFinalOutput($aiml->pattern);
        $template = LemurStr::cleanForFinalOutput($aiml->template);


        $botId = $this->conversation->bot->id;
        $clientId = $this->conversation->client->id;
        $turnId = $this->conversation->currentTurnId();

            $clientCategory = new ClientCategory();
            $clientCategory->pattern=$pattern;
            $clientCategory->template=$template;
            $clientCategory->bot_id=$botId;
            $clientCategory->client_id=$clientId;
            $clientCategory->turn_id=$turnId;
            $clientCategory->tag=strtolower($this->tagName);
            $clientCategory->save();


    }
}

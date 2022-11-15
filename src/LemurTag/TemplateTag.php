<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurStr;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class TemplateTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class TemplateTag extends AimlTag
{
    protected string $tagName = "Template";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }



    public function closeTag()
    {

        //if we are in learning mode send the response back up the stack
        if ($this->isInLearningMode()) {
            //if we are in learning mode we will do something else instead of evaluating the contents
            //it will turn it back into aiml for saving...
            $contents = $this->getCurrentTagContents(true);
            $contents = $this->buildAIMLIfInLearnMode($contents);

            $this->buildResponse($contents);
        } else {
            //if not this is the last tag ... just send the contents
            $contents = $this->getCurrentTagContents(true);
            return LemurStr::cleanForFinalOutput($contents);
        }
    }
}

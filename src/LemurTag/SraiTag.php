<?php
namespace LemurEngine\LemurBot\LemurTag;

use Exception;
use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Services\TalkService;
use LemurEngine\LemurBot\Models\Conversation;
use function Ramsey\Uuid\v1;

/**
 * Class SraiTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class SraiTag extends AimlTag
{
    protected string $tagName = "Srai";
    protected TalkService $talkService;
    protected int $sraiCount;
    protected int $maxSraiCount;
    //this is a recursive tag
    static $aimlTagType = self::TAG_RECURSIVE;

    /**
     * SraiTag Constructor.
     * @param TalkService $talkService
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(TalkService $talkService, Conversation $conversation, array $attributes)
    {
        parent::__construct($conversation, $attributes);
        $this->talkService = $talkService;
        $this->sraiCount = (int)$conversation->getVar('srai-count', 0);
        $this->maxSraiCount = $this->getMaxSraiCountFromConfig();
    }


    /**
     * @return string|void
     * @throws Exception
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

        //check if we have reached the max levels of srai recursion
        //if so through an exception

        if ($this->isInLiTag()) { //if we are in a LI tag...
            $this->buildResponse("<srai>" . $contents . "</srai>");
        } else {
            $this->buildResponse($this->getResponseFromNewTalk($contents));
        }
    }


    public function getMaxSraiCountFromConfig()
    {

        return config('lemurbot.tag.recursion.max', 10);
    }

    public function checkMaxSraiReached()
    {
        $pid= $this->conversation->currentParentTurnId();
        $sraiCount = $this->conversation->countOpenSraiTags($pid);
        return ($sraiCount >= $this->maxSraiCount); //if we have maxed out the recursions then we should exit
    }



    public function getResponseFromNewTalk($contents)
    {

        $this->sraiCount++;

        if ($this->checkMaxSraiReached()) {
            //mark the turn as complete
            $this->conversation->completeTurn('E');
            return config('lemurbot.tag.recursion.message');
        }

        $this->conversation->setVar('srai-count', $this->sraiCount);

        $this->talkService->initFromTag($this->conversation, $contents, 'srai');

        $this->talkService->talk($contents);
        $response = $this->talkService->getOutput();
        $reParsedConversation = $this->talkService->getConversation();

        //set the debug
        $this->extractDebug($reParsedConversation);

        //copy local vars back for debugging
        //as we are still in the same turn carry all the vars over
        $vars = $reParsedConversation->getVars();
        foreach ($vars as $name => $value) {
            $this->conversation->setVar($name, $value);
        }
        return $response;
    }

    public function extractDebug($reParsedConversation)
    {

        //get all the debugArr and set it so we can see what happened
        //remember we only get the debug if in ui mode so this may be empty
        $output = $this->talkService->responseOutput($reParsedConversation);
        if (!empty($output['debugArr'])) {
            $this->conversation->setDebug('reparse', $output['debugArr']);
        }
    }
}

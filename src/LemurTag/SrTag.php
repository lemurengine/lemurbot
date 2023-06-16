<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Services\TalkService;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class SrTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class SrTag extends SraiTag
{
    protected string $tagName = "Sr";
    //this is a recursive tag
    static $aimlTagType = self::TAG_RECURSIVE;

    /**
     * SrTag Constructor.
     * @param TalkService $talkService
     * @param Conversation $conversation
     * @param array $attributes
     *
     * this will never get called as a method called expandSr($template){
     * expands the <sr/> tag to <srai><star/></srai>
     */
    public function __construct(TalkService $talkService, Conversation $conversation, array $attributes)
    {
        parent::__construct($talkService, $conversation, $attributes);
    }
}

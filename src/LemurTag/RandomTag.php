<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Services\TalkService;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class RandomTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurengine.com/aiml.html
 */
class RandomTag extends AimlTag
{
    //this is a recursive tag
    static $aimlTagType = self::TAG_RECURSIVE;
    /**
     * Random Constructor.
     * There isnt really anything to do here...
     * the random item are extracted as part of the aimlParser
     * @param TalkService $talkService
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(TalkService $talkService, Conversation $conversation, array $attributes)
    {
        parent::__construct($conversation, $attributes);
    }
}

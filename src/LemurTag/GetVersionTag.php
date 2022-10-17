<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class GetVersionTag
 * @package LemurEngine\LemurBot\LemurTag
 */
class GetVersionTag extends VersionTag
{
    protected string $tagName = "GetVersion";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * GetversionTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes)
    {
        parent::__construct($conversation, $attributes);
    }
}

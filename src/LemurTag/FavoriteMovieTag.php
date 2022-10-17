<?php
namespace LemurEngine\LemurBot\LemurTag;

use LemurEngine\LemurBot\Classes\LemurLog;
use LemurEngine\LemurBot\Models\Conversation;

/**
 * Class BotTag
 * @package LemurEngine\LemurBot\LemurTag
 * Documentation on this tag, examples and explanation
 * see: https://docs.lemurbot.com/aiml.html
 */
class FavoriteMovieTag extends AimlTag
{
    protected string $tagName = "FavouriteMovie";
    //this is a standard tag
    static $aimlTagType = self::TAG_STANDARD;

    /**
     * BotTag Constructor.
     * @param Conversation $conversation
     * @param array $attributes
     */
    public function __construct(Conversation $conversation, array $attributes = [])
    {
        parent::__construct($conversation, $attributes);
    }

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

        $botProperty = $this->conversation->getBotProperty('favoritemovie');

        if ($botProperty === null) {
            $favoriteMovie = $this->getUnknownValueStr('bot_property');
        } else {
            $favoriteMovie = $botProperty->value;
        }

        $this->buildResponse($favoriteMovie);
    }

}
